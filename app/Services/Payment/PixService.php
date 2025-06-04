<?php

namespace App\Services\Payment;

use App\Services\PaymentService;
use App\Services\PaymentServiceInterface;

/**
 * Class PixService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class PixService extends PaymentService implements PaymentServiceInterface
{
    private const float DISCOUNT_FACTOR = 0.1; // 10%

    /**
     * @inheritDoc
     */
    public function getValue() : int
    {
        $value = $this->shoppingCartService->getValue();
        $value -= $this->doDiscount($value);
        return $value;
    }

    /**
     * Get discount
     * @return int
     */
    public function getDiscount(): int
    {
        $value = $this->shoppingCartService->getValue();
        return $this->doDiscount($value);
    }

    /**
     * Execute discount factor
     *
     * @param int $value
     * @return int
     */
    private function doDiscount(int $value) : int
    {
        return (int)($value * self::DISCOUNT_FACTOR);
    }
}
