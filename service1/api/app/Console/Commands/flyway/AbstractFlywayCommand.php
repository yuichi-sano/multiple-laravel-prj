<?php

namespace App\Console\Commands\Flyway;

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

    protected string $gradleCmd  = './gradlew -i clean ';
    protected string $processResources = 'processResources';
    /**
     * 入力コマンドをgradlew+flywayで使用可能なコマンドに変換します。
     * @var array|string[]
     */
    protected array $flywayCmd = [
        'base'   => 'flywayBaseline',
        'clean'  => 'flywayClean',
        'info'   => 'flywayInfo',
        'migrate'=> 'flywayMigrate',
        'repair' => 'flywayRepair',
        'valid'  => 'flywayValidate'
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
     * @param bool $test
     * @return string
     */
    protected function transferConnection(bool $test): string
    {
        $databaseName = $test ? env('DB_DATABASE_TEST') : env('DB_DATABASE');
        return "-Dflyway.url=jdbc:postgresql://". env('DB_HOST').":".env('DB_PORT')."/".$databaseName;
    }

    /**
     * locationから操作対象を決定します。
     * @param string $location
     * @return string
     */
    protected function transferLocation(string $location = 'local'): string
    {
        $trans='-Dflyway.locations=classpath:db/migration';
        if($location == 'local'){
            $trans .= ',classpath:develop,classpath:local';
        }elseif($location == 'stg'){
            $trans .= ',classpath:develop';
        }
        return $trans;
    }

    /**
     * envからユーザー情報を抜き出しコマンドを生成
     * @return string
     */
    protected function  getDbUser(): string
    {
        $USER = env('DB_USERNAME');
        return '-Dflyway.user='.$USER;
    }

    /**
     * envからパスワードを抜き出しコマンドを生成
     * @return string
     */
    protected function  getDbPass(): string
    {
        $USER = env('DB_PASSWORD');
        return '-Dflyway.password='.$USER;
    }

    /**
     * 実行dirまで移動
     */
    protected function cdFlyDir(): void{
        chdir( './database/flyway/' );
    }

    /**
     * コマンドビルダー
     * @param string $action
     * @param string $location
     * @param bool $test
     * @return string
     */
    protected function flywayCmdBuild(string $action, string $location, bool $test): string
    {
        $commands = [$this->gradleCmd];
        if($action != 'clean'){
            $commands[] = $this->processResources;
        }
        $commands[] = $this->transferFlyWayCmd($action);
        if($action  == 'clean'){
            $commands[] = $this->processResources;
        }
        if($action != 'clean') {
            $commands[] = $this->transferLocation($location);
        }
        $commands[]=$this->transferConnection($test);
        $commands[]=$this->getDbUser();
        $commands[]=$this->getDbPass();
        return  implode(' ',$commands);
    }


}
