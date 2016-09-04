<?php

/**
 * This is the main program, where all the magic happens
 * 
 * I used a customized version of swagger.io (I intented to build something more light that will easly run under a nano aws instance)
 *
 * HTTP calls will arrive here like this: GET /airport or GET /airport/YUL
 * the first portion (airport) will indicate wich file controller will be included (./api/AirportApiController.php)
 * the other parts will serve as filtering parameters or ID, the method will decide what to do with them.
 *
 */
use TripBuilder\Api;
use TripBuilder\Model;
use TripBuilder\Util\Logger;

require_once (__DIR__ . "/config.php");


Logger::info("new request comming");


// this will allow ajax calls from any domain
header("Access-Control-Allow-Origin: *");

// mod rewrite configuration will put all query parameters will be inside "query_path" _GET variable
$queryPath = $_GET['queryPath'];
unset($_GET['queryPath']);
unset($_REQUEST['queryPath']);

$queryParams = explode("/", $queryPath);

$controller = Api\Factory::buildController($queryParams);

if (! is_object($controller)) {
    Logger::error("could not find a controller to deal with this request ($queryPath)");
    $resp = new Model\Response(404, "Not found!");
    $resp->returnResponse();
}

/*
 * TODO: Add some security throught access token
 *
 * $accessToken = $_SERVER['HTTP_ACCESS_TOKEN'];
 * if(!$controller->auth($accessToken)){
 * $resp = new Model\Response(403, "Not authorized!");
 * $resp->returnResponse();
 * }
 */

// following the example above:
// "GET" "/airport" [YUL]
$resp = $controller->call($_SERVER['REQUEST_METHOD'], "/$queryPath", $queryParams);
if (is_object($resp)) {
    $resp->returnResponse();
} else {
    Logger::error("non object returned");
    $resp = new Model\Response(500, "Huston, we have a problem!!!");
    $resp->returnResponse();
}