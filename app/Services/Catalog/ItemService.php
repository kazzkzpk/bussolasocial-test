<?php

namespace App\Services\Catalog;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * Class ItemService
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
class ItemService implements ItemServiceInterface
{
    private int $id;
    private string $name;
    private int $value;

    public function __construct(
        int $id,
        string $name,
        int $value
    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * Set item unique identifier
     *
     * @param int $id
     * @return void
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): ItemServiceInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(int $value): ItemServiceInterface
    {
        $this->value = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): void
    {
        $data = $this->toArray();

        $validator = Validator::make($data, [
            'name' => 'required|string|min:1|max:255',
            'value' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }
    }
}
