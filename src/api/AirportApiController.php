<?php
namespace TripBuilder\Api;

use TripBuilder\Model\Response;
use TripBuilder\IATA\IATA;

class AirportApiController extends ApiController
{

    function __construct()
    {
        $this->register(ApiController::GET, "/airport", "listAllAirports");
    }

    function listAllAirports()
    {
        $iata_api = new IATA();
        
        try {
            $airports = $iata_api->getAirports($_REQUEST['code']);
        } catch (Exception $e) {
            return new Response(500, $e->getMessage());
        }
        
        if (is_array($airports)) {
            return new Response(200, $airports);
        } else {
            return new Response(500, "error parsing response from iata api");
        }
    }
}