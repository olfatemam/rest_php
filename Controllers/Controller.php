<?php 
/*
A simple RESTful webservices base class
Use this as a template and build upon it
*/
namespace Controllers;

class Controller {
	
    private $httpVersion = "HTTP/1.1";

    public function encodeJson($responseData) {
        $jsonResponse = json_encode($responseData);
        return $jsonResponse;		
    }

    public function setHttpHeaders($contentType, $statusCode)
    {
        $statusMessage = $this -> getHttpStatusMessage($statusCode);
        header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
        header("Content-Type: ". $contentType);
    }

    public function getHttpStatusMessage($statusCode){
            $httpStatus = array(
                    100 => 'Continue',  
                    101 => 'Switching Protocols',  
                    200 => 'OK',
                    201 => 'Created',  
                    202 => 'Accepted',  
                    203 => 'Non-Authoritative Information',  
                    204 => 'No Content',  
                    205 => 'Reset Content',  
                    206 => 'Partial Content',  
                    300 => 'Multiple Choices',  
                    301 => 'Moved Permanently',  
                    302 => 'Found',  
                    303 => 'See Other',  
                    304 => 'Not Modified',  
                    305 => 'Use Proxy',  
                    306 => '(Unused)',  
                    307 => 'Temporary Redirect',  
                    400 => 'Bad Request',  
                    401 => 'Unauthorized',  
                    402 => 'Payment Required',  
                    403 => 'Forbidden',  
                    404 => 'Not Found',  
                    405 => 'Method Not Allowed',  
                    406 => 'Not Acceptable',  
                    407 => 'Proxy Authentication Required',  
                    408 => 'Request Timeout',  
                    409 => 'Conflict',  
                    410 => 'Gone',  
                    411 => 'Length Required',  
                    412 => 'Precondition Failed',  
                    413 => 'Request Entity Too Large',  
                    414 => 'Request-URI Too Long',  
                    415 => 'Unsupported Media Type',  
                    416 => 'Requested Range Not Satisfiable',  
                    417 => 'Expectation Failed',  
                    500 => 'Internal Server Error',  
                    501 => 'Not Implemented',  
                    502 => 'Bad Gateway',  
                    503 => 'Service Unavailable',  
                    504 => 'Gateway Timeout',  
                    505 => 'HTTP Version Not Supported');
            return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
    }

    public function does_url_exist($url)
    {
        $ch = curl_init($url); 
        // Request method is set 
        curl_setopt($ch, CURLOPT_NOBODY, true); 

        // Executing cURL session 
        curl_exec($ch); 

        // Getting information about HTTP Code 
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch); 

        // Testing for 404 Error 
        if($retcode != 200) 
        { 
            return false; 
        } 
        else { 
            return true; 
        } 
    }
}
?>