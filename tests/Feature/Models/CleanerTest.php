<?php

namespace Tests\Feature\Models;

use Tests\TestCase;

class CleanerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }
}
