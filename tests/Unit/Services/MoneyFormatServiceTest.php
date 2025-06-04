<?php

namespace Tests\Unit\Services;

use App\Services\MoneyFormatterService;
use App\Services\MoneyFormatterServiceInterface;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use NumberFormatter;
use Tests\TestCase;

class MoneyFormatServiceTest extends TestCase
{
    use WithFaker;

    protected MoneyFormatterServiceInterface $moneyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create('pt_BR');
        $this->moneyService = new MoneyFormatterService();
    }

    public function testFormat(): void
    {
        $brlMoney = $this->faker->numberBetween(10000, 20000);
        $moneyFormatted = (new NumberFormatter('pt_BR', NumberFormatter::CURRENCY))->formatCurrency($brlMoney / 100, 'BRL');;
        $this->assertEquals($moneyFormatted, $this->moneyService->format($brlMoney));
    }
}
