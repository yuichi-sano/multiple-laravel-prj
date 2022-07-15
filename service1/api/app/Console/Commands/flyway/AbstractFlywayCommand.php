<?php

namespace App\Console\Commands\Flyway;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

abstract class AbstractFlywayCommand extends Command
{
    /**
     * Create a new migration command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected string $gradleCmd = './gradlew -i clean ';
    protected string $processResources = 'processResources';
    protected bool $isTesting = false;
    /**
     * 入力コマンドをgradlew+flywayで使用可能なコマンドに変換します。
     * @var array|string[]
     */
    protected array $flywayCmd = [
        'base' => 'flywayBaseline',
        'clean' => 'flywayClean',
        'info' => 'flywayInfo',
        'migrate' => 'flywayMigrate',
        'repair' => 'flywayRepair',
        'valid' => 'flywayValidate'
    ];

    /**
     * flyway用コマンドに変換します。
     * @param $action
     * @return string
     */
    protected function transferFlyWayCmd($action): string
    {
        return $this->flywayCmd[$action];
    }

    /**
     * DBコネクションを設定します。
     * testを指定するとユニットテスト用データベースを参照します
     * @return string
     */
    protected function transferConnection(): string
    {
        $host = $this->getDbConfig('host');
        $port = $this->getDbConfig('port');
        $dbname = $this->getDbConfig('database');
        return "-Dflyway.url=jdbc:postgresql://" . $host . ":" . $port . "/" . $dbname;
    }

    /**
     * locationから操作対象を決定します。
     * @param string $location
     * @return string
     */
    protected function transferLocation(string $location = 'local'): string
    {
        $trans = '-Dflyway.locations=classpath:db/migration';
        if ($location == 'local') {
            $trans .= ',classpath:develop,classpath:local';
        } elseif ($location == 'stg') {
            $trans .= ',classpath:develop';
        } elseif ($location == 'testing') {
            $trans .= ',classpath:develop,classpath:local,classpath:testing';
        }
        return $trans;
    }

    /**
     * 適用versionの指定
     * @param string|null $version
     * @return string
     */
    protected function transferVersion(string $version): string
    {
        return $trans = '-Dflyway.target=' . $version;
    }

    /**
     *configからユーザー情報を抜き出しコマンドを生成
     * @return string
     */
    protected function getDbUser(): string
    {
        $user = $this->getDbConfig('username');
        return '-Dflyway.user=' . $user;
    }

    /**
     * configからパスワードを抜き出しコマンドを生成
     * @return string
     */
    protected function getDbPass(): string
    {
        $password = $this->getDbConfig('password');
        return '-Dflyway.password=' . $password;
    }

    /**
     * 実行dirまで移動
     */
    protected function cdFlyDir(): void
    {
        chdir('./database/flyway/');
    }

    /**
     * コマンドビルダー
     * @param string $action
     * @param string $location
     * @param bool $test
     * @return string
     */
    protected function flywayCmdBuild(
        string $action,
        string $location,
        string $version = null
    ): string {
        $commands = [$this->gradleCmd];
        if ($action != 'clean') {
            $commands[] = $this->processResources;
        }
        $commands[] = $this->transferFlyWayCmd($action);
        if ($action == 'clean') {
            $commands[] = $this->processResources;
        }
        if ($action != 'clean') {
            $commands[] = $this->transferLocation($location);
        }
        if ($version) {
            $commands[] = $this->transferVersion($version);
        }
        $commands[] = $this->transferConnection();
        $commands[] = $this->getDbUser();
        $commands[] = $this->getDbPass();
        return implode(' ', $commands);
    }

    /**
     * DBコンフィグを走査
     * @throws Exception
     */
    private function getDbConfig(string $name): string
    {
        $defaultDb = config('database.default');
        $connections = config('database.connections');
        $connection = $this->isTesting ? config('database.connections.testing') : $connections[$defaultDb];
        if (array_key_exists($name, $connection)) {
            return $connection[$name];
        } else {
            throw new Exception('指定されたコンフィグ値は存在しませんでした');
        }
    }
}
