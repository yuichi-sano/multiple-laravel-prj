<?php

namespace packages\service\helper;

interface TransactionManagerInterface
{
    /**
     * リポジトリとして一般的と見られるトランザクション管理関数を定義します。
     */
    public static function startTransaction(): void;

    public static function commit(): void;

    public static function rollback(): void;

    public static function getNestingLevel(): string;

    public static function close(): void;

    public static function wrapInTransaction(callable $func): void;

    public static function reConnect(): void;
}
