<?php
namespace Models;

use Logger\Logger;

Class Converter 
{
    public $xml="";
    
    public $errors=array();
                  
    public function __construct()
    {
    }
    
    public function to_xml($input)
    {
        $$this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $$this->xml .='<request>';

        $this->xml .='<from_msisdn>'  .   $input["from_msisdn"]   .'</from_msisdn>';
        $this->xml .='<message>'      .   $input["message"]       .   '</message>';
        $this->xml .='<to_msisdn>'    .   $input["to_msisdn"]     .   '</to_msisdn>';
        $this->xml .='<encoding>'     .   $input["encoding"]      .   '</encoding>';
        if(isset($input["extra"]))
        foreach($input["extra"] as $key=>$value)
        {
           $this->xml .='<' .$key . 'type='. gettype($value). '>'.$value.'</'.$key.'>';
        }
        $this->xml .='</request>';

        Logger::Debug("\nxml=\n".$this->xml);

        return true;
    }
};
?>