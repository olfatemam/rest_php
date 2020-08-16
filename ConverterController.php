<?php


require_once 'Controllers\Controller.php';
require_once 'Logger\Logger.php';
require_once 'Models\Converter.php';



 
use Controllers\Controller;
use Logger\Logger;
use Models\Converter;

class ConverterController extends Controller
{
    private $url;
    
    public function __construct()
    {
        $config = include('Config/App.php');
        $this->url = $config['xml_api_url'];
        Logger::Debug($this->url);
    }
    
    public function convert_then_send_xml()
    {
        try
        {
            Logger::Debug("convert_then_send_xml Begin");

            $results_arry=array("StatusCode"=>200);

            if(count($_POST)>0)
            {
                Logger::Debug("calling converter");

                $converter = new Converter();

                $result = $converter->to_xml($_POST);

                if($result[0]==true)
                {
                    Logger::Debug("xml data created");
                    $results_arry = $this->post_to_xml_rest_api($converter->xml);
                }
                else
                {
                    Logger::Debug("could not create xml data");
                    $results_arry=["Result"=>'Error', "Error"=>"could not create xml data:".$result[1], "StatusCode"=>404];
                }
            }
            else
            {
                $results_arry=["Result"=>'Error', "Error"=>"Error: invalid input", "StatusCode"=>404];
            }
        }
        catch(\Exception $e)
        {
            $results_arry=["Result"=>'Error', "Error"=>$e->getMessage(), "StatusCode"=>404];
        }
        
        $this->setHttpHeaders("application/json", $results_arry["StatusCode"]);
       
        $response = $this->encodeJson($results_arry);
        
        
        Logger::Debug("response=");
        
        Logger::Debug($response);
        
        Logger::Debug("convert_then_send_xml End");
        
        return $response;
    }
            
    private function post_to_xml_rest_api($xml)
    {
        
        Logger::Debug("post_to_xml_rest_api Start");
        
        $headers = array(
            "Content-type: text/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );
        
        Logger::Debug('post_to_xml_rest_api calling this->__post('. $this->url. ', ' , $xml . ')');
        return $this->__post($this->url, $headers, $xml);
    }

    private function __post($url, $headers, $data)
    {
        Logger::Info("posing url:".$url . ' start:');
        
        $results_arry=array();
        
        //Logger::Debug("url=".$url);
        //Logger::Debug("data=".$data);
        
        if($this->does_url_exist($url)==false)
        {
            return ["Result"=>'Error', $url. ' does not exist', "StatusCode"=>404];
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result=curl_exec($ch);
        
        Logger::Debug(print_r($result, true));
        
        if($result==false)
        {
            if(curl_errno($ch))
            {
                $results_arry=["Result"=>'Error', "Error"=>curl_error($ch), "StatusCode"=>404];
            }
            else
                $results_arry=["Result"=>'Error', "Error"=>"unknown error", "StatusCode"=>404];
        }
        else
        {
            Logger::Info("curl_exec succeeded in sending xml to ".$url);
            $results_arry=["Result"=>'Success', "Error"=>"", "StatusCode"=>200];
            
        }
        curl_close($ch);
        
        Logger::Info("posing url end:");
        
        return $results_arry;
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

echo (new ConverterController())->convert_then_send_xml();