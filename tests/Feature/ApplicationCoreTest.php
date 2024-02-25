<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicationCoreTest extends TestCase
{
    /** @test **/
    public function it_should_find_patients_table(): void
    {
        $this->assertTrue(Schema::hasTable('patients'));
    }

    /** @test **/
    public function it_should_find_patients_table_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('patients', 'id'));
        $this->assertTrue(Schema::hasColumn('patients', 'full_name'));
        $this->assertTrue(Schema::hasColumn('patients', 'mother_name'));
        $this->assertTrue(Schema::hasColumn('patients', 'birth_date'));
        $this->assertTrue(Schema::hasColumn('patients', 'cpf'));
        $this->assertTrue(Schema::hasColumn('patients', 'cns'));
        $this->assertTrue(Schema::hasColumn('patients', 'picture'));
    }

    /** @test **/
    public function it_should_find_addresses_table(): void
    {
        $this->assertTrue(Schema::hasTable('addresses'));
    }

    /** @test **/
    public function it_should_find_addresses_table_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('addresses', 'id'));
        $this->assertTrue(Schema::hasColumn('addresses', 'patient_id'));
        $this->assertTrue(Schema::hasColumn('addresses', 'zip_code'));
        $this->assertTrue(Schema::hasColumn('addresses', 'street'));
        $this->assertTrue(Schema::hasColumn('addresses', 'number'));
        $this->assertTrue(Schema::hasColumn('addresses', 'complement'));
        $this->assertTrue(Schema::hasColumn('addresses', 'district'));
        $this->assertTrue(Schema::hasColumn('addresses', 'city'));
        $this->assertTrue(Schema::hasColumn('addresses', 'state'));
    }

    /** @test **/
    public function it_should_find_patients_controller(): void
    {
        $this->assertTrue(class_exists('App\Http\Controllers\PatientController'));
    }

    /** @test **/
    public function it_should_find_patients_controller_store_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\PatientController', 'store'));
    }


    /** @test **/
    public function it_should_find_patients_controller_index_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\PatientController', 'index'));
    }

    /** @test **/
    public function it_should_find_patients_controller_update_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\PatientController', 'update'));
    }

    /** @test **/
    public function it_should_find_patients_controller_show_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\PatientController', 'show'));
    }


    /** @test **/
    public function it_should_find_patients_controller_destroy_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\PatientController', 'destroy'));
    }

    /** @test **/
    public function it_should_find_patients_model(): void
    {
        $this->assertTrue(class_exists('App\Models\Patient'));
    }

    /** @test **/
    public function it_should_find_addresses_model(): void
    {
        $this->assertTrue(class_exists('App\Models\Address'));
    }

    /** @test **/
    public function it_should_find_import_patient_controller(): void
    {
        $this->assertTrue(class_exists('App\Http\Controllers\ImportPatientController'));
    }

    /** @test **/
    public function it_should_find_import_patient_controller_import_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\ImportPatientController', 'import'));
    }

    /** @test **/
    public function it_should_find_patient_resource(): void
    {
        $this->assertTrue(class_exists('App\Http\Resources\PatientResource'));
    }

    /** @test **/
    public function it_should_find_cep_service_api_controller(): void
    {
        $this->assertTrue(class_exists('App\Http\Controllers\CepController'));
    }

    /** @test **/
    public function it_should_find_cep_service_api_controller_show_method(): void
    {
        $this->assertTrue(method_exists('App\Http\Controllers\CepController', 'show'));
    }
}
