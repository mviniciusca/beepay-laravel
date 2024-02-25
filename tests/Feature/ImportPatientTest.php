<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('local');
        $file = UploadedFile::fake()->create('test.csv', 0);

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertJsonMissingValidationErrors(['file']);

        Storage::fake()->delete('test.csv');
    }

    /** @test **/
    public function it_should_return_file_is_required(): void
    {
        $this->postJson(route('api.import.patient'))
            ->assertJsonValidationErrors(['file'])
            ->assertStatus(400);
    }

    /** @test **/
    public function it_should_not_accept_invalid_file_type(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertJsonValidationErrors(['file']);
    }

    /** @test **/
    public function it_should_accept_file(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('test.csv');

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertJsonMissingValidationErrors(['file']);
    }

    /** @test **/
    public function it_should_not_accept_empty_files_even_they_are_accepted_format(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('test.csv', 0);

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertStatus(400)
            ->assertJsonMissing(['message' => 'File imported successfully']);

        Storage::fake()->delete('test.csv');
    }

    /** @test **/
    public function it_should_not_process_file_without_header(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('test.csv', 'John Doe, Jane Doe, 1990-01-01, 12345678901, 123456789012345, picture.jpg, 12345678, street, 123, complement, district, city, state');

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertStatus(400)
            ->assertJsonMissing(['message' => 'File imported successfully']);

        Storage::fake()->delete('test.csv');
    }

    /** @test **/
    public function it_should_process_file_and_import_data_to_database()
    {
        $content = "full_name,mother_name,birth_date,cpf,cns,picture,zip_code,street,number,complement,district,city,state\n";
        $content .= "John Doe,Jane Doe,1990-01-01,12345678901,123456789012345,picture.jpg,12345678,street,123,complement,Downtown,Rio de Janeiro,RJ";

        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        $this->postJson(route('api.import.patient'), [
            'file' => $file
        ])
            ->assertStatus(200)
            ->assertJson(['message' => 'File imported successfully']);

        Storage::fake()->delete('test.csv');
    }

}
