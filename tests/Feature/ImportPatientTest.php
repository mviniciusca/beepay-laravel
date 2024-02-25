<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportPatientTest extends TestCase
{
    /** @test **/
    public function it_should_find_api_route_to_upload(): void
    {
        $this->assertTrue(Route::has('api.import.patient'));
    }

    /** @test **/
    public function it_should_allow_to_upload_a_csv_file(): void
    {
        $response = $this->postJson(route('api.import.patient'), [
            'file' => 'file.csv'
        ]);

        $response->assertStatus(200);
    }

}
