<?php

namespace App\Services;

use App\Entities\Facility;
use App\Entities\Location;
use App\Exceptions\FacilityAlreadyExistsException;
use App\Exceptions\FacilityNotFoundException;
use App\Exceptions\FacilityTagAlreadyLinkedException;
use App\Exceptions\LocationNotFoundException;
use App\Exceptions\TagNotFoundException;
use App\Exceptions\UpdateFailedException;
use App\Plugins\Db\Db;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\FacilityRepository;
use App\Repositories\LocationRepository;
use App\Repositories\TagRepository;
use App\Utils\PaginationParser;
use DateTime;

class FacilityService
{
    private FacilityRepository $facilityRepository;
    private TagRepository $tagRepository;
    private LocationRepository $locationRepository;
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->facilityRepository = new FacilityRepository($this->db);
        $this->tagRepository = new TagRepository($this->db);
        $this->locationRepository = new LocationRepository($this->db);
    }

    // Get a Facility and its Tags with its ID
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


    // Create Facility and link Tags as well
    // Transactions are needed here because data is inserted in facility and facility_tag tables
    // If something goes wrong, nothing is inserted.
    public function createFacility(array $requestPayload): Facility
    {
        /** @var string $facilityName */
        $facilityName = $requestPayload['name'];

        /** @var int $locationid */
        $locationId = $requestPayload['location_id'];

        $this->db->beginTransaction();
        try {
            $location = $this->locationRepository->getLocation($locationId);
            $facility = $this->facilityRepository->createFacility($facilityName,$location);
            $facilityWithTags = $this->addTags($facility->getId(), $requestPayload);
            $this->db->commit();
            return $facilityWithTags;
        } catch (LocationNotFoundException $exception){
            $this->db->rollBack();
            throw new NotFound(['message' => 'Unable to find this Location.']);
        } catch (FacilityAlreadyExistsException $exception){
            $this->db->rollBack();
            throw new Conflict(['message' => 'Facility already exists.']);
        } catch (Conflict $exception) {
            $this->db->rollBack();
            throw new Conflict(['message' => 'Unable to link same Tag multiple times.']);
        } catch (NotFound $exception){
            $this->db->rollBack();
            throw new NotFound(['message' => 'Unable to find Tag or Tags.']);
        }

    }


    // Add existing Tags to a Facility
    // e.g., add an entry to facility_tag table with Facility and Tag ids
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
                $this->facilityRepository->createFacilityTagLink($id, $tag->getId());
            } catch (TagNotFoundException $exception) {
                throw new NotFound(['message' => 'Unable to find Tag or Tags.']);
            } catch (FacilityTagAlreadyLinkedException $exception){
                throw new Conflict(['message' => 'Tag already linked to this facility.']);
            }
        }
        $facility->setTags($this->tagRepository->getTagsByFacilityId($id));
        return $facility;
    }

    // Change current name or location of a Facility
    // To update Tags of a Facility, deleteTag or addTags are used
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
            throw new Conflict(['message' => 'Facility name and Location already exists.']);
        } catch (LocationNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find this Location.']);
        }
        $facility = $this->facilityRepository->getFacility($facilityId);
        $facility->setLocation(new Location($locationId,$location->getCity(),'','','',''));
        $facility->setTags($this->tagRepository->getTagsByFacilityId($facilityId));
        return $facility;
    }

    // Delete a Facility and remove its linked Tags and Employees
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

    // Delete a relationship of a Facility and one Tag
    // e.g. remove from facility_tag with Facility and Tag IDs
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
        } catch (FacilityTagAlreadyLinkedException $e) {
            throw new NotFound(['message' => 'This Tag is not linked with this Facility.']);
        }
        $this->facilityRepository->deleteFacilityTagLink($id, $tagId);
        $facility->setTags($this->tagRepository->getTagsByFacilityId($id));
        return $facility;
    }


    // List Facilities with cursor pagination and search criteria included
    // paginationParser Object used to store filter values (e.g. Facility name, Tag name, Location City)
    // nextCursor and limit with default values in case of not specified
    public function listFacilities(): array
    {
        $paginationParser = new PaginationParser(
            !empty($_GET['nextcursor']) ? $_GET['nextcursor'] : 1,
            !empty($_GET['limit']) ? $_GET['limit'] + 1 : 11, // plus one in order to get the id of 'nextCursor', e.g., id of the next Facility
            !empty($_GET['facilityname']) ? $_GET['facilityname'] : '',
            !empty($_GET['tagname']) ? $_GET['tagname'] : '',
            !empty($_GET['locationcity']) ? $_GET['locationcity'] : ''
        );
        try {
            $results = $this->facilityRepository->listFacilities($paginationParser);
        } catch (FacilityNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find Facilities with such filters.']);
        }
        $facilities = [];
        foreach ($results as $result){
            $location = new Location(
                intval($result['location_id']),
                $result['location_city'],
                '',
                '',
                '' ,
                ''
            );
            $facilities[]= new Facility(
                intval($result['id']),
                $result['name'],
                new DateTime($result['created_at']),
                $location,
                $this->tagRepository->getTagsByFacilityId(intval($result['id']))
            );
        }
        if (count($results)<$paginationParser->getLimit()){
            // It means that is the last page of results
            $nextCursor = null;
        } else {
            $lastFacility = array_pop($facilities);
            $nextCursor = $lastFacility->getId();
        }
        return [
            "facilities" => $facilities,
            "nextCursor" => $nextCursor
        ];
    }

}