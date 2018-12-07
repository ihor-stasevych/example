<?php

namespace App\AddHash\System\Response;

class ResponseListCollection
{
    private $items;

    private $totalItems;

    private $totalPages;

    private $page;

    private $limit;

    public function __construct(
        array $items,
        int $totalItems,
        int $totalPages,
        int $page,
        int $limit
    )
    {
        $this->items = $items;
        $this->totalItems = $totalItems;
        $this->totalPages = $totalPages;
        $this->page = $page;
        $this->limit = $limit;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}