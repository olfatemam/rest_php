<?php
namespace Models;

require_once 'Logger\Logger.php';

use Logger\Logger;

Class Status 
{
    private $status_array=array();
    //private $xml_obj;
    public $errors=array();
                  
    public function __construct($host, $port, $password)
    {
        $this->initialize($host, $port, $password);
    }
 
    //Api's:
    public function get_byname($name)
    {
        if (array_key_exists($name, $this->status_array)) 
        {
            return $this->status_array[$name];
        }
        $this->errors[]="Error: node '".name."' not found";
        return ["data"=>'empty'];
    }
    public function get_all()
    {
        Logger::Debug("get_all function: return the status_array");
        return $this->status_array;
    }
    
    private function initialize($host, $port, $password)
    {
        Logger::Debug("initialize Start(" . $host. ',' . $port . ',' . $password . ')=>');
        try 
        {            
            //checking if host name is a subfolder from the main host
            $delimiters=["/","\\"];
            Logger::Debug('host='.$host);
            
            $ready = str_replace($delimiters, $delimiters[0], $host);
            
            $parts = explode($delimiters[0], $ready);
            //Logger::Debug(print_r($parts,true));
            
            if(count($parts)>0)
            {
                $parts[0].=":".$port;
                $host = implode("/", $parts);
            }
            else
            {
                $host.=':'.$port;
            }
                
            $query_uri  ="http://". $host .'/status.xml?password='.$password;

            $xml_content = $this->get_xml_contents($query_uri);
            
            if ($xml_content !== false)
            {
                $xml_obj = simplexml_load_string($xml_content);

                if($xml_obj ===false)
                {
                    $xml = explode("\n", $xml_content);

                    $this->errors[] = $this->xml_errors_tostring($xml);
                    Logger::Error(print_r($this->errors, true));
                }
                else 
                {
                    $json = json_encode($xml_obj);
                    $this->status_array = json_decode($json, TRUE);
                }
                Logger::Debug("initialize End=>true");
                return true;
            }
        }
        catch (Exception $ex)
        {
            $this->errors[]=$e->getMessage();
        }
        Logger::Debug("initialize End=>false");
        
        return false;
    }
    
    
    private function get_xml_contents($url)
    {
        Logger::Debug("get_xml_contents Start(" . $url . ')=>');

        $headers = get_headers($url);
        $code = substr($headers[0], 9, 3);
        if($code != "200")
        {
            $this->errors[]='status xml failed to read, returned error:'.$code;
            
            Logger::Debug(print_r($headers, true));
            Logger::Error(print_r($this->errors, true));
            Logger::Debug("get_xml_contents End=> false");
            return  false;
        }
        Logger::Debug("get_xml_contents End=> file_get_contents output");
        return file_get_contents($url);
    }

    
    function xml_errors_tostring($xml)
    {
        $errors = libxml_get_errors();

        foreach ($errors as $error) 
        {
            $this->errors[]=$this->xml_error_tostring($error, $xml);
        }
        libxml_clear_errors();
    }
    
    function xml_error_tostring($error, $xml)
    {
        $return  = $xml[$error->line - 1] . "\n";
        $return .= str_repeat('-', $error->column) . "^\n";

        switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }

    $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return $return;
    }
};
?>