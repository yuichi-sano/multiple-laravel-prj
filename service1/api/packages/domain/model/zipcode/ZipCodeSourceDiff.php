<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

class ZipCodeSourceDiff
{
    private string $new;
    private string $old;

    public function __construct(
        string $old = null,
        string $new = null
    ) {
        $this->new = $new;
        $this->old = $old;
    }

    /**
     * @return string
     */
    public function getOld(): string
    {
        return $this->old;
    }

    /**
     * @return string
     */
    public function getNew(): string
    {
        return $this->new;
    }

    public array $collectionKeys = [];
}
