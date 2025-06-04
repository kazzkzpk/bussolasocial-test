<?php

namespace Tests\Unit\Services\Payment;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CatalogService;
use App\Services\CatalogServiceInterface;
use App\Services\Payment\PixService;
use App\Services\ShoppingCartService;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PixServiceTest extends TestCase
{
    use WithFaker;

    protected CatalogServiceInterface $catalogService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create('pt_BR');

        $this->catalogService = new CatalogService();
    }

    protected function getRandomItem(): ItemServiceInterface
    {
        $item = new ItemService(
            1,
            $this->faker->text(32),
            $this->faker->numberBetween(10000, 20000),
        );

        return $item;
    }

    public function testPixRequest(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $pixPaymentService = new PixService($shoppingCart);
        $discount = $pixPaymentService->getDiscount();

        $this->assertEquals($shoppingCart->getValue() - $discount, $pixPaymentService->getValue());
    }
}
