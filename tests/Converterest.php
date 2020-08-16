<?php

//require('vendor/autoload.php');

/**
 * Description of StatusTest
 *
 * @author Olfat.Emam
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
       protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'localhost/rest_php'
        ]);
    }
    public function testPost_convert()
    {
    
        $response = $this->client->post('/converter/api', [
                'json' => [
                    "from_msisdn" =>12345678910123,
                    "message"=> "(some asci or utf-8 string)", "to_msisdn"=> 12345678910123, 
                    "encoding"=>"utf-8",
                    "extra"=>[  "bool_field"=>true,
                                "string_field"=>"test_string",
                                "double_field"=>11.02,
                                "int_field"=>7,
                    ],
                ]
            ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals("Success", $data);
    }
    
}
