<?php

namespace Controllers;

use Logger\Logger;
use Resources\Status;

/*
task create a converter api {
The intended exposed api POST http://url.com/exposed/api is 
serving for the incoming message flow. The api expects json format in the request body. The minimum expected required fields by the api are: from_msisdn (i.e 12345678910123), message (asci or utf-8 string), to_msisdn (i.e 12345678910123), encoding.
Optionally the caller may also add extra fields where those fields are 
 * mentioned in a reserved key field_map, an object w
here keys are the name of extra fields and values are string having one of (integer, string, boolean, float)
The api upon receiving the request should parse and handle exceptions, if any. 
If no exceptions then the api will call another rest api at 
POST http:/transter.to/api/xml to transfer the incoming request in an XML format in a 
<request></request> root enveloppe where the incoming json keys to be xml tags with field type attributes.

All steps should be recorded in a nosql database,
 
for audit trail purposes, over the rest api with simple post http://db.com/query having the generic sql recognition in the body and the database respond 
 * with HTML response codes and the result in the body as json document.
(i.e request: select from table where date<"2020-12-11" response [{"id"=>1,"name"=>"john"},{"id"=>2,"name":"marry"}]

 */		
class ConverterController extends Controller
{
    private $url="http:/transter.to/api/xml";
    
    public function __construct()
    {
    
        
    }

    function post_xml($xml)
    {
        $headers = array(
            "Content-type: text/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($ch); 
        
        if(curl_errno($ch))
            print curl_error($ch);
        else
            curl_close($ch);   
    }
    function post_json($arrayData)
    {
        $ch = curl_init($this->url);
        
        $jsonDataEncoded = json_encode($arrayData);

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

        //Execute the request
        $result = curl_exec($ch);
        
        if(curl_errno($ch))
            print curl_error($ch);
        else
            curl_close($ch);
        
        return $result;
    }

    public static function convert()
    {
        $input = json_decode($_POST[0]);
        if($input!=null)
        {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .='<request>';

            $xml .='<from_msisdn>'  .   $input["from_msisdn"]   .'</from_msisdn>';
            $xml .='<message>'      .   $input["message"]       .   '</message>';
            $xml .='<to_msisdn>'    .   $input["to_msisdn"]     .   '</to_msisdn>';
            $xml .='<encoding>'     .   $input["encoding"]      .   '</encoding>';
            if(isset($input["extra"]))
            foreach($input["extra"] as $key=>$value)
            {
               $xml .='<' .$key . 'type='. gettype($value). '>'.$value.'</'.$key.'>';
            }
            $xml .='</request>';
        
            return $this->post_xml($xml);   
        }
        return null;
    }
};


//call 
ConverterController::convert();