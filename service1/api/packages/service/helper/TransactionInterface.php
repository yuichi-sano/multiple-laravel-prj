<?php
namespace packages\service\helper;
interface TransactionInterface
{
    /**
     */
    public function startTransaction(string $name): void;
    public function commit(string $name): void;
    public function rollback(string $name): void;
    public function getNestedPoint(): string;
}
