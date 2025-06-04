<?php

namespace App\Services\Catalog;

/**
 * Interface ItemServiceInterface
 * @author    Gabriel Morgado <kazzxd1@gmail.com>
 * @copyright Kazz Corp <kazzcorp.com>
 */
interface ItemServiceInterface
{
    /**
     * Get item unique id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Update item name
     * @param string $name
     * @return ItemServiceInterface
     */
    public function setName(string $name): ItemServiceInterface;

    /**
     * Get item name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set item value
     *
     * @param int $value
     * @return ItemServiceInterface
     */
    public function setValue(int $value): ItemServiceInterface;

    /**
     * Get item value
     *
     * @return int
     */
    public function getValue(): int;


    /**
     * Get item as array key/value
     *
     * @return array<string,mixed>
     */
    public function toArray(): array;

    /**
     * Validate item
     */
    public function validate(): void;
}
