<?php

namespace App\Services;

use App\Entities\Facility;
use App\Entities\Location;
use App\Exceptions\FacilityAlreadyExistsException;
use App\Exceptions\FacilityNotFoundException;
use App\Exceptions\FacilityTagNotLinkedException;
use App\Exceptions\LocationNotFoundException;
use App\Exceptions\TagNotFoundException;
use App\Exceptions\UpdateFailedException;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\FacilityRepository;
use App\Repositories\LocationRepository;
use App\Repositories\TagRepository;
use App\Utils\PaginationParser;

class FacilityService
{
    private FacilityRepository $facilityRepository;
    private TagRepository $tagRepository;
    private LocationRepository $locationRepository;

    public function __construct()
    {
        $this->facilityRepository = new FacilityRepository();
        $this->tagRepository = new TagRepository();
        $this->locationRepository = new LocationRepository();
    }


    public function getFacility(int $id): Facility
    {
        try {
            $facility = $this->facilityRepository->getFacility($id);
        } catch (FacilityNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        }
        $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        return $facility;
    }


    public function listFacilities(): array
    {
        $paginationParser = new PaginationParser(
            !empty($_GET['nextcursor']) ? $_GET['nextcursor'] : 1,
            !empty($_GET['limit']) ? $_GET['limit'] : 10,
            '',
            '',
            ''
        );
        try {
            $facilities = $this->facilityRepository->listFacilities($paginationParser->getNextCursor(), $paginationParser->getLimit()+1);
        } catch (FacilityNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find Facilities.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByName(string $facilityName, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilityByName($facilityName, $nextCursor, $limit);
        } catch (FacilityNotFoundException $exception) {
            throw new Conflict(['message' => 'There are no Facilities with the respective Name pattern.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByTagName(string $tagName, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByTagName($tagName, $nextCursor, $limit+1);
        } catch (FacilityNotFoundException $exception){
            throw new Conflict(['message' => 'There are no Facilities with the respective Tag pattern.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByCity(string $city, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByCity($city, $nextCursor, $limit+1);
        } catch (FacilityNotFoundException $exception){
            throw new Conflict(['message' => 'There are no Facilities with the respective City pattern.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByNameTagAndCity(string $facilityName, string $tagName, string $city, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByNameTagAndCity($facilityName, $tagName, $city, $nextCursor, $limit);
        } catch (FacilityNotFoundException $exception) {
            throw new Conflict(['message' => 'There are no Facilities with the respective patterns.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByNameAndTag(string $facilityName, string $tagName, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByNameAndTag($facilityName, $tagName, $nextCursor, $limit);
        } catch (FacilityNotFoundException $exception) {
            throw new Conflict(['message' => 'There are no Facilities with the respective patterns.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByNameAndCity(string $facilityName, string $city, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByNameAndCity($facilityName, $city, $nextCursor, $limit);
        } catch (FacilityNotFoundException $exception) {
            throw new Conflict(['message' => 'There are no Facilities with the respective patterns.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function searchFacilityByTagAndCity(string $tagName, string $city, int $nextCursor, int $limit): array
    {
        try {
            $facilities = $this->facilityRepository->getFacilitiesByTagAndCity($tagName, $city, $nextCursor, $limit);
        } catch (FacilityNotFoundException $exception) {
            throw new Conflict(['message' => 'There are no Facilities with the respective patterns.']);
        }
        foreach ($facilities["facilities"] as $facility){
            $facility->setTags($this->tagRepository->getTagsByFacilityId($facility->getId()));
        }
        return $facilities;
    }


    public function createFacility(array $requestPayload): Facility
    {
        /** @var string $facilityName */
        $facilityName = $requestPayload['name'];

        /** @var int $locationid */
        $locationId = $requestPayload['location_id'];

        try {
            $location = $this->locationRepository->getLocation($locationId);
            $facility = $this->facilityRepository->createFacility($facilityName,$location);
        } catch (LocationNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Location.']);
        } catch (FacilityAlreadyExistsException $exception){
            throw new Conflict(['message' => 'Facility already exists.']);
        }

        return $facility;

    }


    public function addTags(int $id, array $requestPayload): Facility
    {
        $tagNames = $requestPayload['tag_names'];
        try {
            $facility = $this->facilityRepository->getFacility($id);
        } catch (FacilityNotFoundException $e) {
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        }
        foreach ($tagNames as $tagName){
            try {
                $tag = $this->tagRepository->getTagByName($tagName['name']);
            } catch (TagNotFoundException $e) {
                throw new NotFound(['message' => 'Unable to find Tag/s.']);
            }
            $this->facilityRepository->createFacilityTagLink($id, $tag->getId());
        }
        $facility->setTags($this->tagRepository->getTagsByFacilityId($id));
        return $facility;
    }


    public function updateFacility(int $id, array $requestPayload): Facility
    {
        $facilityId = $id;

        /** @var string $facilityName */
        $facilityName = $requestPayload['name'];

        /** @var  $locationId */
        $locationId = $requestPayload['location_id'];

        try {
            $this->facilityRepository->getFacility($facilityId);
            $location = $this->locationRepository->getLocation($locationId);
            $this->facilityRepository->updateFacilityNameAndLocation($facilityId, $facilityName, $locationId);
        } catch (FacilityNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        } catch (UpdateFailedException $exception){
            throw new Conflict(['message' => 'Update failed.']);
        } catch (LocationNotFoundException $exception) {
            throw new Conflict(['message' => 'Unable to find this Location.']);
        }
        $facility = $this->facilityRepository->getFacility($facilityId);
        $facility->setLocation(new Location($locationId,$location->getCity(),'','','',''));
        $facility->setTags($this->tagRepository->getTagsByFacilityId($facilityId));
        return $facility;
    }


    public function deleteFacility(int $id): Facility
    {

        try {
            $facility = $this->facilityRepository->getFacility($id);
        } catch (FacilityNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        }

        // Database has ON DELETE CASCADE on facility_id column (facility_tag and employees tables)
        // Thus, it is not needed to perform a transaction to delete all relationships
        // with this facility.
        $facility->setTags($this->tagRepository->getTagsByFacilityId($id));
        $this->facilityRepository->deleteFacility($id);

        return $facility;
    }


    public function deleteTag(int $id, int $tagId): Facility
    {
        try {
            $facility = $this->facilityRepository->getFacility($id);
            $this->tagRepository->getTagById($tagId);
            $this->facilityRepository->getFacilityTagLink($id, $tagId);
        } catch (FacilityNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        } catch (TagNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find this Tag.']);
        } catch (FacilityTagNotLinkedException $e) {
            throw new NotFound(['message' => 'This Tag is not linked with this Facility.']);
        }
        $this->facilityRepository->deleteFacilityTagLink($id, $tagId);
        $facility->setTags($this->tagRepository->getTagsByFacilityId($id));
        return $facility;
    }


    public function paginationParser(): array
    {
        $paginationParser = new PaginationParser(
            !empty($_GET['nextcursor']) ? $_GET['nextcursor'] : 1,
            !empty($_GET['limit']) ? $_GET['limit'] : 10,
            !empty($_GET['facilityname']) ? $_GET['facilityname'] : '',
            !empty($_GET['tagname']) ? $_GET['tagname'] : '',
            !empty($_GET['locationcity']) ? $_GET['locationcity'] : ''
        );

        $nextCursor = $paginationParser->getNextCursor();
        $limit = $paginationParser->getLimit() + 1;

        // This if else statement checks all the possibles combinations within the parameters received
        if ($facilityName = $paginationParser->getFacilityName()){
            if ($tagName = $paginationParser->getTagName()){
                if ($city = $paginationParser->getLocationCity()){
                    // Facility name, Tag name and City
                    $facilities = $this->searchFacilityByNameTagAndCity($facilityName, $tagName, $city, $nextCursor, $limit);
                } else {
                    // Facility name and Tag name
                    $facilities = $this->searchFacilityByNameAndTag($facilityName, $tagName, $nextCursor, $limit);
                }
            } elseif ($city = $paginationParser->getLocationCity()) {
                // Facility name and City
                $facilities = $this->searchFacilityByNameAndCity($facilityName, $city, $nextCursor, $limit);

            } else {
                // Facility name only
                $facilities = $this->searchFacilityByName($facilityName, $nextCursor, $limit);
            }
        }
        elseif ($tagName = $paginationParser->getTagName()) {
            if ($city = $paginationParser->getLocationCity()){
                // Tag name and City
                $facilities = $this->searchFacilityByTagAndCity($tagName, $city, $nextCursor, $limit);

            } else {
                // Tag name only
                $facilities = $this->searchFacilityByTagName($tagName, $nextCursor, $limit);
            }
        }
        elseif ($city = $paginationParser->getLocationCity()){
            // City only
            $facilities = $this->searchFacilityByCity($city, $nextCursor, $limit);
        }
        else {
            //It did not get any query parameter (however, it never gets here due to a check in FacilityController).
            throw new Conflict(['message' => 'Query parameters needed.']);
        }
        return $facilities;
    }


}