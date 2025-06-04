<?php

namespace Tests\Unit\Services;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CatalogService;
use App\Services\CatalogServiceInterface;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogServiceTest extends TestCase
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

    public function testCatalogAddGetItem(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);
        $this->assertEquals($item, $this->catalogService->getItem($item->getId()));
    }

    public function testCatalogRemoveItem(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);
        $this->catalogService->removeItem($item->getId());
        $this->assertNull($this->catalogService->getItem($item->getId()));
    }

    public function testCatalogGetItems(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);
        $items = $this->catalogService->getItems();
        $this->assertEquals($item, $items[$item->getId()]);
    }
}
