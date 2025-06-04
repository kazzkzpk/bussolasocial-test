<?php

namespace App\Services;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;
use NumberFormatter;

/**
 * Class MoneyFormatterService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class MoneyFormatterService implements MoneyFormatterServiceInterface
{
    private NumberFormatter $numberFormatter;

    public function __construct()
    {
        $this->numberFormatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
    }

    /**
     * {@inheritdoc}
     */
    public function format(int $brlMoney): string
    {
        return $this->numberFormatter->formatCurrency($brlMoney / 100, 'BRL');
    }
}
