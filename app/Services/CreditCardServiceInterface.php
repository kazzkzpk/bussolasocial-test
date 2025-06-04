<?php

namespace App\Services;

use App\Services\Catalog\ItemServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * Interface CreditCardServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface CreditCardServiceInterface
{
    /**
     * Set credit card holder name
     *
     * @param string $holderName
     * @return CreditCardServiceInterface
     */
    public function setHolderName(string $holderName): CreditCardServiceInterface;

    /**
     * Get credit card holder name
     *
     * @return string
     */
    public function getHolderName(): string;

    /**
     * Set credit card number
     *
     * @param string $number
     * @return CreditCardServiceInterface
     */
    public function setNumber(string $number): CreditCardServiceInterface;

    /**
     * Get credit card number
     *
     * @return string
     */
    public function getNumber(): string;

    /**
     * Set credit card expirate date year
     *
     * @param int $expirationDateYear
     * @return CreditCardServiceInterface
     */
    public function setExpirationDateYear(int $expirationDateYear): CreditCardServiceInterface;

    /**
     * Get credit card expirate date year
     *
     * @return int
     */
    public function getExpirationDateYear(): int;

    /**
     * Set credit card expirate date month
     *
     * @param int $expirationDateMonth
     * @return CreditCardServiceInterface
     */
    public function setExpirationDateMonth(int $expirationDateMonth): CreditCardServiceInterface;

    /**
     * Get credit card expirate date month
     *
     * @return int
     */
    public function getExpirationDateMonth(): int;

    /**
     * Set credit card CVV
     *
     * @param string $cvv
     * @return CreditCardServiceInterface
     */
    public function setCvv(string $cvv): CreditCardServiceInterface;

    /**
     * Get credit card CVV
     *
     * @return string
     */
    public function getCvv(): string;


    /**
     * Get credit card as array key/value
     *
     * @return array<string,mixed>
     */
    public function toArray(): array;

    /**
     * Validate credit card
     */
    public function validate(): void;
}
