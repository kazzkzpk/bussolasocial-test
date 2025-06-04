<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * Class CreditCardService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class CreditCardService implements CreditCardServiceInterface
{
    private string $holderName;
    private string $number;
    private int $expirationDateYear;
    private int $expirationDateMonth;
    private string $cvv;

    public function __construct(
        string $holderName,
        string $number,
        int $expirationDateYear,
        int $expirationDateMonth,
        string $cvv
    ) {
        $this->setHolderName($holderName);
        $this->setNumber($number);
        $this->setExpirationDateYear($expirationDateYear);
        $this->setExpirationDateMonth($expirationDateMonth);
        $this->setCvv($cvv);
    }

    /**
     * {@inheritdoc}
     */
    public function setHolderName(string $holderName): CreditCardServiceInterface
    {
        $this->holderName = $holderName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHolderName(): string
    {
        return $this->holderName;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumber(string $number): CreditCardServiceInterface
    {
        $this->number = $number;

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpirationDateYear(int $expirationDateYear): CreditCardServiceInterface
    {
        $this->expirationDateYear = $expirationDateYear;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpirationDateYear(): int
    {
        return $this->expirationDateYear;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpirationDateMonth(int $expirationDateMonth): CreditCardServiceInterface
    {
        $this->expirationDateMonth = $expirationDateMonth;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpirationDateMonth(): int
    {
        return $this->expirationDateMonth;
    }

    /**
     * {@inheritdoc}
     */
    public function setCvv(string $cvv): CreditCardServiceInterface
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCvv(): string
    {
        return $this->cvv;
    }


    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'holderName' => $this->getHolderName(),
            'number' => $this->getNumber(),
            'expirationDateYear' => $this->getExpirationDateYear(),
            'expirationDateMonth' => $this->getExpirationDateMonth(),
            'cvv' => $this->getCvv(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): void
    {
        $data = $this->toArray();

        $validator = Validator::make($data, [
            'holderName' => 'required|string|min:1|max:128',
            'number' => 'required|string|min:16|max:16',
            'expirationDateYear' => 'required|integer|min:1900|max:2200',
            'expirationDateMonth' => 'required|integer|min:1|max:12',
            'cvv' => 'required|string|min:3|max:3',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $expirationTs = \DateTime::createFromFormat(
            'Y-m',
            $this->getExpirationDateYear() . '-' . $this->getExpirationDateMonth()
        )->getTimestamp();

        if (time() > $expirationTs) {
            throw new InvalidArgumentException('Credit card expired.');
        }
    }
}
