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
                'name' => 'tag1'
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
            'location_id' => '1'
        ];

        // When
        $facilityService = new FacilityService($dbConnection);
        $facilityService->updateFacility($id, $requestPayload);

        // Then
        $getFacility = $facilityService->getFacility($id);

        self::assertNotNull($getFacility);
        self::assertSame($requestPayload['name'],$getFacility->getName());
        self::assertSame(intval($requestPayload['location_id']), $getFacility->getLocation()->getId());
    }


}