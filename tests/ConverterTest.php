<?php declare(strict_types=1);

require('vendor/autoload.php');
require_once 'Logger/Logger.php';

use Logger\Logger;


use Guzzle\Http\Client;


use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client('http://localhost/rest_php');
    }

    public function testPost_convert()
    {
        Logger::Debug("testPost_convert");
        $request = $this->client->post('converter_api', [],[
                    "from_msisdn" =>12345678910123,
                    "message"=> "(some asci or utf-8 string)", "to_msisdn"=> 12345678910123, 
                    "encoding"=>"utf-8",
                    "extra"=>[  "bool_field"=>[true, "boolean"],
                                "string_field"=>["test_string", "string"],
                                "double_field"=>[11.02,"double"],
                                "int_field"=>[7,"int"],
                            ]
                ]);
        
        
        $response = $request->send();
        
        echo ($response->getHeader('Content-Type'));
        
        $data = $response->json();

        $this->assertEquals(200, $response->getStatusCode());

        Logger::Debug(print_r($data, true));
        
        var_dump($data);
        
        //$response->getBody();
    }
}

