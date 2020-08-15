<?php

require('vendor/autoload.php');

/**
 * Description of StatusTest
 *
 * @author Olfat.Emam
 */
class StatusTest extends PHPUnit_Framework_TestCase
{
       protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'localhost/rest_php'
        ]);
    }

    public function testGet_statuses()
    {
        $response = $this->client->get('/status/all', [
            'query' => [
                'host' => 'localhost/rest_php',
                'port' => 80,
                'password' => "password",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('gateway', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('author', $data);
    }
    
    public function testGet_bynames()
    {
        testGet_byname('gateway');
        testGet_byname('version');
        testGet_byname('status');
        testGet_byname('boxes');
        testGet_byname('box');
        testGet_byname('smscs');
    }

    public function testGet_byname($name)
    {
        $response = $this->client->get('/status/byname', [
            'query' => [
                'host' => 'localhost/rest_php',
                'port' => 80,
                'password' => "password",
                'name'=>$name
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey($name, $data);
    }
}
