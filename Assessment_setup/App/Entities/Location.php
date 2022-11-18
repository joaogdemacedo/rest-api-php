<?php

namespace App\Entities;

use JsonSerializable;

class Location implements JsonSerializable
{
    private int $id;
    private string $city;
    private string $address;
    private string $zipCode;
    private string $countryCode;
    private string $phoneNumber;

    /**
     * @param int $id
     * @param string $city
     * @param string $address
     * @param string $countryCode
     * @param string $phoneNumber
     */
    public function __construct(int $id, string $city, string $address, string $zipCode, string $countryCode, string $phoneNumber)
    {
        $this->id = $id;
        $this->city = $city;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->countryCode = $countryCode;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function jsonSerialize(): array
    {
        return[
            'id' => $this->getId(),
            'city' => $this->getCity(),
            'address' => $this->getAddress(),
            'zipCode' => $this->getZipCode(),
            'countryCode' => $this->getCountryCode(),
            'phoneNumber' => $this->getPhoneNumber()
        ];
    }
}