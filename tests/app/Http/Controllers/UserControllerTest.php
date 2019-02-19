<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->post('/users', [
            'name' => 'Human Person',
            'email' => 'hperson@universe.com'
        ])->seeStatusCode(201);
 
        $response = json_decode($this->response->getContent());

        $this->assertEquals("The user with id 11 has been successfully created.", $response->message);
    }
}
