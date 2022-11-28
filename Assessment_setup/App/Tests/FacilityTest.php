<?php

namespace App\Tests;

use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\TagRepository;
use App\Services\FacilityService;
use App\Utils\Utils;
use PHPUnit\Framework\TestCase;

class FacilityTest extends TestCase
{
    public function getRandomName(): string
    {
        $n=10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * @throws NotFound
     * @throws Conflict
     */
    public function testShouldCreateFacilityWithSuccess()
    {
        $dbConnection = Utils::getDbConnection();
        // Given
        $requestPayload = [
            'name' => $this->getRandomName(),
            'location_id' => 1,
            'tag_names' => [
                [
                'name' => 'tag50'
                ]
            ]
        ];

        // When
        $facilityService = new FacilityService($dbConnection);
        $facilityCreated = $facilityService->createFacility($requestPayload);

        // Then
        $getFacility = $facilityService->getFacility($facilityCreated->getId());

        self::assertNotNull($getFacility);
        self::assertSame($requestPayload['name'], $getFacility->getName());
        self::assertSame($requestPayload['location_id'], $getFacility->getLocation()->getId());
        self::assertSame($requestPayload['tag_names'][0]['name'], $getFacility->getTags()[0]->getName());
    }


    /**
     * @throws NotFound
     * @throws Conflict
     */
    public function testShouldUpdateFacilityWithSuccess()
    {
        $dbConnection = Utils::getDbConnection();
        // Given
        $id = 1;
        $requestPayload = [
            'name' => $this->getRandomName(),
            'location_id' => 1,
            'tag_names' => [
                [
                    'name' => $this->getRandomName()
                ]
            ]
        ];

        // When
        $facilityService = new FacilityService($dbConnection);
        $tagRepository = new TagRepository($dbConnection);
        $facilityService->updateFacility($id, $requestPayload);

        // Then
        $results = $tagRepository->getTagsByFacilityId($id);
        $tagName='';
        foreach ($results as $result){ // Check if Tag inserted was linked with this Facility
            if ($result->getName() == $requestPayload['tag_names'][0]['name'])
                $tagName = $result->getName();
        }
        $getFacility = $facilityService->getFacility($id);
        $facilityTag = $tagRepository->getTagByName($requestPayload['tag_names'][0]['name']);

        self::assertNotNull($getFacility);
        self::assertSame($requestPayload['name'],$getFacility->getName());
        self::assertSame($requestPayload['tag_names'][0]['name'],$facilityTag->getName());
        self::assertSame($requestPayload['tag_names'][0]['name'], $tagName);
    }


}