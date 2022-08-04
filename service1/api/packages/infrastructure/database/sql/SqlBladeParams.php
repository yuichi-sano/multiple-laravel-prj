<?php

namespace packages\infrastructure\database\sql;

use Ramsey\Collection\Collection;

class SqlBladeParams
{
    private ?object $criteria;
    private ?Collection $sortOrders;
    private $statementParam;

    public function __construct(?object $criteria = null, ?Collection $sortOrders = null, $statementParam = null)
    {
        $this->criteria = $criteria;
        $this->sortOrders = $sortOrders;
        $this->statementParam = $statementParam;
    }

    public function toFormat()
    {
        return [
            'criteria' => $this->criteria,
            'sortOrders' => $this->sortOrders,
            'statementParam' => $this->statementParam,
        ];
    }
}
