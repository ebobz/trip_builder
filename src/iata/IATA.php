<?php
namespace TripBuilder\IATA;

use TripBuilder\Util\GenericApiClient;
use TripBuilder\Util\Logger;

class IATA extends GenericApiClient
{
    
    // elton's key, this should be in a config file or something...
    private $api_key = '5f9bc51b-1d83-4d26-957c-71f9327ae741';

    /**
     * Will get the list of all airports in the world
     * 
     * @param string $filter_code            
     * @throws Exception
     * @return object (JSON decoded output)
     */
    public function getAirports($filter_code = '')
    {
        
        // @todo Should be considered some caching to avoid abuses
        $returned = $this->makeRequest("GET", "https://iatacodes.org/api/v6/airports?api_key=" . $this->api_key . "&code=" . $filter_code);
        
        $json = json_decode($returned, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Logger::emergency("iatacodes.org is not returning a valid JSON");
            throw new Exception("coult not retreive airport list, invalid json response from iata api");
        }
        
        $airports = array();
        // can do some processing / validation here...
        foreach ($json["response"] as $j) {
            $airports[] = array(
                "code" => $j['code'],
                "name" => $j['name'],
                "country" => $j['country_code']
            );
        }
        
        return $airports;
    }
}