<?php

namespace packages\infrastructure\database\doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use LaravelDoctrine\ORM\Facades\Registry;
use LaravelDoctrine\ORM\Facades\EntityManager;
use packages\service\helper\TransactionManagerInterface;

/**
 * リポジトリとして一般的と見られるトランザクション管理関数のみをEntityManagerから実施します。
 * @NOTE 悲観ロックなどはこのMangaerは通さず、各repositoryにて実施ください。($this->lock($this->getEntity,)
 * @FIXME 複数DB等の考慮ができていません。
 *
 */
class DoctrineTransactionManager implements TransactionManagerInterface
{

    public static function startTransaction(): void
    {
        EntityManager::beginTransaction();
    }
    public static function commit(): void
    {
        EntityManager::commit();
    }

    public static function getNestingLevel(): string
    {
        return EntityManager::getConnection()->getTransactionNestingLevel();
    }
    public static function rollback(): void
    {
        EntityManager::rollBack();
    }

    public static function wrapInTransaction(callable $func): void{
        EntityManager::wrapInTransaction($func);
    }
    public static function close(): void{
        EntityManager::close();
    }

    ///以下はDoctorine特有のTransaction管理機能
    /**
     * Doctrineコネクションを返却します。
     * @return Connection
     */
    public static function getConnection(): Connection
    {
        return EntityManager::getConnection();
    }

    /**
     * データをDBにflush（書き込み）します。
     * @return void
     */
    public static function flush(){
        EntityManager::flush();
    }

    /**
     * オブジェクトレベルのトランザクションを返却します
     * @return \Doctrine\ORM\UnitOfWork
     */
    public static function getUnitOfWork(): \Doctrine\ORM\UnitOfWork
    {
        return EntityManager::getUnitOfWork();
    }


}
