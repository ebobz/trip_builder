<?php
namespace TripBuilder\Api;

use TripBuilder\Util\Database;
use TripBuilder\Model\Response;

class TripApiController extends ApiController
{

    function __construct()
    {
        $this->register(ApiController::POST, "/trip", "createTrip");
        $this->register(ApiController::GET, "/trip", "getTrip");
    }

    public function createTrip()
    {
        $db = Database::getInstance();
        
        try {
            $db->query("
            insert into tb_trip(customer_id) 
            values(
                :customer_id
            )", array(
                ":customer_id" => $_POST['customer_id']
            ));
            
            $trip_id = $db->lastInsertId();
        } catch (\Exception $e) {
            return new Response(500, "Error, trip could not be created > " . $e->getMessage());
        }
        
        return new Response(200, "Trip $trip_id created!");
    }

    public function getTrip()
    {
        $trip_id = $_GET['trip_id'];
        
        $db = Database::getInstance();
        
        $trip = $db->queryFetch("select * from tb_trip where id=:trip_id", array(
            ":trip_id" => $trip_id
        ))[0];
        
        if (! $trip["id"]) {
            return new Response(404, "Trip $trip_id not found");
        }
        
        $flights = $db->queryFetch("select airport_origin, airport_destination, departure_date
            from tb_flight 
            where trip_id=:trip_id", array(
            ":trip_id" => $trip_id
        ));
        
        $trip['flights'] = $flights;
        
        return new Response(200, $trip);
    }
}