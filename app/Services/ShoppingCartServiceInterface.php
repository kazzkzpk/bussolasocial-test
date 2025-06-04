<?php

namespace App\Services;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;

/**
 * Interface ShoppingCartServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface ShoppingCartServiceInterface
{
    /**
     * Add item to shopping cart
     *
     * @param ItemServiceInterface $itemService
     * @return void
     */
    public function addItem(ItemServiceInterface $itemService): void;

    /**
     * Get all shopping cart items
     *
     * @return Collection<Collection<ItemService, int>>
     */
    public function getItems(): Collection;

    /**
     * Get shopping cart total items value
     *
     * @return int
     */
    public function getValue(): int;

    /**
     * Validate Shopping Cart
     *
     * @return void
     */
    public function validate(CatalogServiceInterface $catalogService): void;
}
