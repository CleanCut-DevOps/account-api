<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_application_base_route_returns_404()
    {
        $response = $this->get('/');

        $response->assertStatus(404)->assertJsonStructure(['type', 'message']);
    }
}
