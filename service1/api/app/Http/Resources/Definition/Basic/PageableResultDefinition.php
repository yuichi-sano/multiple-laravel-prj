<?php

namespace App\Http\Resources\Definition\Basic;

use packages\domain\basic\page\Pageable;


class PageableResultDefinition extends AbstractResultDefinition implements ResultDefinitionInterface
{
    public function __construct(
        Pageable $pageable,
        int $totalCount
    ) {
        $this->resultCount = $totalCount;
        $this->totalPages = $totalCount / $pageable->getPerPage()->getValue();
        $this->currentPage = $pageable->getPage()->getValue();
        $this->previousPage = $pageable->getPage()->previous()->getValue();
        $this->nextPage = $pageable->getPage()->next()->getValue();
    }

    //取得件数
    protected int $resultCount;
    //総ページ数
    protected int $totalPages;
    //前のページ数
    protected int $previousPage;
    //現在のページ数
    protected int $currentPage;
    //次のページ数
    protected int $nextPage;

    /**
     * @return int
     */
    public function getResultCount(): int
    {
        return $this->resultCount;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getPreviousPage(): int
    {
        return $this->previousPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getNextPage(): int
    {
        return $this->nextPage;
    }
}
