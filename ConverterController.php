<?php

namespace Controllers;

use Logger\Logger;
use Models\Converter;

class ConverterController extends Controller
{
    private $url;
    
    public function __construct($url="http:/transter.to/api/xml")
    {
        $this->url = $url;
    }
    
    public function convert_then_send_xml()
    {
        $input = json_decode($_POST[0], false, 512, JSON_BIGINT_AS_STRING);
        if($input)
        {
            $converter = new Converter();
        
            if($converter->to_xml($input)==true)
            {
                $result = $this->post_to_xml_rest_api($converter->xml);
                $statusCode=($result==true)?200:404;
            }
            else
            {
                $statusCode=404;
                $result="Error: could not create xml data.";
            }
        }
        else
        {
            $statusCode=404;
            $result="Error: invalid input";
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
        $response = $this->encodeJson($result);
        Logger::Debug(print_r($rawData, true));
        return $response;
    }
            
    private function post_to_xml_rest_api($xml)
    {
        $headers = array(
            "Content-type: text/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );
        return $this->__post($this->url, $headers, $xml);
    }

    private function __post($url, $headers, $data)
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POST, 1);//Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//Set the content type to application/json
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);//Execute the request

        if($result==false)
        {
            if(curl_errno($ch))
            {
                $rawData = 'Error:' . curl_error($ch);
                $statusCode = 404;
                return false;
            }
        }
        else
        {
            $rawData="Success";
            $statusCode = 200;
            curl_close($ch);
            return true;
        }
        
    }

    function post_json($arrayData)
    {
        $jsonDataEncoded = json_encode($arrayData);
        $headers = array(
            'Content-Type: application/json'
        );
        return $this->__post($this->url, $headers, $jsonDataEncoded);
    }
    
};


//call 
(new ConverterController())->convert_then_send_xml();