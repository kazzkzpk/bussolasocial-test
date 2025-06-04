<?php

namespace App\Services\Payment;

use App\Exceptions\EmptyPaymentCreditCardException;
use App\Services\CreditCardServiceInterface;
use App\Services\PaymentService;
use App\Services\PaymentServiceInterface;
use InvalidArgumentException;

/**
 * Class CreditCardService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class CreditCardService extends PaymentService implements PaymentServiceInterface
{
    private const int INSTALLMENTS_DEFAULT = 1;
    private const int INSTALLMENTS_DISCOUNT = 1;

    private const float DISCOUNT_FACTOR = 0.1; // 10%
    private const float FEES_FACTOR = 0.01; // 1%

    protected int $installments = self::INSTALLMENTS_DEFAULT;
    protected ?CreditCardServiceInterface $creditCard = null;

    /**
     * @inheritDoc
     */
    public function getValue() : int
    {
        $value = $this->shoppingCartService->getValue();
        $value -= $this->doDiscount($value);
        $value += $this->doFees($value);
        return $value;
    }

    /**
     * Get payment discounts
     * @return int
     */
    public function getDiscount(): int
    {
        $value = $this->shoppingCartService->getValue();
        return $this->doDiscount($value);
    }

    /**
     * Execute discount factor
     *
     * @param int $value
     * @return int
     */
    private function doDiscount(int $value) : int
    {
        if ($this->getInstallments() > self::INSTALLMENTS_DISCOUNT) {
            return 0;
        }
        return (int)($value * self::DISCOUNT_FACTOR);
    }

    /**
     * Set payment installments
     *
     * @param int $installments
     * @return void
     */
    public function setInstallments(int $installments): void
    {
        if ($installments < 1 || $installments > 12) {
            throw new InvalidArgumentException('Installments must be greater than 0 and less than 12.');
        }

        $this->installments = $installments;
    }

    /**
     * Get payment installments
     *
     * @return int
     */
    public function getInstallments(): int
    {
        return $this->installments;
    }

    /**
     * Execute fees factor
     *
     * @param int $value
     * @return int
     */
    private function doFees(int $value) : int
    {
        $installments = $this->getInstallments();
        if ($installments <= self::INSTALLMENTS_DISCOUNT) {
            return 0;
        }

        return (int)(($value * pow(1 + self::FEES_FACTOR, $installments)) - $value);
    }

    /**
     * Get payment fees
     * @return int
     */
    public function getFees(): int
    {
        $value = $this->shoppingCartService->getValue();
        $value -= $this->doDiscount($value);
        return $this->doFees($value);
    }

    public function setCreditCard(CreditCardServiceInterface $creditCard)
    {
        $this->creditCard = $creditCard;
    }

    public function getCreditCard(): ?CreditCardServiceInterface
    {
        return $this->creditCard;
    }

    /**
     * @inheritDoc
     * @throws EmptyPaymentCreditCardException
     */
    public function validate(): void
    {
        parent::validate();

        $creditCard = $this->getCreditCard();
        if ($creditCard === null) {
            throw new EmptyPaymentCreditCardException('Payment credit card is empty.');
        }

        $creditCard->validate();
    }
}
