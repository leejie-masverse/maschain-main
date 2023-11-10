<?php

namespace App\Http\Helpers;


class RestAPI {

    protected $method;
    protected $request_data;

    public function __construct() {
        // Determine HTTP method
        $this->verifyMethod();
    }

    public function makeRequest($url, $data_string, $method = 'GET', $content_type = 'form', $basicAuthUsername = null) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($basicAuthUsername !== null) {
            curl_setopt($ch, CURLOPT_USERPWD, $basicAuthUsername);
        }

        if ($content_type == 'form-post') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );

            $data = $data_string;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }
        else {
            // GET Request via PHP Curl
            if ($method == 'GET') {
                curl_setopt($ch, CURLOPT_URL, $url . "?" . $data_string);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            } // POST Request via PHP Curl
            else if ($method == 'POST_QUERY') {
                curl_setopt($ch, CURLOPT_URL, $url . "?" . $data_string);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            } // POST Request via PHP Curl
            else if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Determine the correct content type
                if ($content_type == "form") {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/x-www-form-urlencoded')
                    );
                }
                else if ($content_type == "json") {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string)
                        )
                    );
                }
                else if ($content_type == "xml") {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: text/xml',
                            'Content-Length: ' . strlen($data_string))
                    );
                }
                else {
                    //throw new \Exception('Unsupported Content Type');
                }
            }


            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                //throw new \Exception('CURL Error: ' . curl_errno($ch));
            }

            return $result;
        }
    }

    // Functions for Receiving RESTful API Requests
    public function getMethod() {
        return $this->method;
    }

    public function getRequestData() {
        return $this->request_data;
    }

    public function verifyMethod() {
        $request = request();
        $this->method = strtoupper($request->method());
        $this->request_data = $request->all();

        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new \Exception('Invalid HTTP request');
        }
    }
}
