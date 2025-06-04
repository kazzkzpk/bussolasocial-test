<?php

namespace App\Services;

use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;

/**
 * Interface CatalogServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface CatalogServiceInterface
{
    /**
     * Add item to catalog
     *
     * @param ItemServiceInterface $itemService
     * @return void
     */
    public function addItem(ItemServiceInterface $itemService): void;

    /**
     * Get item from catalog by id
     *
     * @param int $id
     * @return ItemServiceInterface|null
     */
    public function getItem(int $id) : ?ItemServiceInterface;

    /**
     * Remove item from catalog by id
     *
     * @param int $id
     * @return void
     */
    public function removeItem(int $id) : void;

    /**
     * Get all items from catalog
     *
     * @return Collection
     */
    public function getItems(): Collection;
}
