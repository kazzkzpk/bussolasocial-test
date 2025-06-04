<?php

namespace App\Services;

/**
 * Class CatalogServiceFactory
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class CatalogServiceFactory
{
    public function __invoke(): CatalogService
    {
        return new CatalogService();
    }
}
