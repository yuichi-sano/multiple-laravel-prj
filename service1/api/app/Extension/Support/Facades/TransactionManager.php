<?php

namespace App\Extension\Support\Facades;

use Illuminate\Support\Facades\Facade;
use packages\service\helper\TransactionManagerInterface;

/**
 * @method static TransactionManagerInterface startTransaction()
 * @method static TransactionManagerInterface commit()
 * @method static TransactionManagerInterface getNestingLevel()
 * @method static TransactionManagerInterface rollback()
 * @method static TransactionManagerInterface getConnection()
 * @method static TransactionManagerInterface reConnect()
 */
class TransactionManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TransactionManagerInterface::class;
    }
}
