<?php
require_once("SimpleRest.php");
require_once("Status.php");
		
class StatusRestHandler extends SimpleRest {

	public function getStatus($host, $port, $password, $name)
        {
            $statusCode = 200;
            $status = new Status($host, $port, $password);
            if(count($status->errors)==0)
            {
                $rawData = $status->get_byname($name);
                if($rawData==null)
                {
                    $statusCode = 404;
                    $rawData = $status->errors;
                }
                else
                {
                    $statusCode = 200;
                }
            }
            else 
            {
                $statusCode = 404;
                $rawData = $status->errors;
            }
                
            $requestContentType = $_SERVER['HTTP_ACCEPT'];
            $this ->setHttpHeaders($requestContentType, $statusCode);
            $response = $this->encodeJson($rawData);
            echo $response;
	
	}
	function getAllStatuses($host, $port, $password) {	

            $status = new Status($host, $port, $password);
            if(count($status->errors)==0)
            {
                $rawData = $status->get_all();
                if($rawData==null)
                {
                    $statusCode = 404;
                    $rawData = $status->errors;
                }
                else
                {
                    $statusCode = 200;
                }
            }
            else 
            {
                $statusCode = 404;
                $rawData = $status->errors;
            }
                
            $requestContentType = $_SERVER['HTTP_ACCEPT'];
            $this ->setHttpHeaders($requestContentType, $statusCode);
            $response = $this->encodeJson($rawData);
            Logger::Info(print_r($rawData, true));
            echo $response;
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
}
?>