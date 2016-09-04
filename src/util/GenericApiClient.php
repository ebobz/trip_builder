<?php
namespace TripBuilder\Util;

/**
 * A very simple multiporpouse API client
 * 
 * @author Elton
 *        
 */
class GenericApiClient
{

    /**
     * Make a HTTP request (with Curl)
     *
     * @param string $method
     *            can be (GET, POST, PUT, DELETE)
     * @param string $url
     *            complete URL of the resource including query string
     * @param string $body
     *            (optional) request body (when using POST or PUT)
     * @return string response body from URL
     */
    public function makeRequest($method, $url, $body = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // to be able to run in windows
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // to be able to run in windows
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        return curl_exec($curl);
    }
}