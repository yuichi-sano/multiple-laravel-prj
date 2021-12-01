<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class SwaggerCodeGenCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:swagger-codegen {name} {--resource}{--force}';
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

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('resource') ? __DIR__.'/stubs/resource.stub' : __DIR__.'/stubs/definition.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Console\temp';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        //TODO ↓正規表現に変更、及びファイル存在チェック実装
        $resource = file_get_contents( __DIR__.'/../../../resources/swagger/api.json');
        $json = mb_convert_encoding($resource, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        $definitions = $arr['definitions'];

        foreach($definitions as $key => $definition){
            $name = $this->qualifyClass($key);
            //$name = $this->qualifyClass($this->getNameInput());
            $path = $this->getPath($name);
            if ((! $this->hasOption('force') ||
                    ! $this->option('force')) &&
                //$this->alreadyExists($this->getNameInput())
                $this->alreadyExists($name)
            ) {
                $this->error($this->type.' already exists!');
                return false;
            }

            $this->makeDirectory($path);
            $this->files->put($path, $this->buildClassOne($name,$definition,$key));
            $this->info($this->type.' created successfully.');
        }

    }

    public function childGen(array $children,$parentName){
        $inp_name= $parentName;
        $name = $this->qualifyClass($inp_name);
        //$name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClassOne($name,$children,$parentName));

        $this->info($this->type.' created children successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClassOne($name, array $swaggerArray, $planeName)
    {
        $properties = [];
        $getters = [];
        $setters = [];
        if (!array_key_exists('properties', $swaggerArray)){
            var_dump($swaggerArray);
            exit;
        }
        foreach($swaggerArray['properties'] as $propertyName => $property){
            if(!array_key_exists('description', $property)){
                continue;
            }
            $properties[] = $this->getPropertyStr($propertyName, $property['description']);
            $getters[] = $this->getGetterMethodStr($propertyName);
            if($property['type'] == 'object'){
                $childName = $planeName.self::camelize($propertyName);
                $setters[] = $this->getSetterMethodStr($propertyName, self::camelize($childName));
                if(array_key_exists('properties',$property) && $property['properties'] != '{}'){
                    $this->childGen($property,$childName);
                }
            }elseif($property['type'] == 'array'){
                $childName = $planeName.self::camelize($propertyName);
                $setters[] = $this->getArrayObjetSetterMethodStr($propertyName, $childName, $property['type']);
                $this->childGen($property['items'],$childName);
            }else{
                $setters[] = $this->getSetterMethodStr($propertyName, $property['type']);
            }
        }

        $stub = $this->files->get($this->getStub());
        $this->replaceProperties($stub, implode("\n", $properties));
        $this->replaceGetters($stub, implode("\n", $getters));
        $this->replaceSetters($stub, implode("\n", $setters));
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * プロパティ記載
     * @param  string  $stub
     * @param  string  $name
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
     * getters
     * @param  string  $stub
     * @param  string  $name
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
     * @param  string  $stub
     * @param  string  $name
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

    protected function getPropertyStr($name,$description){
        $camel =  self::camelize($name);
        return "
    //{$description}
    protected \${$name};
       ";
    }
    protected function getGetterMethodStr($name){
        $camel =  self::camelize($name);
        return "
    /**
     * @return mixed
     */
    public function get{$camel}()
    {
        return \$this->{$name};
    }
    ";
    }
    protected function getSetterMethodStr($name, $type, $is_optional = false){

        $type = $type === 'integer' ? 'int' : $type;
        $type = $type === 'number' ? 'float' : $type;
        $type = $type === 'boolean' ? 'bool' : $type;
        $conv = '';
        if($type == 'int' || $type == 'float' || $type == 'string' || $type == 'bool' ){
            $conv = "({$type})";
        }
        $camel =  self::camelize($name);
        return "
    /**
     * @param mixed {$name}
     */
    public function set{$camel}({$type} \${$name}): void
    {
        \$this->{$name} = {$conv} \${$name};
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
    protected function getArrayObjetSetterMethodStr($name, $childObj, $type, $is_optional = false){
        $camel =  self::camelize($name);
        return "
    /**
     * @param mixed {$name}
     */
    public function add{$camel}({$childObj} \${$name}): void
    {
        \$this->{$name}[] = \${$name};
    }

    /**
     * @param mixed {$name}
     */
    public function set{$camel}(array \${$name}): void
    {
        foreach(\${$name} as \$unit){
           \$this->add{$camel}(\$unit);
        }
    }
    ";
    }
    /**
     * スネークからキャメルへ置換します。
     * @param string $str
     * @return string
     */
    public static function camelize($str){
        return ucfirst(strtr(ucwords(strtr($str, array('_' => ' '))), array(' ' => '')));
    }

}
