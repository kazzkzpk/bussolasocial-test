<?php

namespace App\Services;

/**
 * Class MoneyFormatterServiceFactory
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class MoneyFormatterServiceFactory
{
    public function __invoke(): MoneyFormatterServiceInterface
    {
        return new MoneyFormatterService();
    }
}
