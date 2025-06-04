<?php

namespace App\Services;

use App\Exceptions\EmptyShoppingCartException;

/**
 * Class CatalogService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
abstract class PaymentService implements PaymentServiceInterface
{
    protected ShoppingCartServiceInterface $shoppingCartService;

    public function __construct(ShoppingCartServiceInterface &$shoppingCartService)
    {
        $this->shoppingCartService = $shoppingCartService;
    }

    /**
     * @inheritDoc
     */
    public abstract function getValue() : int;

    /**
     * @inheritDoc
     * @throws EmptyShoppingCartException
     */
    public function validate(): void
    {
        if ($this->shoppingCartService->getValue() === 0) {
            throw new EmptyShoppingCartException('Shopping cart is empty.');
        }
    }
}
