<?php

namespace App\Services;

use App\Exceptions\InvalidShoppingCartItemCatalogValueException;
use App\Exceptions\InvalidShoppingCartItemMissingCatalogException;
use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class ShoppingCartService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class ShoppingCartService implements ShoppingCartServiceInterface
{
    /** @var Collection<Collection<ItemService, int>> */
    private Collection $items;

    public function __construct()
    {
        $this->items = collect();
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(ItemServiceInterface $itemService, int $count = 1): void
    {
        $itemService->validate();

        if ($count < 1) {
            throw new InvalidArgumentException('Count must be greater than 0.');
        }

        $this->items->add(collect([
            'item' => clone $itemService,
            'count' => $count
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): int
    {
        $items = $this->getItems();

        $itemsValues = $items->map(function ($item) {
            return ($item['item']->getValue() * $item['count']);
        });

        return $itemsValues->sum();
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidShoppingCartItemMissingCatalogException
     * @throws InvalidShoppingCartItemCatalogValueException
     */
    public function validate(CatalogServiceInterface $catalogService): void
    {
        foreach ($this->items as $cartItem) {
            /** @var ItemServiceInterface $item */
            $item = $cartItem['item'];

            $catalogItem = $catalogService->getItem($item->getId());
            if (!$catalogItem) {
                throw new InvalidShoppingCartItemMissingCatalogException('Item does not exists anymore in catalog.');
            }

            if ($item->getValue() !== $catalogItem->getValue()) {
                throw new InvalidShoppingCartItemCatalogValueException('Item value does not match catalog value.');
            }
        }
    }
}
