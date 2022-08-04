<?php

namespace App\Http\Resources\Definition\Yusei;

use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use App\Http\Resources\Definition\Basic\AbstractResultDefinition;


class ZipCodeInfoResultDefinitionZipCodeList extends AbstractResultDefinition implements ResultDefinitionInterface
{
    //
    protected string $old;
    //
    protected string $new;

    /**
     * @param string $new
     */
    public function setNew(string $new): void
    {
        $this->new = $new;
    }

    /**
     * @param string $old
     */
    public function setOld(string $old): void
    {
        $this->old = $old;
    }

    /**
     * @return string
     */
    public function getNew(): string
    {
        return $this->new;
    }

    /**
     * @return string
     */
    public function getOld(): string
    {
        return $this->old;
    }
}
