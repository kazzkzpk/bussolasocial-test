<?php

namespace App\Providers;

use App\Services\MoneyFormatterServiceFactory;
use App\Services\MoneyFormatterServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class MoneyFormatterServiceProvider
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class MoneyFormatterServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            MoneyFormatterServiceInterface::class,
            function () {
                return (new MoneyFormatterServiceFactory())();
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
