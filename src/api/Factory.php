<?php
namespace TripBuilder\Api;

use TripBuilder\Util\Logger;

class Factory
{

    static function buildController($queryPath)
    {
        switch ($queryPath[0]) {
            case "airport":
                return new AirportApiController();
            
            case "trip":
                return new TripApiController();
            
            case "flight":
                return new FlightApiController();
        }
        
        Logger::notice("no controller configured for ".$queryPath[0]);
        
        return null;
    }
}
