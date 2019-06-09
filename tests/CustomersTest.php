<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCustomer()
    {
        $result = factory(App\Company::class, 2)->create();
        $this->assertTrue(true,$result);
    }
}
