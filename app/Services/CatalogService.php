<?php

namespace App\Services;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;

/**
 * Class CatalogService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class CatalogService implements CatalogServiceInterface
{
    /** @var Collection<Collection<ItemService, int>> */
    private Collection $items;

    public function __construct() {
        $this->items = collect();
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(ItemServiceInterface $itemService): void
    {
        $itemService->validate();
        $this->items->put($itemService->getId(), $itemService);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id) : ?ItemServiceInterface
    {
        return $this->items->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(int $id) : void
    {
        $this->items->forget($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
