<?php

namespace App\Data;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var string|null
     * @Assert\NotNull()
     */
    private $firstName;

    /**
     * @var string|null
     * @Assert\NotNull()
     */
    private $lastName;

    /**
     * @var string|null
     * @Assert\NotNull()
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @var Product|null
     */
    private $ad;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Product|null
     */
    public function getAd(): ?Product
    {
        return $this->ad;
    }

    /**
     * @param Product|null $ad
     */
    public function setAd(?Product $ad): void
    {
        $this->ad = $ad;
    }
}