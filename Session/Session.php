<?php


/**
//task Create a class for session management {
//The library will handle operation of $_SESSION, and should
//- provide option to encrypt the session data prior storage (md5 is fine)
//- provide option to store, update and delete sessions via external api (use placeholder for an api with /fetch and /store resources in the uml)
//- should accept its variables via an associative array
//}
 * @author Olfat.Emam
 */
namespace Session;

class Session {
    private $id;//session id
    private $secretkey = 'secret_string';

//- create a session if absent at construction.
    public function __construct($use_encryption)
    {
        
        $status = session_status();
        if($status!= PHP_SESSION_ACTIVE)
        {
            //- provide option to store, update and delete sessions via external api (use placeholder for an api with /fetch and /store resources in the uml)
            //olfat: this is done by a class that inherets SessionHandlerInterface, it will be called automatically to manage storage, i chosed mango 

            ini_set('session.save_handler', 'files');
            if($use_encryption==true)
            {
                $handler = new EncryptedSessionHandler($this->secretkey);
                session_set_save_handler($handler, true);
            }
            else
            {
                $handler = new NativeSessionHandler();
                session_set_save_handler($handler, true);
            }
            session_start();
        }
         $this->id = session_id();
    }
    
//- delete a session on demand.
    public function delete() 
    {
        if ( session_status() == PHP_SESSION_ACTIVE )
        {
            session_destroy();
            unset( $_SESSION );
            return true;
        }
        return false;
    }

//- return the individual session variables on demand
   public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }
   
//- set, reset and unset the session variables on demand
    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }

    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;
    }
   
    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }
    
    public function encrypt($name)
    {
        $plaintext = $_SESSION[$name];
        
        $_SESSION[$name]=md5($plaintext);
        
        return $_SESSION[$name];
    }
    

    //- should accept its variables via an associative array
    public function add_data($associative_array)
    {
        foreach($associative_array as $this->secretkey=>$value)
        {
            $this->__set( $this->secretkey, $value );
        }
    }
//    public function openssl_encrypt($name)
//    {
//        $plaintext = $_SESSION[$name];
//        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
//        $iv = openssl_random_pseudo_bytes($ivlen);
//        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $this->secretkey, $options=OPENSSL_RAW_DATA, $iv);
//        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->secretkey, $as_binary=true);
//        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
//        
//        $_SESSION[$name]=$ciphertext;
//    }
//    
//    public function openssl_decrypt($name)
//    {
//        $value = $_SESSION[$name];
//        
//        $c = base64_decode($value);
//        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
//        $iv = substr($c, 0, $ivlen);
//        $hmac = substr($c, $ivlen, $sha2len=32);
//        $ciphertext_raw = substr($c, $ivlen+$sha2len);
//        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->secretkey, $options=OPENSSL_RAW_DATA, $iv);
//        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->secretkey, $as_binary=true);
//        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
//        {
//            $_SESSION[$name]=$original_plaintext;
//            return $_SESSION[$name];
//        }
//        return null; //should throw exception
//     }
};
