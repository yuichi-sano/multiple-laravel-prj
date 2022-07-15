<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use DateTime;
use packages\domain\basic\type\DateTimeType;

class ZipCodeAuditDate implements DateTimeType
{
    private ?DateTime $value;

    public function __construct(DateTime $value = null)
    {
        $this->value = $value;
    }

    public static function create(string $value = null): self
    {
        if ($value) {
            return new self(DateTime::createFromFormat('Y-m-d H:i:s', $value));
        }
        return new self();
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function toLocalDateTime(): DateTime
    {
        return $this->value;
    }

    public function getValue(): ?DateTime
    {
        return $this->value;
    }

    public function format(): ?string
    {
        if ($this->isEmpty()) {
            return null;
        }
        return $this->value->format('Y-m-d H:i:s');
    }
}
