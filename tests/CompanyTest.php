<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompnay()
    {
        $result = factory(App\Company::class, 2)->create()->each(function ($company) {
            $company->customers()->save(factory(App\Customers::class)->make());
        });
        $this->assertTrue(true,$result);
    }
}
