<?php

namespace Controllers;

use Logger\Logger;
use Resources\Status;

		
class StatusController extends Controller
{
    
    public static function handle_api()
    {
        $view = "";
        $rawData=null;
        
        if(!isset($_GET["view"]))return "";

        $view = $_GET["view"];
        $statusCode = 200;

        $status = new Status($_GET["host"], $_GET["port"], $_GET["password"]);

        if(count($status->errors)==0)
        {        
            switch($view){
                case "all":
                $rawData = $status->get_all();
                break;

                case "byname":
                case "by_name":
                    $rawData = $status->get_byname($_GET["port"]);
                break;

                case "" :
                        //404 - not found;
                    $rawData=null;
                    $status->errors[]="Api not found";
                break;
            }
        }
        if($rawData==null)
        {
            $statusCode = 404;
            $rawData = $status->errors;

        }
        else
        {
            $statusCode = 200;
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        
        $this->setHttpHeaders($requestContentType, $statusCode);
        
        $response = $this->encodeJson($rawData);
        
        Logger::Debug(print_r($rawData, true));
        
        echo $response;
    }
};


//call 
StatusController::handle_api();