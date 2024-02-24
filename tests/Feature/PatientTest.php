<?php

namespace Tests\Feature;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PatientTest extends TestCase
{
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
    public function it_should_validate_patient_required_fields(): void
    {
        $this->postJson(route('api.store.patient'), [])
            ->assertJsonValidationErrors([
                'full_name',
                'mother_name',
                'birth_date',
                'cpf',
                'cns',
            ]);
    }

    /** @test **/
    public function it_should_validate_patient_address_required_fields(): void
    {
        $this->postJson(route('api.store.patient'), [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678909',
            'cns' => '123456789012345',
            'picture' => 'photo.jpg',
        ])
            ->assertJsonValidationErrors([
                'zip_code',
                'street',
                'number',
                'complement',
                'district',
                'city',
                'state',
            ]);
    }

    /** @test **/
    public function it_should_able_to_create_a_new_patient(): void
    {
        $this->withoutExceptionHandling();
        $this->postJson(route('api.store.patient'), [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678909',
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
            'cpf' => '12345678909',
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
        ]);
    }

    /** @test **/
    public function it_should_get_all_patients(): void
    {
        $this->withoutExceptionHandling();
        $this->getJson(route('api.index.patient'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test **/
    public function it_should_allow_to_delete_patient_from_database(): void
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $this->deleteJson(route('api.destroy.patient', $patient->id))
            ->assertJson([
                'message' => 'Patient deleted successfully!',
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }

    /** @test **/
    public function it_should_update_patient_information_on_database(): void
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create(['full_name' => 'Marcos Coelho']);
        $this->putJson(route('api.update.patient', $patient->id), [
            'full_name' => 'John Doe Updated',
            'mother_name' => 'Jane Doe',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678909',
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
                'message' => 'Patient updated successfully!',
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('patients', ['full_name' => 'John Doe Updated']);
    }

    /** @test **/
    public function it_should_show_a_patient_by_id(): void
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create(['full_name' => 'Marcos Coelho']);
        $this->getJson(route('api.show.patient', $patient->id))
            ->assertValid()
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test **/
    public function it_should_not_show_a_patient_by_invalid_id(): void
    {
        $this->getJson(route('api.show.patient', 999))
            ->assertJson([
                'message' => 'Validation failed.'
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test **/
    public function it_should_not_delete_a_patient_by_invalid_id(): void
    {
        $this->deleteJson(route('api.destroy.patient', 999))
            ->assertJson([
                'message' => 'Validation failed.'
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test **/
    public function it_should_not_update_a_patient_by_invalid_id(): void
    {
        $this->putJson(route('api.update.patient', 999), [])
            ->assertJson([
                'message' => 'Validation failed.'
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
