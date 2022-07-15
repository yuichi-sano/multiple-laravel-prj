<?php

declare(strict_types=1);

namespace packages\domain\basic\page;

class Pageable
{
    private Page $page;
    private PerPage $perPage;

    public function __construct(Page $page, PerPage $perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function isSpecified(): bool
    {
        return !$this->page->isEmpty() && !$this->perPage->isEmpty();
    }

    public function cursol(): int
    {
        return $this->offset()->getValue() + $this->perPage->getValue();
    }

    private function offset(): Offset
    {
        return new Offset($this->page, $this->perPage);
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getPerPage(): PerPage
    {
        return $this->perPage;
    }

    public function getOffset(): Offset
    {
        return $this->offset();
    }
}
