<?php

namespace App\Utils;

class PaginationParser
{
    private int $nextCursor;
    private int $limit;
    private string $facilityName;
    private string $tagName;
    private string $locationCity;

    /**
     * @param int $nextCursor
     * @param int $limit
     * @param string $facilityName
     * @param string $tagName
     * @param string $locationCity
     */
    public function __construct(int $nextCursor, int $limit, string $facilityName, string $tagName, string $locationCity)
    {
        $this->nextCursor = $nextCursor;
        $this->limit = $limit;
        $this->facilityName = $facilityName;
        $this->tagName = $tagName;
        $this->locationCity = $locationCity;
    }

    /**
     * @return int
     */
    public function getNextCursor(): int
    {
        return $this->nextCursor;
    }

    /**
     * @param int $nextCursor
     */
    public function setNextCursor(int $nextCursor): void
    {
        $this->nextCursor = $nextCursor;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getFacilityName(): string
    {
        return $this->facilityName;
    }

    /**
     * @param string $facilityName
     */
    public function setFacilityName(string $facilityName): void
    {
        $this->facilityName = $facilityName;
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     */
    public function setTagName(string $tagName): void
    {
        $this->tagName = $tagName;
    }

    /**
     * @return string
     */
    public function getLocationCity(): string
    {
        return $this->locationCity;
    }

    /**
     * @param string $locationCity
     */
    public function setLocationCity(string $locationCity): void
    {
        $this->locationCity = $locationCity;
    }



}