<?php declare(strict_types=1);

require('vendor/autoload.php');
require_once 'Logger/Logger.php';

use Logger\Logger;


use Guzzle\Http\Client;


use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client('http://localhost/rest_php');
    }

    public function testGet_statuses()
    {
        
        $request = $this->client->get('statuses?host=localhost&port=80&password=password');
        
        $response = $request->send();
        $data = $response->json();
        //echo gettype($data);
        //var_dump($data);

        $this->assertEquals(200, $response->getStatusCode());
        
        
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('status', $data);
        
    }
    


    public function testGet_bynames()
    {
        $this->Get_byname('version');
        $this->Get_byname('status');
        $this->Get_byname('boxes');
        $this->Get_byname('smscs');
    }

    private function Get_byname($name)
    {
        $request = $this->client->get('statuses?host=localhost&port=80&password=password&name='.$name);
        
        $response = $request->send();
        $data = $response->json();
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertArrayHasKey($name, $data);
    }
}
