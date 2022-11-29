<?php

namespace App\Repositories;

use App\Entities\Facility;
use App\Entities\Location;
use App\Exceptions\FacilityAlreadyExistsException;
use App\Exceptions\FacilityNotFoundException;
use App\Exceptions\FacilityTagAlreadyLinkedException;
use App\Exceptions\UpdateFailedException;
use App\Plugins\Db\Db;
use App\Utils\PaginationParser;
use DateTime;
use PDO;

class FacilityRepository
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }


    public function getFacility(int $facilityId): Facility
    {
        $sqlGetFacility = '
            SELECT 
                f.id, 
                f.name, 
                f.created_at, 
                l.id AS location_id, 
                l.city AS location_city 
            FROM facility AS f 
            INNER JOIN location AS l ON l.id=f.location_id
            WHERE f.id = :facilityId';

        $this->db->executeQuery($sqlGetFacility,['facilityId' => $facilityId]);
        $facility = $this->db->getStatement()->fetch();
        if(empty($facility)){
            throw new FacilityNotFoundException();
        }
        return new Facility(
            $facility['id'],
            $facility['name'],
            new DateTime($facility['created_at']),
            new Location(
                $facility['location_id'],
                $facility['location_city'],
                '',
                '',
                '',
                ''
            ),
            []
        );
    }


    // Get Facilities that satisfies filters obtained
    // If none filter is get, list all Facilities
    // Otherwise, look for Facilities with respective patterns of name, tag and/or city
    public function listFacilities(PaginationParser $pp): array
    {
        $nextCursor = $pp->getNextCursor();
        $limit = $pp->getLimit();
        $patternFacilityName = '%' . $pp->getFacilityName() . '%';
        $patternTagName = '%' . $pp->getTagName() . '%';
        $patternCity = '%' . $pp->getLocationCity() . '%';

        $sqlQuery = '
            SELECT DISTINCT
                f.id,
                f.name,
                f.created_at,
                l.id AS location_id,
                l.city AS location_city
            FROM facility AS f
            INNER JOIN location AS l ON l.id = f.location_id
            INNER JOIN facility_tag AS ft ON ft.facility_id = f.id
            INNER JOIN tag AS t ON t.id=ft.tag_id ';

        $filters = [];
        $whereFlag = false;
        if (!empty($pp->getFacilityName())){
            $sqlQuery .= ($whereFlag ? 'AND ' : 'WHERE ' ) . 'f.name LIKE :facilityName ';
            $whereFlag = true;
            $filters[] = array(':facilityName', $patternFacilityName);
        }
        if (!empty($pp->getTagName())){
            $sqlQuery .= ($whereFlag ? 'AND ' : 'WHERE ') . 't.name LIKE :tagName ';
            $whereFlag = true;
            $filters[] = [':tagName', $patternTagName];
        }
        if (!empty($pp->getLocationCity())){
            $sqlQuery .= ($whereFlag ? 'AND ' : 'WHERE ') . 'l.city LIKE :city ';
            $whereFlag = true;
            $filters[] = [':city', $patternCity];
        }
        $sqlQuery .= '
            AND f.id>= :nextCursor
            ORDER BY f.id
            LIMIT :limit';
        $statement = $this->db->prepareStatement($sqlQuery);

        foreach ($filters as $filter){
            $statement->bindParam( $filter[0],$filter[1], PDO::PARAM_STR);
        }

        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $results;
    }


    public function createFacility(string $facilityName, Location $location): Facility
    {
        $dateTime = new DateTime('now');
        $sqlInsertFacility = '
            INSERT INTO facility (name, created_at, location_id)
            VALUES (:facilityName, :dateTime, :locationId)';

        $locationId = $location->getId();
        $this->db->executeQuery($sqlInsertFacility, ['facilityName' => $facilityName, 'dateTime' => $dateTime->format('Y-m-d H:i:s'), 'locationId' => $locationId]);
        $facilityInsertedId = $this->db->getLastInsertedId();
        if ($facilityInsertedId==0){
            throw new FacilityAlreadyExistsException();
        }
        return new Facility(
            $facilityInsertedId,
            $facilityName,
            $dateTime,
            $location,
            []
        );

    }


    // Link Facility with Tag
    // e.g., insert an entry in junction table facility_tag with Facility and Tag IDs
    public function createFacilityTagLink(int $facilityId, int $tagId): int
    {
        $sqlLinkTag = '
            INSERT INTO facility_tag (facility_id, tag_id) 
            VALUES (:facilityId, :tagId);';

        $this->db->executeQuery($sqlLinkTag,['facilityId'=> $facilityId, 'tagId' => $tagId]);
        $facilityLinkedRow = $this->db->getStatement()->rowCount();
        if ($facilityLinkedRow==0){
            throw new FacilityTagAlreadyLinkedException();
        }
        return $facilityLinkedRow;
    }


    // Remove link of a Facility with a Tag
    // e.g., delete the row in junction table facility_tag with Facility and Tag IDs
    public function deleteFacilityTagLink(int $facilityId, int $tagId)
    {
        $sqlDeleteLink = '
            DELETE FROM facility_tag
            WHERE facility_id = :facilityId AND tag_id = :tagId';

        $this->db->executeQuery($sqlDeleteLink,['facilityId' => $facilityId, 'tagId' => $tagId]);
    }


    public function getFacilityTagLink(int $facilityId, int $tagId)
    {
        $sqlGetFacilityTag = '
            SELECT * FROM facility_tag WHERE facility_id=:facilityId AND tag_id=:tagId';

        $this->db->executeQuery($sqlGetFacilityTag, ['facilityId' => $facilityId, 'tagId' => $tagId]);
        $result = $this->db->getStatement()->fetch();
        if (empty($result)){
            throw new FacilityTagAlreadyLinkedException();
        }
        return $result;
    }


    public function updateFacilityNameAndLocation(int $facilityId, string $facilityName, int $locationId): bool
    {
        $sqlUpdateFacilityName = '
            UPDATE facility SET name = :facilityName, location_id = :locationId
            WHERE id = :facilityId;';

        $result = $this->db->executeQuery($sqlUpdateFacilityName, [
            'facilityName' => $facilityName,
            'locationId' =>$locationId  ,
            'facilityId' => $facilityId
        ]);
        if ($result != 1){
            throw new UpdateFailedException();
        }
        return true;
    }


    public function deleteFacility(int $facilityId)
    {
        $sqlDeleteFacility = '
            DELETE FROM facility
            WHERE id = :facilityId';

        $this->db->executeQuery($sqlDeleteFacility, ['facilityId' => $facilityId]);
    }

}