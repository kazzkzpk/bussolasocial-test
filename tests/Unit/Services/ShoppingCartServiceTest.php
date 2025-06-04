<?php

namespace Tests\Unit\Services;

use App\Exceptions\InvalidShoppingCartItemCatalogValueException;
use App\Exceptions\InvalidShoppingCartItemMissingCatalogException;
use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CatalogService;
use App\Services\CatalogServiceInterface;
use App\Services\ShoppingCartService;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShoppingCartServiceTest extends TestCase
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

    public function testShoppingCartAddGetItem(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $this->assertEquals($item, $shoppingCart->getItems()[0]['item']);
        $this->assertEquals($count, $shoppingCart->getItems()[0]['count']);
    }

    public function testShoppingCartValue(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $this->assertEquals($item->getValue() * $count, $shoppingCart->getValue());
    }

    public function testShoppingValidate(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $validate = $shoppingCart->validate($this->catalogService);

        $this->assertNull($validate);
    }

    public function testShoppingValidateInvalidItemCatalogValue(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $item->setValue($this->faker->numberBetween(1000, 9999));

        $this->expectException(InvalidShoppingCartItemCatalogValueException::class);

        $validate = $shoppingCart->validate($this->catalogService);

        $this->assertNull($validate);
    }

    public function testShoppingValidateInvalidItemCatalogRemoved(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $this->catalogService->removeItem($item->getId());

        $this->expectException(InvalidShoppingCartItemMissingCatalogException::class);

        $validate = $shoppingCart->validate($this->catalogService);

        $this->assertNull($validate);
    }
}
