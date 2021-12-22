<?php

namespace packages\infrastructure\database\doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\Doctrine;
use packages\service\helper\TransactionManagerInterface;

class DoctrineTransactionManager implements TransactionManagerInterface
{
    private Connection $connection;
    public function __construct(EntityManager $entityManager){
        $this->connection = $entityManager->getConnection();
    }

    public function startTransaction(string $name): void
    {
        $this->connection->beginTransaction();
    }
    public function commit(string $name): void
    {
        $this->connection->commit();
    }

    public function getNestedPoint(): string
    {
        $this->connection->getTransactionNestingLevel();
    }
    public function rollback(string $name): void
    {
        $this->connection->rollBack();
    }

}
