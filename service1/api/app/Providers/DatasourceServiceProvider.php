<?php

namespace App\Providers;

use packages\infrastructure\database\doctrine as Doctrine;
use packages\infrastructure\database as Database;
use packages\domain\model as DomainModel;
use Illuminate\Support\ServiceProvider;
use packages\infrastructure\source\external\postalCode\GuzzleYuseiLargeBusinessZipCodeSource;
use packages\infrastructure\source\external\postalCode\GuzzleZipCodeSource;
use packages\infrastructure\source\external\yamatoMaster\GuzzleYamatoMasterSource;
use packages\infrastructure\source\migration\jp\ZipCodeMigrationDataSource;
use packages\infrastructure\source\migration\yamato\YamatoMigrationDataSource;

class DatasourceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerForInMemory();
    }

    private function registerForInMemory()
    {
        $this->app->bind(DomainModel\user\UserRepository::class, function ($app) {
            return new Doctrine\user\DoctrineUserRepository(
                $app['em'],
                $app['em']->getClassMetaData(DomainModel\user\User::class)
            );
        });
        $this->app->bind(
            DomainModel\authentication\authorization\RefreshTokenRepository::class,
            function ($app) {
                return new Doctrine\authentication\authorization\DoctrineRefreshTokenRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(
                        DomainModel\authentication\authorization\AuthenticationRefreshToken::class
                    )
                );
            }
        );
        $this->app->bind(
            DomainModel\merchant\MerchantRepository::class,
            function ($app) {
                return new Doctrine\merchant\DoctrineMerchantRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\merchant\Merchant::class)
                );
            }
        );
        $this->app->bind(
            DomainModel\zipcode\ZipCodeSourceRepository::class,
            GuzzleZipCodeSource::class
        );
        $this->app->bind(
            DomainModel\zipcode\YuseiLargeBusinessZipCodeSourceRepository::class,
            GuzzleYuseiLargeBusinessZipCodeSource::class
        );


        $this->app->bind(
            DomainModel\zipcode\ZipCodeRepository::class,
            function ($app) {
                return new Doctrine\jp\DoctrineZipCodeRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\zipcode\ZipCode::class)
                );
            }
        );
        $this->app->bind(
            DomainModel\zipcode\YuseiYubinBangouRepository::class,
            function ($app) {
                return new Doctrine\jp\DoctrineYuseiYubinBangouRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\zipcode\YuseiYubinBangou::class)
                );
            }
        );

        $this->app->bind(
            DomainModel\zipcode\YuseiLargeBusinessYubinBangouRepository::class,
            function ($app) {
                return new Doctrine\jp\DoctrineYuseiLargeBusinessYubinBangouRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\zipcode\YuseiLargeBusinessYubinBangou::class)
                );
            }
        );

        $this->app->bind(
            DomainModel\zipcode\MergeZipYuseiYubinBangouRepository::class,
            function ($app) {
                return new Doctrine\jp\DoctrineMergeZipYuseiYubinBangouRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\zipcode\MergeZipYuseiYubinBangou::class)
                );
            }
        );


        $this->app->bind(
            DomainModel\zipcode\ZipCodeMigrationSourceRepository::class,
            function ($app) {
                return new ZipCodeMigrationDataSource(
                    $app['files']
                );
            }
        );

        $this->app->bind(
            DomainModel\batch\MigrationBatchAuditRepository::class,
            function ($app) {
                return new Doctrine\batch\DoctrineMigrationBatchAuditRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\batch\MigrationBatchAudit::class)
                );
            }
        );


        $this->app->bind(
            DomainModel\prefecture\PrefectureRepository::class,
            function ($app) {
                return new Doctrine\prefecture\DoctrinePrefectureRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\prefecture\Prefecture::class)
                );
            }
        );

        $this->app->bind(
            DomainModel\facility\device\DeviceRepository::class,
            function ($app) {
                return new Doctrine\facility\device\DoctrineDeviceRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\facility\device\Device::class)
                );
            }
        );

        $this->app->bind(
            DomainModel\facility\workplace\WorkplaceRepository::class,
            function ($app) {
                return new Doctrine\facility\workplace\DoctrineWorkplaceRepository(
                    $app['em'],
                    $app['em']->getClassMetaData(DomainModel\facility\workplace\Workplace::class)
                );
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
