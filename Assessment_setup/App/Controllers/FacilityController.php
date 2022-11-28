<?php

namespace App\Controllers;

use App\Plugins\Http\Response\Created;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;
use App\Services\FacilityService;
use App\Utils\Utils;

class FacilityController extends AuthenticationController
{
    private FacilityService $facilityService;
    private AuthenticationService $authenticationService;


    public function __construct()
    {
        $dbConnection = Utils::getDbConnection();
        $this->facilityService = new FacilityService($dbConnection);
        $this->authenticationService = new AuthenticationService($dbConnection);
    }


    public function getFacility(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();
        return (new Ok($this->facilityService->getFacility($id)))->send();
    }


    public function listFacilities()
    {
        $employeeId = $this->authenticationService->validateToken();

        $facilities = $this->facilityService->listFacilities();
        return (new Ok($facilities))->send();
    }


    public function createFacility()
    {
        $employeeId = $this->authenticationService->validateToken();

        $facilityCreated = $this->facilityService->createFacility(Utils::getDecodeJson());
        return (new Created($facilityCreated))->send();
    }


    public function updateFacility(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $facilityUpdated = $this->facilityService->updateFacility($id, Utils::getDecodeJson());
        return (new Ok($facilityUpdated))->send();
    }


    public function deleteFacility(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $facilityDeleted = $this->facilityService->deleteFacility($id);
        return (new Ok($facilityDeleted))->send();
    }


    public function addTags(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $facility = $this->facilityService->addTags($id, Utils::getDecodeJson());
        return (new Ok($facility))->send();
    }


    public function deleteTag(int $id, int $tagId)
    {
        $employeeId = $this->authenticationService->validateToken();

        $tags = $this->facilityService->deleteTag($id, $tagId);
        return (new Ok($tags))->send();
    }



}