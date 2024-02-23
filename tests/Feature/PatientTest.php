<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PatientTest extends TestCase
{

    /** @test **/
    public function it_should_detected_patients_table_on_application_core(): void
    {
        $database = DB::connection()->getSchemaBuilder()->hasTable('patients');
        $this->assertTrue($database);
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
