<?php

namespace App\Services;

use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;

/**
 * Interface MoneyFormatterServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface MoneyFormatterServiceInterface
{
    /**
     * Format money from int to brl string
     *
     * @param int $brlMoney
     * @return string
     */
    public function format(int $brlMoney): string;
}
