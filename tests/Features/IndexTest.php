<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex_accessed_without_credential()
    {
        $this->get('/');
        $this->assertEquals(
            'Unauthorized', $this->response->getContent()
        );
    }
}
