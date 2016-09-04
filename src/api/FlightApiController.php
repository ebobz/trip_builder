<?php
namespace TripBuilder\Api;

use TripBuilder\Util\Database;
use TripBuilder\Model\Response;

class FlightApiController extends ApiController
{

    function __construct()
    {
        $this->register(ApiController::POST, "/flight", "addFlight");
        $this->register(ApiController::DELETE, "/flight", "removeFlight");
    }

    public function addFlight()
    {
        $db = Database::getInstance();
        
        try {
            $db->query("
            insert into tb_flight(trip_id, airport_origin, airport_destination, departure_date) 
            values(
                :trip_id,
                :airport_origin,
                :airport_destination,
                :departure_date
            )", array(
                ":trip_id" => $_REQUEST['trip_id'],
                ":airport_origin" => $_POST['airport_origin'],
                ":airport_destination" => $_POST['airport_destination'],
                ":departure_date" => $_POST['departure_date']
            ));
        } catch (\Exception $e) {
            return new Response(500, "Error, flight could not be added to this trip > " . $e->getMessage());
        }
        
        return new Response(200, "Flight added!");
    }

    public function removeFlight()
    {
        $db = Database::getInstance();
        
        try {
            
            $trip_found = $db->queryFetch("select * from tb_trip
                where id = :trip_id", array(
                ":trip_id" => $_REQUEST["trip_id"]
            ))[0];
            
            if (! $trip_found["id"]) {
                return new Response(404, "Trip not found!");
            }
            
            $flight_params = array(
                ":trip_id" => $_REQUEST['trip_id'],
                ":airport_origin" => $_REQUEST['airport_origin'],
                ":airport_destination" => $_REQUEST['airport_destination'],
                ":departure_date" => $_REQUEST['departure_date']
            );
            
            $flight_found = $db->queryFetch("select * from tb_flight 
                where trip_id = :trip_id 
                and airport_origin = :airport_origin
                and airport_destination = :airport_destination
                and departure_date = :departure_date", $flight_params)[0];
            
            if ($flight_found["trip_id"]) {
                
                $db->query("delete from tb_flight 
                where trip_id = :trip_id 
                and airport_origin = :airport_origin
                and airport_destination = :airport_destination
                and departure_date = :departure_date", $flight_params);
                
                return new Response(200, "Flight removed");
            } else {
                return new Response(404, "Flight not found!");
            }
        } catch (\Exception $e) {
            return new Response(500, "Error, flight could not be removed > " . $e->getMessage());
        }
        
        return new Response(200, "Flight $trip_id added!");
    }
}