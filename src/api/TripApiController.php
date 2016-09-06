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
        $customer_id = $_GET['customer_id'];
        
        $db = Database::getInstance();
        
        if ($_GET['trip_id']) {
            $where = "id=:trip_id";
            $params = array(
                ":trip_id" => $trip_id
            );
        } elseif ($_GET['customer_id']) {
            $where = "customer_id=:customer_id";
            $params = array(
                ":customer_id" => $customer_id
            );
        }
        
        $trips = $db->queryFetch("select * from tb_trip where $where", $params);
        
        if (! is_array($trips) || count($trips) == 0) {
            return new Response(404, "Trip $trip_id not found");
        }
        
        for ($i = 0; $i < count($trips); $i ++) {
            
            $flights = $db->queryFetch("select airport_origin, airport_destination, departure_date
            from tb_flight 
            where trip_id=:trip_id", array(
                ":trip_id" => $trips[$i]['id']
            ));
            
            $trips[$i]['flights'] = $flights;
        }
        return new Response(200, $trips);
    }
}