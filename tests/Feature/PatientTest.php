<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_should_detects_patients_table_on_application_core(): void
    {
        $database = DB::connection()->getSchemaBuilder()->hasTable('patients');
        $this->assertTrue($database);
    }

    /** @test **/
    public function it_should_detects_addresses_table_on_application_core(): void
    {
        $database = DB::connection()->getSchemaBuilder()->hasTable('addresses');
        $this->assertTrue($database);
    }

    /** @test **/
    public function it_should_find_api_post_route(): void
    {
        $this->assertTrue(Route::has('api.store.patient'));
    }

    /** @test **/
    public function it_should_deny_access_to_post_route(): void
    {
        $this->getJson(route('api.store.patient'))
            ->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /** @test **/
    public function it_should_able_to_create_a_new_patient(): void
    {
        $this->withoutExceptionHandling();
        $this->postJson(route('api.store.patient'), [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678901',
            'cns' => '123456789012345',
            'picture' => 'photo.jpg',
            'zip_code' => '12345678',
            'street' => 'Main Street',
            'number' => '123',
            'complement' => 'Near the park',
            'district' => 'Downtown',
            'city' => 'Big City',
            'state' => 'BC',
        ])
            ->assertValid()
            ->assertJson([
                'message' => 'Patient created successfully!',
            ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('patients', [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678901',
            'cns' => '123456789012345',
            'picture' => 'photo.jpg',
        ]);

        $this->assertDatabaseHas('addresses', [
            'zip_code' => '12345678',
            'street' => 'Main Street',
            'number' => '123',
            'complement' => 'Near the park',
            'district' => 'Downtown',
            'city' => 'Big City',
            'state' => 'BC',
            'patient_id' => 1,
        ]);
    }
}
