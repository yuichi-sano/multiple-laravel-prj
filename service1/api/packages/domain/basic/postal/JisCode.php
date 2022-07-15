<?php

declare(strict_types=1);

namespace packages\domain\basic\postal;

use packages\domain\basic\type\StringType;

class JisCode implements StringType
{
    protected string $value;
    protected ?TownCode $townCode = null;
    protected ?PrefCode $prefCode = null;

    public function __construct(string $value = null)
    {
        $this->prefCode = $this->toPrefCode($value);
        $this->townCode = $this->toTownCode($value);
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        if (!$this->value) {
            return true;
        }
        return false;
    }

    public function toString(): string
    {
        if ($this->isEmpty()) {
            return "";
        }
        return $this->value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * JISコードから都道府県コードを抜き出し返却
     * @param string $jis
     * @return int
     */
    protected function toPrefCode(string $jis): PrefCode
    {
        return new PrefCode(substr($jis, 0, 2));
    }

    /**
     * JISコードから都道府県コードを抜き出し返却
     * @param string $jis
     * @return int
     */
    protected function toTownCode(string $jis): TownCode
    {
        return new TownCode(substr($jis, 2));
    }

    /**
     * @return PrefCode
     */
    public function getPrefCode(): PrefCode
    {
        if (!$this->prefCode) {
            $this->prefCode = $this->toPrefCode($this->value);
        }
        return $this->prefCode;
    }

    /**
     * @return TownCode
     */
    public function getTownCode(): TownCode
    {
        if (!$this->townCode) {
            $this->townCode = $this->toTownCode($this->value);
        }
        return $this->townCode;
    }
}
