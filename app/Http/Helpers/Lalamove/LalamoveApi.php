<?php


namespace App\Http\Helpers\Lalamove;

use App\Http\Helpers\Lalamove\LalamoveRequest as Request;


class LalamoveApi
{
    public $host = '';
    public $key = '';
    public $secret = '';

    public $country = '';

    /**
     * Constructor for Lalamove API
     *
     * @param $host - domain with http / https
     * @param $apikey - apikey lalamove provide
     * @param $apisecret - apisecret lalamove provide
     * @param $country - two letter country code such as HK, TH, SG
     *
     */
    public function __construct($host = "", $apiKey = "", $apiSecret = "", $country = "MY")
    {
        $this->host = $host;
        $this->key = $apiKey;
        $this->secret = $apiSecret;
        $this->country = $country;
    }

    /**
     * Make a http Request to get a quotation from lalamove API via guzzlehttp/guzzle
     *
     * @param $body{Object}, the body of the json
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function quotation($body)
    {
        $request = new Request();
        $request->method = "POST";
        $request->path = "/v2/quotations";
        $request->body = $body;
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }

    /**
     * Make a http request to place an order at lalamove API via guzzlehttp/guzzle
     *
     * @param $body{Object}, the body of the json
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function postOrder($body)
    {
        $request = new Request();
        $request->method = "POST";
        $request->path = "/v2/orders";
        $request->body = $body;
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }

    /**
     * Make a http request to get the status of order
     *
     * @param $orderId(String), the customerOrderId of lalamove
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function getOrderStatus($orderId)
    {
        $request = new Request();
        $request->method = "GET";
        $request->path = "/v2/orders/".$orderId;
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }

    /**
     * Make a http request to get the driver Info
     *
     * @param $orderId(String), the customerOrderId of lalamove
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function getDriverInfo($orderId, $driverId)
    {
        $request = new Request();
        $request->method = "GET";
        $request->path = "/v2/orders/".$orderId."/drivers/".$driverId;
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }

    /**
     * Make a http request to get the driver Location
     *
     * @param $orderId(String), the customerOrderId of lalamove
     * @param $driverId(String), the id of the driver at lalamove
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function getDriverLocation($orderId, $driverId)
    {
        $request = new Request();
        $request->method = "GET";
        $request->path = "/v2/orders/".$orderId."/drivers/".$driverId."/location";
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }

    /**
     * Cancel the http request to get the driver location
     *
     * @param $orderId(String), the customerOrderId of lalamove
     * @return the http response from guzzlehttp/guzzle, an exception will not be thrown
     *   2xx - http request is successful
     *   4xx - unsuccessful request, see body for error message and documentation for matching
     *   5xx - server error, please contact lalamove
     */
    public function cancelOrder($orderId)
    {
        $request = new Request();
        $request->method = "PUT";
        $request->path = "/v2/orders/".$orderId."/cancel";
        $request->host = $this->host;
        $request->key = $this->key;
        $request->secret = $this->secret;
        $request->country = $this->country;
        return $request->send();
    }
}
