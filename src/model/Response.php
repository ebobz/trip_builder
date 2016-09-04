<?php
namespace TripBuilder\Model;

/**
 * Will carry HTTP response, a HTTP RESPONSE CODE and a response object in json notation
 *
 * @author Elton
 */
class Response
{

    private $httpResponseCode;

    private $responseObject;

    /**
     * Will carry HTTP response to output
     *
     * @param int $httpResponseCode
     *            as specified by RFC 7231
     * @param mixed $responseObject
     *            can be anything and it will be transformed in JSON notation RFC 7159
     */
    function __construct($httpResponseCode, $responseObject)
    {
        $this->httpResponseCode = $httpResponseCode;
        $this->responseObject = $responseObject;
    }

    /**
     * Will set http response code header and will write the JSON
     * notation of the given response object
     */
    public function returnResponse()
    {
        http_response_code($this->httpResponseCode);
        $resp = array(
            "responseCode" => $this->httpResponseCode,
            "success" => ($this->httpResponseCode >= 200 && $this->httpResponseCode <= 299),
            "data" => $this->responseObject
        );
        echo json_encode($resp);
        exit();
    }
}