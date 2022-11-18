<?php

namespace App\Controllers;

use App\Plugins\Http\Exceptions\BadRequest;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Plugins\Http\Exceptions\Unauthorized;
use App\Plugins\Http\Response\Created;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;
use App\Services\FacilityService;

class FacilityController extends AuthenticationController
{
    private FacilityService $facilityService;
    private AuthenticationService $authenticationService;


    public function __construct(){
        $this->facilityService = new FacilityService();
        $this->authenticationService = new AuthenticationService();
    }


    public function getFacility(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();
        return (new Ok($this->facilityService->getFacility($id)))->send();
    }



    public function listFacilities()
    {
        $employeeId = $this->authenticationService->validateToken();

        if (empty($_GET['facilityname']) && empty($_GET['tagname']) && empty($_GET['locationcity'])) {
            $facilities = $this->facilityService->listFacilities();
        } else {
            $facilities = $this->facilityService->paginationParser();
        }
        return (new Ok($facilities))->send();
    }



    public function createFacility()
    {
        $employeeId = $this->authenticationService->validateToken();

        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $facilityCreated = $this->facilityService->createFacility($decodeJson);

        return (new Created($facilityCreated))->send();
    }


    public function updateFacility(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $facilityUpdated = $this->facilityService->updateFacility($id, $decodeJson);

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

        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $facility = $this->facilityService->addTags($id, $decodeJson);
        return (new Ok($facility))->send();
    }


    public function deleteTag(int $id, int $tagId)
    {
        $employeeId = $this->authenticationService->validateToken();

        $tags = $this->facilityService->deleteTag($id, $tagId);
        return (new Ok($tags))->send();
    }



}