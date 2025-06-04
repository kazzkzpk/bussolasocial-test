<?php

namespace Tests\Unit\Services;

use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CreditCardService;
use App\Services\CreditCardServiceInterface;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use InvalidArgumentException;

class CreditCardServiceTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create('pt_BR');
    }

    public function testCreditCardHolderName(): void
    {
        $holderName = $this->faker->text(32);
        $creditCard = new CreditCardService(
            $holderName,
            $this->faker->numerify('################'),
            $this->faker->numberBetween(2026, 2030),
            $this->faker->numberBetween(1, 12),
            $this->faker->numerify(),
        );

        $this->assertEquals($holderName, $creditCard->getHolderName());
    }

    public function testCreditCardNumber(): void
    {
        $number = $this->faker->numerify('################');
        $creditCard = new CreditCardService(
            $this->faker->text(32),
            $number,
            $this->faker->numberBetween(2026, 2030),
            $this->faker->numberBetween(1, 12),
            $this->faker->numerify(),
        );

        $this->assertEquals($number, $creditCard->getNumber());
    }

    public function testCreditCardExpirationDateYear(): void
    {
        $expirationDateYear = $this->faker->numberBetween(2026, 2030);
        $creditCard = new CreditCardService(
            $this->faker->text(32),
            $this->faker->numerify('################'),
            $expirationDateYear,
            $this->faker->numberBetween(1, 12),
            $this->faker->numerify(),
        );

        $this->assertEquals($expirationDateYear, $creditCard->getExpirationDateYear());
    }

    public function testCreditCardExpirationDateMonth(): void
    {
        $expirationDateMonth = $this->faker->numberBetween(1, 12);
        $creditCard = new CreditCardService(
            $this->faker->text(32),
            $this->faker->numerify('################'),
            $this->faker->numberBetween(2026, 2030),
            $expirationDateMonth,
            $this->faker->numerify(),
        );

        $this->assertEquals($expirationDateMonth, $creditCard->getExpirationDateMonth());
    }

    public function testCreditCardCvv(): void
    {
        $cvv = $this->faker->numerify();
        $creditCard = new CreditCardService(
            $this->faker->text(32),
            $this->faker->numerify('################'),
            $this->faker->numberBetween(2026, 2030),
            $this->faker->numberBetween(1, 12),
            $cvv,
        );

        $this->assertEquals($cvv, $creditCard->getCvv());
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

    public function testCreditCardHolderNameRuntime(): void
    {
        $creditCard = $this->getRandomCreditCard();

        $holderName = $this->faker->text(32);
        $creditCard->setHolderName($holderName);

        $this->assertEquals($holderName, $creditCard->getHolderName());
    }

    public function testCreditCardNumberRuntime(): void
    {
        $creditCard = $this->getRandomCreditCard();

        $number = $this->faker->numerify('################');
        $creditCard->setNumber($number);

        $this->assertEquals($number, $creditCard->getNumber());
    }

    public function testCreditCardExpirationDateYearRuntime(): void
    {
        $creditCard = $this->getRandomCreditCard();

        $expirationDateYear = $this->faker->numberBetween(2026, 2030);
        $creditCard->setExpirationDateYear($expirationDateYear);

        $this->assertEquals($expirationDateYear, $creditCard->getExpirationDateYear());
    }

    public function testCreditCardExpirationDateMonthRuntime(): void
    {
        $creditCard = $this->getRandomCreditCard();

        $expirationDateMonth = $this->faker->numberBetween(1, 12);
        $creditCard->setExpirationDateMonth($expirationDateMonth);

        $this->assertEquals($expirationDateMonth, $creditCard->getExpirationDateMonth());
    }

    public function testCreditCardCvvRuntime(): void
    {
        $creditCard = $this->getRandomCreditCard();

        $cvv = $this->faker->numerify();
        $creditCard->setCvv($cvv);

        $this->assertEquals($cvv, $creditCard->getCvv());
    }

    public function testCreditCardValidate(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidHolderName(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setHolderName('');

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidNumber(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setNumber($this->faker->numerify('########'));

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidExpirationDateMonth(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setExpirationDateMonth($this->faker->numberBetween(13, 24));

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidExpirationDateYear(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setExpirationDateYear($this->faker->numberBetween(1900, 1920));

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidCvv(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setCvv($this->faker->numerify('#'));

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

    public function testCreditCardValidateInvalidExpiration(): void
    {
        $creditCard = $this->getRandomCreditCard();
        $creditCard->setExpirationDateYear($this->faker->numberBetween(2022, 2024));

        $this->expectException(InvalidArgumentException::class);
        $validate = $creditCard->validate();

        $this->assertNull($validate);
    }

}
