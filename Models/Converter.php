<?php


namespace Models;
require_once 'Logger\Logger.php';

use Logger\Logger;

Class Converter 
{
    public $xml="";
    
    public $errors=array();
                  
    public function __construct()
    {
        Logger::Info("Converter constructor");
    }
    
    public function to_xml($input)
    {
        Logger::Info("Converter to xml");
        try
        {
            $this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $this->xml .='<request>';

            $this->xml .='<from_msisdn>'  .   $input["from_msisdn"]   .'</from_msisdn>';
            $this->xml .='<message>'      .   $input["message"]       .   '</message>';
            $this->xml .='<to_msisdn>'    .   $input["to_msisdn"]     .   '</to_msisdn>';
            $this->xml .='<encoding>'     .   $input["encoding"]      .   '</encoding>';
            if(isset($input["extra"]))
            foreach($input["extra"] as $key=>$value)
            {
               $this->xml .='<' .$key . ' type="'. $value[1]. '">'.$value[0].'</'.$key.'>';
            }
            $this->xml .='</request>';

            Logger::Debug("xml=");
            Logger::Debug($this->xml);

            return [true, ""];
        }
        catch(\Exception $e)
        {
            Logger::Error($e->getMessage());
            return [false, $e->getMessage()];
        }
    }
};
?>