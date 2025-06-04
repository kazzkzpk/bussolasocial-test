<?php

namespace App\Services;

use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;

/**
 * Interface PaymentServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface PaymentServiceInterface
{
    /**
     * Get final payment value with discounts
     * @return int
     */
    public function getValue(): int;

    /**
     * Validate payment
     *
     * @return void
     */
    public function validate(): void;
}
