<?php



require_once 'Controllers\Controller.php';
require_once 'Logger\Logger.php';
require_once 'Models\Status.php';

use Controllers\Controller;
use Logger\Logger;
use Models\Status;

class StatusController extends Controller
{
    
    public function handle_api()
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
        
        $this->setHttpHeaders("application/json", $statusCode);
        
        $response = $this->encodeJson($rawData);
        
        //Logger::Debug("statuscode=".$statusCode);
        //Logger::Debug(gettype($response));
        //Logger::Debug(print_r($response, true));
        
        return $response;
    }
};


//call 
echo (new StatusController())->handle_api();