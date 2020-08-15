<?php

/*
task create a converter api {
The intended exposed api POST http://url.com/exposed/api is serving for the incoming message flow. 
 * The api expects json format in the request body. 
 * 
 * The minimum expected required fields by the api are: 
 * from_msisdn (i.e 12345678910123), 
 * message (asci or utf-8 string), 
 * to_msisdn (i.e 12345678910123), 
 * encoding.
Opt
 * ionally the caller may also add extra fields where those fields are mentioned in a reserved key field_map, 
 * an object where keys are the name of extra fields and values are string having one of (integer, string, boolean, float)
The api upon receiving the request should parse and handle exceptions, if any. 
 * If no exceptions then the api will 
 * call another rest api at POST 
 * http:/transter.to/api/xml to transfer the incoming request in an XML format in a 
 * <request></request> 
 * root enveloppe where the incoming json keys to be xml tags with field type attributes.
 * 
 * 
 * https://www.php.net/manual/en/set.mongodb.php
 
All steps should be recorded in a nosql database,
 * for audit trail purposes, over the rest api with simple post http://db.com/query having the generic sql recognition in the body and the database respond with HTML response codes and the result in the body as json document.
(i.e request: select from table where date<"2020-12-11" response [{"id"=>1,"name"=>"john"},{"id"=>2,"name":"marry"}]
The api will be hosted behind an apache server on a single cpu 1GB mikro-server which can handle 20 transactions per second at 90% load for a full lifecycle process if implemented over a single threaded jobs. There is no clustering and the high availability is provided with a smart load balancer in front of the server.
 * 
 *  */

/**
 * Description of Exposed
 *
 * @author Olfat.Emam
 */
//$options = array(
//  'http' => array(
//    'method'  => 'POST',
//    'content' => json_encode( $data ),
//    'header'=>  "Content-Type: application/json\r\n" .
//                "Accept: application/json\r\n"
//    )
//);
//
//$context  = stream_context_create( $options );
//$result = file_get_contents( $url, false, $context );
//$response = json_decode( $result );

class Exposed {
    
    public function __construct($from_msisdn, $message, $to_msisdn, $encoding)
    {
        ;
    }
    //put your code here
    // add a record
    //$document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
    
    public function connect($array)
    {
        $this->m = new MongoClient();
        // select a database
        $this->db = $m->incoming;
        //$collection = $db->messages;

    }
    public function insert($collection, $array)
    {
        $collection->insert($document);
    }
}


// connect



// add a record
$document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
$collection->insert($document);

// add another record, with a different "shape"
$document = array( "title" => "XKCD", "online" => true );
$collection->insert($document);

// find everything in the collection
$cursor = $collection->find();

// iterate through the results
foreach ($cursor as $document) {
    echo $document["title"] . "\n";
}

