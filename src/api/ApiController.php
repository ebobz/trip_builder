<?php
namespace TripBuilder\Api;

use TripBuilder\Model\Response;
use TripBuilder\Util\Logger;

abstract class ApiController
{

    const GET = 'GET';

    const POST = 'POST';

    const PUT = 'PUT';

    const DELETE = 'DELETE';

    private $functions;

    function auth($access_token)
    {
        if ($access_token == 'trip_builder') {
            return true;
        }
        return false;
    }

    function register($http_method, $path, $function)
    {
        $this->functions[] = array(
            "http_method" => $http_method,
            "path" => $path,
            "function" => $function
        );
    }

    function call($http_method, $path, $args)
    {
        $path = str_replace("/", "\\/", $path);
        $func_to_call = "";
        // print_r($this->functions);
        // echo $path;
        foreach ($this->functions as $func) {
            if ($func['http_method'] == $http_method) {
                // echo "<br><br>($path | ".$func['path'].")<br><br>";
                if (preg_match("/^" . $path . "$/", $func['path'], $matches)) {
                    $function = $func['function'];
                    array_shift($matches);
                    if ($function && method_exists($this, $function)) {
                        // call function passing arguments if there is any within the regexp
                        return $this->$function($matches, $args);
                    }
                }
            }
        }
        // too bad this is an invalid call
        // for now i'll just return 404
        Logger::notice("controller found but no function related to it " . $path);
        $resp = new Response(404, "Not found!");
        $resp->returnResponse();
    }
}

