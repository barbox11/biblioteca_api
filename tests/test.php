<?php

use PHPUnit\Framework\TestCase;

class LibreriaApiTest extends TestCase 
{
    private $baseUrl = 'http://localhost/libreria_api/Api.php';

    /**
     * @test
     */
    public function testConexionApi()
    {
        $response = file_get_contents($this->baseUrl);
        $data = json_decode($response, true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('status', $data);
    }
}