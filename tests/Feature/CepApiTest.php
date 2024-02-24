<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CepApiTest extends TestCase
{
    /** @test **/
    public function it_should_check_if_cep_get_route_exists(): void
    {
        $this->assertTrue(Route::has('api.cep.show'));
    }

    /** @test **/
    public function it_should_check_api_url_on_application_core(): void
    {
        $this->assertTrue(env('VIA_CEP_URL') !== null);
    }

    /** @test **/
    public function it_should_check_api_format_on_application_core(): void
    {
        $this->assertTrue(env('VIA_CEP_FORMAT') !== null);
    }

    /** @test **/
    public function it_should_check_api_url_format_on_application_core(): void
    {
        $this->assertStringContainsString('http', env('VIA_CEP_URL'));
    }

    /** @test **/
    public function it_should_check_api_format_format_on_application_core(): void
    {
        $this->assertStringContainsString('json', env('VIA_CEP_FORMAT'));
    }

    /** @test **/
    public function it_should_be_able_to_reach_api_and_get_a_valid_cep(): void
    {
        $cep = '01001000';
        $response = $this->get(route('api.cep.show', $cep));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /** @test **/
    public function it_should_be_able_to_reach_api_and_get_an_invalid_cep(): void
    {
        $cep = '00000000';
        $response = $this->get(route('api.cep.show', $cep));
        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    /** @test **/
    public function it_should_be_able_to_reach_api_and_get_an_invalid_cep_format(): void
    {
        $cep = '0000000';
        $response = $this->get(route('api.cep.show', $cep));
        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
    }

    /** @test **/
    public function it_should_be_able_to_reach_api_and_get_an_error(): void
    {
        $cep = '0000000';
        $response = $this->get(route('api.cep.show', $cep));
        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
    }

    /** @test **/
    public function it_should_not_allow_put_method(): void
    {
        $cep = '01001000';
        $response = $this->putJson(route('api.cep.show', $cep));
        $response->assertStatus(405);
    }

    /** @test **/
    public function it_should_not_allow_post_method(): void
    {
        $cep = '01001000';
        $response = $this->postJson(route('api.cep.show', $cep));
        $response->assertStatus(405);
    }

    /** @test **/
    public function it_should_not_allow_delete_method(): void
    {
        $cep = '01001000';
        $response = $this->deleteJson(route('api.cep.show', $cep));
        $response->assertStatus(405);
    }


}
