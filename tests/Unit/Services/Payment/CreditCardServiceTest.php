<?php

namespace Tests\Unit\Services\Payment;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CatalogService;
use App\Services\CatalogServiceInterface;
use App\Services\CreditCardService;
use App\Services\CreditCardServiceInterface;
use App\Services\Payment\CreditCardService as CreditCardPaymentService;
use App\Services\ShoppingCartService;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use InvalidArgumentException;
use Tests\TestCase;

class CreditCardServiceTest extends TestCase
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

    protected function getRandomCreditCard(): CreditCardServiceInterface
    {
        $creditCard = new CreditCardService(
            $this->faker->text(32),
            $this->faker->numerify('################'),
            $this->faker->numberBetween(2026, 2030),
            $this->faker->numberBetween(1, 12),
            $this->faker->numerify(),
        );

        return $creditCard;
    }

    public function testCreditCardInstallments(): void
    {
        $shoppingCart = new ShoppingCartService();
        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);
        $installments = $this->faker->numberBetween(1, 5);
        $creditCardPaymentService->setInstallments($installments);

        $this->assertEquals($installments, $creditCardPaymentService->getInstallments());
    }

    public function testCreditCard1xRequest(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);
        $creditCardPaymentService->setInstallments(1);
        $discount = $creditCardPaymentService->getDiscount();

        $this->assertEquals($shoppingCart->getValue() - $discount, $creditCardPaymentService->getValue());
    }

    public function testCreditCard6xRequest(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);
        $installments = 6;
        $creditCardPaymentService->setInstallments($installments);
        $discount = $creditCardPaymentService->getDiscount();
        $fees = $creditCardPaymentService->getFees();

        $this->assertEquals($shoppingCart->getValue() - $discount + $fees, $creditCardPaymentService->getValue());
    }

    public function testCreditCardValidate(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);
        $creditCardPaymentService->setCreditCard($this->getRandomCreditCard());
        $validate = $creditCardPaymentService->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidCreditCard(): void
    {
        $item = $this->getRandomItem();
        $this->catalogService->addItem($item);

        $count = $this->faker->numberBetween(1, 5);

        $shoppingCart = new ShoppingCartService();
        $shoppingCart->addItem($item, $count);

        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);

        $creditCard = $this->getRandomCreditCard();
        $creditCard->setNumber($this->faker->numerify('#'));
        $creditCardPaymentService->setCreditCard($creditCard);

        $this->expectException(InvalidArgumentException::class);

        $validate = $creditCardPaymentService->validate();

        $this->assertNull($validate);
    }
}
