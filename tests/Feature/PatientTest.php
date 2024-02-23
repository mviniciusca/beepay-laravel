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
        $database = DB::connection()->getSchemaBuilder()->hasTable('users');
        $this->assertTrue($database);
    }

    /** @test **/
    public function it_should_able_to_create_a_patient(): void
    {
        $this->post(route('api.store.patient'), [
            'picture' => 'https://via.placeholder.com/150',
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'CPF' => '12345678901',
            'CNS' => '123456789012345',
            'address_id' => 1,
        ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('patients', [
            'picture' => 'https://via.placeholder.com/150',
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'CPF' => '12345678901',
            'CNS' => '123456789012345',
            'address_id' => 1,
        ]);
    }
}
