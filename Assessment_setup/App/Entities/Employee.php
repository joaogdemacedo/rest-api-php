<?php

namespace App\Entities;

use JsonSerializable;

class Employee implements JsonSerializable
{
    private int $id;
    private string $username;
    private string $password;
    private int $facilityId;

    /**
     * @param int $id
     * @param string $username
     * @param string $password
     * @param int $facilityId
     */
    public function __construct(int $id, string $username, string $password, int $facilityId)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->facilityId = $facilityId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     * @return int
     */
    public function getFacilityId(): int
    {
        return $this->facilityId;
    }

    /**
     * @param int $facilityId
     */
    public function setFacilityId(int $facilityId): void
    {
        $this->facilityId = $facilityId;
    }




    public function jsonSerialize(): array
    {
        return[
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'facility' => $this->getFacilityId()
        ];
    }
}