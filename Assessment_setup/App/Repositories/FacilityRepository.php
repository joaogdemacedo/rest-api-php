<?php

namespace App\Repositories;

use App\Entities\Facility;
use App\Entities\Location;
use App\Exceptions\FacilityAlreadyExistsException;
use App\Exceptions\FacilityNotFoundException;
use App\Exceptions\FacilityTagNotLinkedException;
use App\Exceptions\UpdateFailedException;
use App\Plugins\Db\Connection\Mysql;
use App\Plugins\Db\Db;
use DateTime;
use PDO;

class FacilityRepository
{

    private Db $db;


    public function __construct()
    {
        $connection = new Mysql('127.0.0.1:8889','catering_facilities','root','root');
        $this->db = new Db($connection);
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


    public function listFacilities(int $nextCursor, int $limit): array
    {
        $sqlListFacilities = '
            SELECT 
                f.id, 
                f.name, 
                f.created_at, 
                l.id AS location_id, 
                l.city AS location_city 
            FROM facility AS f 
            INNER JOIN location AS l ON l.id=f.location_id
            AND f.id>= :nextCursor
            ORDER BY f.id
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlListFacilities);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilityByName(string $facilityName, int $nextCursor, int $limit): array
    {
        $pattern = '%' . $facilityName . '%';
        $sqlGetFacility = '
            SELECT 
                f.*,
                l.city AS location_city
            FROM facility AS f
            INNER JOIN location AS l ON l.id = f.location_id
            WHERE f.name LIKE :facilityName
            AND f.id>= :nextCursor
            ORDER BY f.id 
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlGetFacility);
        $statement->bindParam(':facilityName', $pattern, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByTagName(string $tagName, int $nextCursor, int $limit): array
    {
        $pattern = '%' . $tagName . '%';
        $sqlFacilitiesByTagName = '
            SELECT DISTINCT 
                f.id,
                f.name,
                f.created_at,
                l.id AS location_id,
                l.city AS location_city
            FROM facility AS f 
            INNER JOIN facility_tag AS ft ON ft.facility_id = f.id
            INNER JOIN tag AS t ON t.id=ft.tag_id 
            INNER JOIN location AS l ON l.id=f.location_id
            WHERE t.name LIKE :tagName
            AND f.id>=:nextCursor
            ORDER BY f.id 
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByTagName);
        $statement->bindParam(':tagName', $pattern, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByCity(string $city, int $nextCursor, int $limit): array
    {

        $pattern = '%' . $city . '%';
        $sqlFacilitiesByCity = '
            SELECT 
                f.id, 
                f.name, 
                f.created_at, 
                f.location_id,
                l.city AS location_city
            FROM facility AS f
            INNER JOIN location AS l ON f.location_id = l.id
            WHERE l.city LIKE :city
            AND f.id>=:nextCursor
            ORDER BY f.id 
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByCity);
        $statement->bindParam(':city', $pattern, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByNameTagAndCity(string $facilityName, string $tagName, string $city, int $nextCursor, int $limit): array
    {
        $patternFacilityName = '%' . $facilityName . '%';
        $patternTagName = '%' . $tagName . '%';
        $patternCity = '%' . $city . '%';
        $sqlFacilitiesByNameTagAndLocation = '
            SELECT DISTINCT 
                f.id,
                f.name,
                f.created_at,
                l.id AS location_id,
                l.city AS location_city
            FROM facility AS f 
            INNER JOIN facility_tag AS ft ON ft.facility_id = f.id
            INNER JOIN tag AS t ON t.id=ft.tag_id 
            INNER JOIN location AS l ON l.id=f.location_id
            WHERE t.name LIKE :tagName
            AND l.city LIKE :city
            AND f.name LIKE :facilityName
            AND f.id >= :nextCursor
            ORDER BY f.id
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByNameTagAndLocation);
        $statement->bindParam(':facilityName', $patternFacilityName, PDO::PARAM_STR);
        $statement->bindParam(':tagName', $patternTagName, PDO::PARAM_STR);
        $statement->bindParam(':city', $patternCity, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByNameAndTag(string $facilityName, string $tagName, int $nextCursor, int $limit): array
    {
        $patternFacilityName = '%' . $facilityName . '%';
        $patternTagName = '%' . $tagName . '%';
        $sqlFacilitiesByNameTagAndLocation = '
            SELECT DISTINCT 
                f.id,
                f.name,
                f.created_at,
                l.id AS location_id,
                l.city AS location_city
            FROM facility AS f 
            INNER JOIN facility_tag AS ft ON ft.facility_id = f.id
            INNER JOIN tag AS t ON t.id=ft.tag_id 
            INNER JOIN location AS l ON l.id=f.location_id
            WHERE t.name LIKE :tagName
            AND f.name LIKE :facilityName
            AND f.id >= :nextCursor
            ORDER BY f.id
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByNameTagAndLocation);
        $statement->bindParam(':facilityName', $patternFacilityName, PDO::PARAM_STR);
        $statement->bindParam(':tagName', $patternTagName, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByNameAndCity(string $facilityName, string $city, int $nextCursor, int $limit): array
    {
        $patternFacilityName = '%' . $facilityName . '%';
        $patternCity = '%' . $city . '%';
        $sqlFacilitiesByNameAndLocation = '
            SELECT 
                f.id, 
                f.name, 
                f.created_at, 
                f.location_id,
                l.city AS location_city
            FROM facility AS f
            INNER JOIN location AS l ON f.location_id = l.id
            WHERE l.city LIKE :city
            AND f.name LIKE :facilityName
            AND f.id>=:nextCursor
            ORDER BY f.id 
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByNameAndLocation);
        $statement->bindParam(':facilityName', $patternFacilityName, PDO::PARAM_STR);
        $statement->bindParam(':city', $patternCity, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
    }


    public function getFacilitiesByTagAndCity(string $tagName, string $city, int $nextCursor, int $limit): array
    {
        $patternTagName = '%' . $tagName . '%';
        $patternCity = '%' . $city . '%';
        $sqlFacilitiesByNameTagAndLocation = '
            SELECT DISTINCT 
                f.id,
                f.name,
                f.created_at,
                l.id AS location_id,
                l.city AS location_city
            FROM facility AS f 
            INNER JOIN facility_tag AS ft ON ft.facility_id = f.id
            INNER JOIN tag AS t ON t.id=ft.tag_id 
            INNER JOIN location AS l ON l.id=f.location_id
            WHERE t.name LIKE :tagName
            AND l.city LIKE :city
            AND f.id >= :nextCursor
            ORDER BY f.id
            LIMIT :limit';

        $statement = $this->db->prepareStatement($sqlFacilitiesByNameTagAndLocation);
        $statement->bindParam(':tagName', $patternTagName, PDO::PARAM_STR);
        $statement->bindParam(':city', $patternCity, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':nextCursor', $nextCursor, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if(empty($results)){
            throw new FacilityNotFoundException();
        }
        return $this->parserFacilitiesPagination($results, $limit);
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


    public function createFacilityTagLink(int $facilityId, int $tagId): int
    {
        $sqlLinkTag = '
            INSERT INTO facility_tag (facility_id, tag_id) 
            VALUES (:facilityId, :tagId);';

        $this->db->executeQuery($sqlLinkTag,['facilityId'=> $facilityId, 'tagId' => $tagId]);
        return $this->db->getLastInsertedId();
    }


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
            throw new FacilityTagNotLinkedException();
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

    // Function created to delete repetitive lines
    public function parserFacilitiesPagination(array $results, int $limit): array
    {

        $numberOfRows = 0;
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
                []
            );
            $numberOfRows++;
        }
        if ($numberOfRows<$limit){
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