<?php

namespace Services\Catalog;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use InvalidArgumentException;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create('pt_BR');
    }

    public function testItemId(): void
    {
        $id = 1;
        $item = new ItemService(
            $id,
            $this->faker->text(32),
            $this->faker->numberBetween(10000, 20000)
        );

        $this->assertEquals($id, $item->getId());
    }

    public function testItemName(): void
    {
        $name = $this->faker->text(32);
        $item = new ItemService(
            1,
            $name,
            $this->faker->numberBetween(10000, 20000)
        );

        $this->assertEquals($name, $item->getName());
    }

    public function testItemValue(): void
    {
        $value = $this->faker->numberBetween(10000, 20000);
        $item = new ItemService(
            1,
            $this->faker->text(32),
            $value
        );

        $this->assertEquals($value, $item->getValue());
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

    public function testItemNameRuntime(): void
    {
        $item = $this->getRandomItem();
        $name = $this->faker->text(32);
        $item->setName($name);

        $this->assertEquals($name, $item->getName());
    }

    public function testItemValueRuntime(): void
    {
        $item = $this->getRandomItem();
        $value =  $this->faker->numberBetween(10000, 20000);
        $item->setValue($value);

        $this->assertEquals($value, $item->getValue());
    }

    public function testItemValidate(): void
    {
        $item = $this->getRandomItem();
        $validate = $item->validate();

        $this->assertNull($validate);
    }

    public function testItemValidateInvalidName(): void
    {
        $item = $this->getRandomItem();
        $item->setName('');

        $this->expectException(InvalidArgumentException::class);
        $validate = $item->validate();

        $this->assertNull($validate);
    }

    public function testItemValidateInvalidValue(): void
    {
        $item = $this->getRandomItem();
        $item->setValue(-10);

        $this->expectException(InvalidArgumentException::class);
        $validate = $item->validate();

        $this->assertNull($validate);
    }
}
