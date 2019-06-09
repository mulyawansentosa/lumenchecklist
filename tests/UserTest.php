<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Controllers\AuthController;


class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $data['email']      = 'mulyawan@flazhost.com';
        $data['password']   = 'sluhme';

        $this->post('/login',$data);
        $this->assertTrue(
            true, $this->response
        );
    }
}
