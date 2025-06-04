<?php

namespace App\Providers;

use App\Services\CatalogServiceFactory;
use App\Services\CatalogServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class CatalogServiceProvider
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class CatalogServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            CatalogServiceInterface::class,
            function () {
                return (new CatalogServiceFactory())();
            }
        );
    }

    /**
     * @return void
     */
    public function boot()
    {
        //
    }
}
