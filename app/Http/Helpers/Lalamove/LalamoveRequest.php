<?php


namespace App\Http\Helpers\Lalamove;


use function hash_hmac;
use function json_encode;
use function time;
use function uniqid;

class LalamoveRequest
{
    public $method = "GET";
    public $body = array();
    public $host = '';
    public $path = '';
    public $header = array();

    public $key = '';
    public $secret = '';
    public $country = '';

    public $ch = null;

    /**
     * Create the signature for the
     * @param $time, time to create the signature (should use current time, same as the Authorization timestamp)
     *
     * @return a signed signature using the secret
     */
    public function getSignature($time)
    {
        $_encryptBody = '';
        if ($this->method == "GET") {
            $_encryptBody = $time."\r\n".$this->method."\r\n".$this->path."\r\n\r\n";
        } else {
            $_encryptBody = $time."\r\n".$this->method."\r\n".$this->path."\r\n\r\n".json_encode((object)$this->body);
        }
        return hash_hmac("sha256", $_encryptBody, $this->secret);
    }

    /**
     * Build and return the header require for calling lalamove API
     * @return {Object} an associative aray of lalamove header
     */
    public function buildHeader()
    {
        $time = time() * 1000;
        return [
            "X-Request-ID" => uniqid(),
            "Content-type" => "application/json; charset=utf-8",
            "Authorization" => "hmac ".$this->key.":".$time.":".$this->getSignature($time),
            "Accept"=> "application/json",
            "X-LLM-Country"=> $this->country
        ];
    }

    /**
     * Send out the request via guzzleHttp
     * @return return the result after requesting through guzzleHttp
     */
    public function send()
    {
        $client = new \GuzzleHttp\Client();

        $content = [
            'headers' => $this->buildHeader(),
            'http_errors' => false
        ];
        if ($this->method != "GET") {
            $content['json'] = (object)$this->body;
        }

        return $client->request($this->method, $this->host.$this->path, $content);
    }
}

