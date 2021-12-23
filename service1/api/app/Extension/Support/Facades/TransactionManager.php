<?php

namespace App\Extension\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \packages\service\helper\TransactionManagerInterface startTransaction()
 * @method static \packages\service\helper\TransactionManagerInterface commit()
 * @method static \packages\service\helper\TransactionManagerInterface getNestingLevel()
 * @method static \packages\service\helper\TransactionManagerInterface rollback()
 * @method static \packages\service\helper\TransactionManagerInterface getConnection()
 */
class TransactionManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \packages\service\helper\TransactionManagerInterface::class;
    }
}
