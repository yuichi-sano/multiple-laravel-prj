<?php

declare(strict_types=1);

namespace packages\domain\basic\page;

class Offset
{
    private Page $page;
    private PerPage $perPage;

    public function __construct(Page $page, PerPage $perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function getValue(): int
    {
        if ($this->page->isEmpty() && $this->perPage->isEmpty()) {
            return 0;
        }
        return ($this->page->getValue() - 1) * $this->perPage->getValue();
    }
}
