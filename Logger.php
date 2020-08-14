<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and ope
 * 
 * 
 * n the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author Olfat.Emam
 */
class Logger {
    
    private static $LoggingLevel=LOG_ERR|LOG_WARNING|LOG_INFO|LOG_DEBUG;
    
    public static function set_logging_level($level)
    {
        self::$LoggingLevel=$level;
    }
    
    //put your code here
    public static function Error($msg)
    {
        if( self::$LoggingLevel & LOG_ERR )
           return self::message("Error", $msg);
        return true;
    }
    
    public static function Warning($msg)
    {
        if( self::$LoggingLevel & LOG_WARNING )
            return self::message("WARNING", $msg);
    
        return true;
    }
    
    public static function Info($msg)
    {
        if( self::$LoggingLevel & LOG_INFO )
            return self::message("INFO", $msg);
        return true;
    }
    public static function Debug($msg)
    {
        if( self::$LoggingLevel & LOG_DEBUG )
            return self::message("Debug", $msg);
        return true;
    }
    
    private static function message($level, $msg)
    {
        $log="\n". date("Y-m-d H:i:s") . '=>' . $level ."=>" . $msg;
        
        return file_put_contents("logfile", $log, FILE_APPEND);
    }
}
