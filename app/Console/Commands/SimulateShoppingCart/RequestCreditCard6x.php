<?php

namespace App\Console\Commands\SimulateShoppingCart;

use App\Console\Commands\BaseCommand;
use App\Services\Catalog\ItemService;
use App\Services\Catalog\ItemServiceInterface;
use App\Services\CatalogServiceInterface;
use App\Services\CreditCardService;
use App\Services\MoneyFormatterServiceInterface;
use App\Services\Payment\CreditCardService as CreditCardPaymentService;
use App\Services\Payment\PixService as PixPaymentService;
use App\Services\ShoppingCartService;
use App\Services\ShoppingCartServiceInterface;

class RequestCreditCard6x extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simulate-shopping-cart:request-creditcard-6x';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate Shopping Cart with Credit Card (6x installments) Request';

    private CatalogServiceInterface $catalogService;

    private MoneyFormatterServiceInterface $moneyFormatterService;

    public function __construct(
        CatalogServiceInterface $catalogService,
        MoneyFormatterServiceInterface $moneyFormatterService
    )
    {
        parent::__construct();
        $this->catalogService = $catalogService;
        $this->moneyFormatterService = $moneyFormatterService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->log('Initialize ' . $this->description);

        $this->catalogService->addItem(
            new ItemService(
                1,
                'Smart Tv Samsung 32" Polegadas Led Hd Wi-fi Hdmi',
                104788
            )
        );

        $this->catalogService->addItem(
            new ItemService(
                3,
                'Geladeira Brastemp Frost Free 375L 220V',
                320990
            )
        );


        // Shopping Cart
        $shoppingCart = new ShoppingCartService();

        $shoppingCart->addItem($this->catalogService->getItem(1), 2);
        $this->logItem($shoppingCart, $this->catalogService->getItem(1), 2);

        $shoppingCart->addItem($this->catalogService->getItem(3));
        $this->logItem($shoppingCart, $this->catalogService->getItem(3), 1);

        $shoppingCart->validate($this->catalogService);

        // Credit Card Payment
        $creditCard = new CreditCardService(
            'FULANO DA SILVA',
            '4000000000000002',
            2025,
            6,
            '123'
        );
        $creditCard->validate();


        $creditCardPaymentService = new CreditCardPaymentService($shoppingCart);
        $creditCardPaymentService->setInstallments(6);
        $creditCardPaymentService->setCreditCard($creditCard);
        $creditCardPaymentService->validate();

        $this->log('Credit Card Payment total value: ' . $this->moneyFormatterService->format($creditCardPaymentService->getValue()));
        $this->log('Credit Card Payment discount value: ' . $this->moneyFormatterService->format($creditCardPaymentService->getDiscount()));
        $this->log('Credit Card Payment fees value: ' . $this->moneyFormatterService->format($creditCardPaymentService->getFees()));

        $this->logInstallments($creditCardPaymentService, 6);
    }

    /**
     * Log item added to shopping cart
     *
     * @param ShoppingCartServiceInterface $shoppingCart
     * @param ItemServiceInterface $item
     * @param int $count
     * @return void
     */
    protected function logItem(ShoppingCartServiceInterface $shoppingCart, ItemServiceInterface $item, int $count): void
    {
        $this->log('Adding ' . $count . 'x item id 1 [' . $item->getName() . '] with unit cost [' .
            $this->moneyFormatterService->format($item->getValue()) . '] to item cart');
        $this->log('Shopping Card total value: ' . $this->moneyFormatterService->format($shoppingCart->getValue()));
    }

    protected function logInstallments(CreditCardPaymentService $creditCardPaymentService, int $installments): void
    {
        if ($installments === 1) {
            $this->log('Request with 1x installments at value: ' . $this->moneyFormatterService->format($creditCardPaymentService->getValue()));
            return;
        }

        $totalValue = $creditCardPaymentService->getValue();
        $installmentValue = (int)($creditCardPaymentService->getValue() / 6);
        $diffValue = ($totalValue - ($installmentValue * 6));

        if ($diffValue === 0) {
            $this->log('Request with ' . $installments . 'x installments at value: ' . $this->moneyFormatterService->format($installmentValue));
            return;
        }

        $this->log('Request with 1x installments at value: ' . $this->moneyFormatterService->format($installmentValue + $diffValue));
        $this->log('Request with ' . ($installments - 1) . 'x installments at value: ' . $this->moneyFormatterService->format($installmentValue));
    }
}
