<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isNull;

class SwaggerCodeGenCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:swagger-codegen {--tag=} {--force} {--withC}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Swagger定義から自動生成';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'SwaggerDefinition';
    private array $swaggerArr = array();

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/';
    }

    protected function getStubCustom($resourceType): string
    {
        $stubName = '';
        if ($resourceType == 'request') {
            $stubName = 'request.stub';
        } elseif ($resourceType == 'requestDefinition') {
            $stubName = 'requestDefinition.stub';
        } elseif ($resourceType == 'resultResource') {
            $stubName = 'resource.stub';
        } elseif ($resourceType == 'resultDefinition') {
            $stubName = 'resultDefinition.stub';
        } elseif ($resourceType == 'controller') {
            $stubName = 'controller.stub';
        }
        return $this->getStub() . $stubName;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     * @return string
     */
    protected function qualifyClassCustom(string $name, $resourceType, $tag): string
    {
        if ($resourceType == 'request') {
            $customName = 'Requests/' . $tag . '/' . $name;
        } elseif ($resourceType == 'requestDefinition') {
            $customName = 'Requests/Definition/' . $tag . '/' . $name;
        } elseif ($resourceType == 'resultResource') {
            $customName = 'Resources/' . $tag . '/' . $name;
        } elseif ($resourceType == 'resultDefinition') {
            $customName = 'Resources/Definition/' . $tag . '/' . $name;
        } elseif ($resourceType == 'controller') {
            $customName = 'Controllers/' . $tag . '/' . $name . 'Controller';
        }
        return $this->qualifyClass($customName);
    }

    public function getDefinitionName($name)
    {
        return str_replace('#/definitions/', '', $name);
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getRoutesPath($name)
    {
        return base_path('routes/' . $name . '.php');
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $resource = file_get_contents(resource_path() . '/swagger/api.json');
        $json = mb_convert_encoding($resource, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $this->swaggerArr = json_decode($json, true);

        $apiPaths = $this->swaggerArr['paths'];

        $confirmContents = "強制的にRequest,Resource群が書き換わりますがよろしいですか？";

        if ($this->hasOption('withC') && $this->option('withC')) {
            $confirmContents = '強制的にコントローラとRequest,Resource群が書き換わりますがよろしいですか？';
        }

        if (($this->hasOption('force') && $this->option('force')) &&
            !$this->confirm($confirmContents)
        ) {
            $this->info('stop the create');
            return false;
        }
        if (
            ($this->hasOption('force') && $this->option('force')) &&
            !$this->confirm('本当によろしいですか？')
        ) {
            $this->info('stop the create');
            return false;
        }



        foreach ($apiPaths as $uri => $apiEndPath) {
            $controllers = [];
            $requestDefinition = [];

            foreach ($apiEndPath as $method => $apiEnd) {

                $tag = end($apiEnd['tags']);
                if ($this->option('tag') && $tag != $this->option("tag")) {
                    continue;
                }
                $controllers[$tag][$method]['security'] = $apiEnd['security'];
                $controllers[$tag][$method]['description'] = $apiEnd['description'];

                if (array_key_exists("parameters", $apiEnd)) {
                    $request = end($apiEnd['parameters']);
                    $requestName = $request['name'];
                    if (array_key_exists("schema", $request)) {
                        $requestDefinition = $this->getDefinitionName($request['schema']['$ref']);
                        $controllers[$tag][$method]['request'] = $this->makeDefinition($requestName, $tag, 'request', $requestDefinition);
                        $this->makeDefinition($requestDefinition, $tag, 'requestDefinition');
                    } else {
                        $controllers[$tag][$method]['request'] = $this->makeDefinition($requestName, $tag, 'request',);
                    }
                }
                $resultResource = $this->getDefinitionName($apiEnd['responses']['200']["schema"]['$ref']);
                $controllers[$tag][$method]['resource'] = $this->makeDefinition(
                    $resultResource,
                    $tag,
                    'resultResource'
                );

                if (array_key_exists("result", $this->swaggerArr['definitions'][$resultResource]["properties"])) {
                    $resultDefinition = $this->getDefinitionName($this->swaggerArr['definitions'][$resultResource]["properties"]["result"]['$ref']);
                    $this->makeDefinition($resultDefinition, $tag, 'resultDefinition');
                }
            }
            if (!empty($controllers) && $this->hasOption('withC') && $this->option('withC')) {
                $this->makeControllers($uri, $controllers);
            }
        }
    }

    /**
     * cliとapiのIF定義生成
     * @param $name
     * @param $tag
     * @param $resourceType
     * @return string
     */
    public function makeDefinition($name, $tag, $resourceType, $constructName = null)
    {
        if (strstr($name, 'Abstract')) {
            echo 'Abstractと命名されたものは基底クラスとみなしここでは作成しません';
            return '';
        }
        $className = $this->qualifyClassCustom($name, $resourceType, $tag);
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        $children = null;

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClassOne($className, $name, $resourceType, $tag, $children, $constructName));
        $this->info($this->type . ' created successfully.');
        return $className;
    }



    /**
     * cliとapiのIF定義生成
     * @param $uri
     * @param $controllers
     * @return string
     */
    public function makeControllers($uri, $controllers)
    {
        foreach ($controllers as $tag => $controller) {
            $className = $this->makeController($uri, $controller, $tag);
            $this->addApiRoute($uri, $className, $controller);
        }
    }

    /**
     * @param $uri
     * @param $className
     * @param $controller
     * @return void
     * @throws FileNotFoundException
     */
    public function addApiRoute($uri, $className, $controller)
    {
        $this->replaceUseAddPoint($className);
        foreach ($controller as $method => $param) {
            $this->replaceRouteAddPoint($uri, $method, $className, $param);
        }
    }

    /**
     * @param $className
     * @return false|void
     * @throws FileNotFoundException
     */
    public function replaceUseAddPoint($className)
    {
        $apiRoute = $this->files->get($this->getRoutesPath('api'));


        $existsPattern = preg_quote($className, '/');
        $existsPattern = "/^.*$existsPattern.*\$/m";
        if (preg_match_all($existsPattern, $apiRoute, $matches)) {
            $this->info('すでにuse宣言されています');
            return false;
        }

        $searchStr = 'artisanUseAddPoint';
        $pattern = preg_quote($searchStr, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (strpos($className, '{id}') !== false) {
            $className = str_replace('{id}', 'id', $className);
        }
        file_put_contents($this->getRoutesPath('api'), preg_replace(
            $pattern,
            'use ' . $className . ";\n" . "\n" . '//' . $searchStr,
            $apiRoute
        ));
    }

    /**
     * @param $className
     * @return false|void
     * @throws FileNotFoundException
     */
    public function replaceRouteAddPoint($uri, $method, $className, $param)
    {
        $apiRoute = $this->files->get($this->getRoutesPath('api'));

        $existsPattern = preg_quote("Route::" . $method . "('" . $uri . "'", '/');
        $existsPattern = "/^.*$existsPattern.*\$/m";
        if (preg_match_all($existsPattern, $apiRoute, $matches)) {
            $this->info('すでに同一名、同一methodでroutes宣言されています');
            return false;
        }

        $searchStr = 'artisanRouteAddPoint';
        $pattern = preg_quote($searchStr, '/');
        $pattern = "/^.*$pattern.*\$/m";
        $class = str_replace($this->getNamespace($className) . '\\', '', $className);
        if (strpos($class, '{id}') !== false) {
            $class = str_replace('{id}', 'id', $class);
        }
        $route = "Route::" . $method . "('" . $uri . "', [" . $class . "::class,'" . self::convMethodStr($method) . "']);";
        $security = array_key_last($param['security']);



        if ($security) {
            $func = "Route::group(['middleware' => '" . $security . "'], function () {\n";
            $func .= "    " . $route . "\n";
            $func .= "});";
        } else {
            $func = $route;
        }
        file_put_contents($this->getRoutesPath('api'), preg_replace(
            $pattern,
            $func . "\n" . "\n" . '//' . $searchStr,
            $apiRoute
        ));
    }


    public function makeController($uri, $controller, $tag)
    {

        $name = self::pascalize(str_replace('/', '', $uri));

        $className = $this->qualifyClassCustom($name, 'controller', $tag);

        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        if (strpos($className, '{id}') !== false) {
            $className = str_replace('{id}', 'id', $className);
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildControllerClass($className, $controller));
        $this->info($className . ' created successfully.');
        return $className;
    }

    /**
     * 関連づく子要素オブジェクト定義生成
     * @param array $children
     * @param $name
     * @param $resourceType
     * @param $tag
     * @return false|void
     */
    public function childGen(array $children, $name, $resourceType, $tag)
    {
        $className = $this->qualifyClassCustom($name, $resourceType, $tag);
        //$name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return false;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClassOne($className, $name, $resourceType, $tag, $children));
        $this->info($this->type . ' created children successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClassOne(string $name, $planeName, $resourceType, $tag, array $children = null, $constructName = null): string
    {
        $replaceProperties = [];
        $replaceChildProperty = [];
        $getters = [];
        $setters = [];
        $dependencyDefinition = '';
        $stub = $this->files->get($this->getStubCustom($resourceType));

        if ($resourceType == 'request') {
            $dependencyDefinition = str_replace('Request', 'Definition', $planeName);
            $this->replaceRequest($stub, $dependencyDefinition, $tag, $constructName);
            return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
        }

        $swaggerArray = $children;
        if (empty($children)) {
            $swaggerArray = $this->swaggerArr['definitions'][$planeName];
            if (!array_key_exists('properties', $swaggerArray)) {
                echo '$swaggerArray';
            }
        }
        foreach ($swaggerArray['properties'] as $propertyName => $property) {
            if (!array_key_exists('description', $property)) {
                if ($property['$ref']) {
                    $dependencyDefinition = $this->getDefinitionName($property['$ref']);
                }
                continue;
            }
            $propertyType = $property['type'];
            if ($property['type'] == 'object' || ($property['type'] == 'array' && $property['items']['type'] == 'object')) {
                $propertyType = $planeName . self::pascalize($propertyName);
                if ($resourceType == 'requestDefinition') {
                    $propertyType = $property['type'] == 'array' ? 'collectionObject' : 'object';
                    $replaceChildProperty[] = "'{$propertyName}' => new " . $planeName . self::pascalize($propertyName) . "(),";
                }
            }
            if ($resourceType == 'requestDefinition') {
                $replaceProperties[] = $this->getValidatePropertyStr($propertyName, $propertyType, $property['description'], $swaggerArray['required']);
            } else {
                $replaceProperties[] = $this->getPropertyStr($propertyName, $propertyType, $property['description']);
            }

            $getters[] = $this->getGetterMethodStr($propertyName);
            if ($property['type'] == 'object') {
                $childName = $planeName . self::pascalize($propertyName);
                $setters[] = $this->getSetterMethodStr($propertyName, self::pascalize($childName));
                if (array_key_exists('properties', $property) && $property['properties'] != '{}') {
                    $this->childGen($property, $childName, $resourceType, $tag);
                }
            } elseif ($property['type'] == 'array') {
                $childName = $planeName . self::pascalize($propertyName);
                $setters[] = $this->getArrayObjetSetterMethodStr($propertyName, $childName, $property['type']);
                $this->childGen($property['items'], $childName, $resourceType, $tag);
            } else {
                $setters[] = $this->getSetterMethodStr($propertyName, $property['type']);
            }
        }

        $this->replaceProperties($stub, implode("\n", $replaceProperties));
        $this->replaceChildDefinitions($stub, implode("\n", $replaceChildProperty));
        $this->replaceGetters($stub, implode("\n", $getters));
        $this->replaceSetters($stub, implode("\n", $setters));

        if ($resourceType == 'resultResource') {
            $this->replaceDefinitions($stub, $dependencyDefinition, $tag);
        }
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * setters
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceDefinitions(string &$stub, string $name, $tag)
    {
        $stub = str_replace(
            ['DummyDefinitionName'],
            [$tag . "\\" . $name],
            $stub
        );
        $stub = str_replace(
            ['DummyDefinitionClass'],
            [$name],
            $stub
        );
    }

    protected function replaceRequest(string &$stub, string $name, $tag, $constructName)
    {
        $stub = str_replace(
            ['DummyDefinitionName'],
            [$tag . "\\" . $constructName],
            $stub
        );

        $stub = str_replace(
            ['DummyDefinitionClass'],
            [$constructName],
            $stub
        );
    }

    /**
     * プロパティ記載
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceProperties(&$stub, $names)
    {
        $stub = str_replace(
            ['properties'],
            [$names],
            $stub
        );
    }

    /**
     * プロパティ記載
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceChildDefinitions(&$stub, $names)
    {
        $stub = str_replace(
            ['childDefinitions'],
            [$names],
            $stub
        );
    }

    /**
     * getters
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceGetters(&$stub, $names)
    {
        $stub = str_replace(
            ['getters'],
            [$names],
            $stub
        );
    }

    /**
     * setters
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceSetters(&$stub, $names)
    {
        $stub = str_replace(
            ['setters'],
            [$names],
            $stub
        );
    }

    protected function getPropertyStr($name, $type, $description): string
    {
        if ($type == 'integer') {
            $type = 'int';
        }
        $camel =  self::camelize($name);
        return "
    //{$description}
    protected {$type} \${$camel};
       ";
    }

    protected function getValidatePropertyStr($name, $type, $description, $required): string
    {
        $camel = self::camelize($name);
        $validate = [];
        if (in_array($camel, $required)) {
            $validate[] = 'required';
        }
        if ($type == 'string' || $type == 'integer') {
            $validate[] = $type;
        }
        $validate_str = implode('|', $validate);
        return "
    //{$description}
    protected string \${$camel} = '{$validate_str}';
       ";
    }

    protected function getGetterMethodStr($name): string
    {
        $pascal = self::pascalize($name);
        $camel = self::camelize($name);
        return "
    /**
     * @return mixed
     */
    public function get{$pascal}()
    {
        return \$this->{$camel};
    }
    ";
    }

    protected function getSetterMethodStr($name, $type, $is_optional = false): string
    {
        $camel = self::camelize($name);
        $type = $type === 'integer' ? 'int' : $type;
        $type = $type === 'number' ? 'float' : $type;
        $type = $type === 'boolean' ? 'bool' : $type;
        $conv = '';
        if ($type == 'int' || $type == 'float' || $type == 'string' || $type == 'bool') {
            $conv = "({$type})";
        }
        $pascal = self::pascalize($name);
        return "
    /**
     * @param mixed {$camel}
     */
    public function set{$pascal}({$type} \${$camel}): void
    {
        \$this->{$camel} = {$conv} \${$camel};
    }
    ";
    }

    /**
     * array_object =  [Object];
     * のような場合に対応
     *
     * @param $name
     * @param $type
     * @param bool $is_optional
     * @return string
     */
    protected function getArrayObjetSetterMethodStr($name, $childObj, $type, $is_optional = false): string
    {
        $pascal = self::pascalize($name);
        $camel = self::camelize($name);
        return "
    /**
     * @param mixed {$camel}
     */
    public function add{$pascal}({$childObj} \${$camel}): void
    {
        \$this->{$camel}[] = \${$camel};
    }

    /**
     * @param mixed {$camel}
     */
    public function set{$pascal}(array \${$camel}): void
    {
        foreach(\${$camel} as \$unit){
           \$this->add{$pascal}(\$unit);
        }
    }
    ";
    }

    /**
     * array_object =  [Object];
     * のような場合に対応
     *
     * @param $name
     * @param $type
     * @param bool $is_optional
     * @return string
     */
    protected function getHttpControllerMethodStr($method, $param): string
    {

        $request = array_key_exists('request', $param) ? str_replace($this->getNamespace($param['request']) . '\\', '', $param['request']) : null;
        $response = str_replace($this->getNamespace($param['resource']) . '\\', '', $param['resource']);
        $methodStr =  self::convMethodStr($method);
        return "
    /**
     * {$param['description']}
     * @param mixed
     */
    public function {$methodStr}({$request} \$request): {$response}
    {
        return {$response}::buildResult(\$response);
    }
    ";
    }

    public static function convMethodStr($method)
    {
        $methods = array(
            'get' => 'index',
            'post' => 'store',
            'put' => 'update',
            'delete' => 'destroy',
        );
        return $methods[$method];
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildControllerClass(string $name, $controller): string
    {
        $useInterfaces = [];
        $getMethodStrs = [];

        $stub = $this->files->get($this->getStubCustom('controller'));

        foreach ($controller as $method => $param) {
            if (array_key_exists('request', $controller)) {
                $useInterfaces[] = 'use ' . $param['request'];
            }

            $useInterfaces[] = 'use ' . $param['resource'];
            $getMethodStrs[] = $this->getHttpControllerMethodStr($method, $param);
        }

        $this->replaceUseInterfaces($stub, implode(";\n", array_unique($useInterfaces)));
        $this->replaceHttpMethods($stub, implode("\n", $getMethodStrs));
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * スネークからパスカルへ置換します。
     * @param string $str
     * @return string
     */
    public static function pascalize($str): string
    {
        return ucfirst(strtr(ucwords(strtr($str, array('_' => ' '))), array(' ' => '')));
    }

    /**
     * スネークからキャメルへ置換します。
     * @param string $str
     * @return string
     */
    public static function camelize($str): string
    {
        return lcfirst(strtr(ucwords(strtr($str, array('_' => ' '))), array(' ' => '')));
    }

    /**
     * IF記載
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceUseInterfaces(&$stub, $names)
    {
        $stub = str_replace(
            ['useInterfaces'],
            [$names],
            $stub
        );
    }

    /**
     * IF記載
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceHttpMethods(&$stub, $names)
    {
        $stub = str_replace(
            ['httpMethods'],
            [$names],
            $stub
        );
    }
}
