<?php

require_once 'Controllers\Controller.php';
require_once 'Logger\Logger.php';
require_once 'Models\Converter.php';

use Controllers\Controller;
use Logger\Logger;
use Models\Converter;

class XmlStoreController extends Controller
{
    public function __construct()
    {
    }
    public function store_xml()
    {
        try
        {
            Logger::Debug("store_xml Begin:");
            Logger::Debug(print_r($_POST, true));
            Logger::Debug("********************************************");
            $results_arry=array("StatusCode"=>200);

            if(count($_POST)>0)
            {
                Logger::Debug("calling converter");

                $converter = new Converter();

                $results_array = $converter->store_xml($_POST);
            }
            else
            {
                $results_arry=["Result"=>'Error', "Error"=>"Error: no input to store", "StatusCode"=>404];
            }
        }
        catch(\Exception $e)
        {
            $results_arry=["Result"=>'Error', "Error"=>$e->getMessage(), "StatusCode"=>404];
        }
        
        $this->setHttpHeaders("application/json", 200);//$results_arry["StatusCode"]);
       
        $response = $this->encodeJson($results_arry);
        
        
        Logger::Debug("response=");
        Logger::Debug($response);
        
        Logger::Debug("store_xml End");
        
        return $response;
    }
            
};


//call 

echo (new XmlStoreController())->store_xml();