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
        $this->postJson(route('api.import.patient'), [
            'file' => 'test.csv'
        ])
            ->assertValid();
    }

    /** @test **/
    public function it_should_return_file_is_required(): void
    {
        $this->postJson(route('api.import.patient'))
            ->assertJsonMissingValidationErrors('file')
            ->assertStatus(400);
    }


}
