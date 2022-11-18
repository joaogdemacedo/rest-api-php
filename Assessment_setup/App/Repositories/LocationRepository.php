<?php

namespace App\Repositories;

use App\Entities\Location;
use App\Exceptions\LocationNotFoundException;
use App\Plugins\Db\Connection\Mysql;
use App\Plugins\Db\Db;

class LocationRepository
{

    private Db $db;

    public function __construct()
    {
        $connection = new Mysql('127.0.0.1:8889','catering_facilities','root','root');
        $this->db = new Db($connection);
    }


    public function getLocation(int $locationId): Location
    {
        $sqlGetLocation = '
            SELECT * FROM location 
            WHERE location.id = :locationId';

        $this->db->executeQuery($sqlGetLocation,['locationId' => $locationId]);
        $location = $this->db->getStatement()->fetch();
        if(empty($location)){
            throw new LocationNotFoundException();
        }
        return new Location(
            $location['id'],
            $location['city'],
            $location['address'],
            $location['zip_code'],
            $location['country_code'],
            $location['phone_number'],
        );
    }


}