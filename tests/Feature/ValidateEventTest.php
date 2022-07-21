<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidateEventTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_mobile_page()
    {
        $response = $this->get('/mobile/find-events');

        $response->assertStatus(200);
    }

    //method for testing validation event is not complete then return 422 eror


    public function test_form_uncomplete()
    {
        $data_form = [[
            'name' => 'putri',
            'description' => 'ur code `re eror',
        ]];

        $response = $this->post('/api/organizer/event/validate', $data_form);
        $response->assertStatus(422);
    }
}
