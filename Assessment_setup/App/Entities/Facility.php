<?php

namespace App\Entities;

use DateTime;
use DateTimeInterface;
use JsonSerializable;

class Facility implements JsonSerializable
{
    private int $id;
    private string $name;
    private DateTime $createdAt;

    private Location $location;
    /** @var Tag[]  */
    private array $tags;


    public function __construct(int $id, string $name, DateTime $createdAt, ?Location $location, array $tags)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->location = $location;
        $this->tags[] = $tags;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }




    public function jsonSerialize(): array
    {
        return[
            'id' => $this->getId(),
            'name' => $this->getName(),
            'createdAt' => $this->getCreatedAt()->format(DateTimeInterface::ATOM),
            'location' => $this->location->getCity(),
            'tags' => $this->tags
        ];
    }
}