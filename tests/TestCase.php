<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * String date for testing now.
     */
    private string $now = '2023-09-06 00:00:00';

    /**
     * The URI request.
     */
    protected string $url = '';

    /**
     * Setup environment testing.
     */
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow($this->now);
    }

    /**
     * Clean up the testing environment before the next test.
     */
    public function tearDown(): void
    {
        Carbon::setTestNow(); // clear mock

        parent::tearDown();
    }

    /**
     * GET request, expecting a JSON response.
     */
    protected function getJsonSuccess(string $prefix = '', array $data = [], array $headers = []): TestResponse
    {
        return $this->json(
            method: 'GET',
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        )->assertSuccessful();
    }

    /**
     * POST request, expecting a JSON response.
     */
    protected function postJsonSuccess(array $data = [], $headers = []): TestResponse
    {
        return $this->postJson(
            uri: $this->url,
            data: $data,
            headers: $headers,
        )->assertSuccessful();
    }

    /**
     * POST request, expecting a JSON response.
     * when status 422 <validation errors>
     */
    protected function postJsonValidationErrors(
        array $data = [],
        array $messages = [],
        array $headers = []
    ): TestResponse {
        return $this->postJson(uri: $this->url, data: $data, headers: $headers)
            ->assertStatus(422)
            ->assertJsonValidationErrors($messages);
    }

     /**
     * PUT request, expecting a JSON response.
     */
    protected function putJsonSuccess(array $data = [], string $prefix = '', array $headers = []): TestResponse
    {
        return $this->putJson(
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        )->assertSuccessful();
    }

     /**
     * PUT request, expecting a JSON response.
     * when status 422 <validation errors>
     */
    protected function putJsonValidationErrors(
        array $data = [],
        string $prefix = '',
        array $messages = [],
        array $headers = []
    ): TestResponse {
        return $this->putJson(uri: $this->url . "/$prefix", data: $data, headers: $headers)
            ->assertStatus(422)
            ->assertJsonValidationErrors($messages);
    }

     /**
     * DELETE request, expecting a JSON response.
     */
    protected function deleteJsonSuccess(string $prefix = '', array $data = [], $headers = []): TestResponse
    {
        return $this->deleteJson(
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        )->assertSuccessful();
    }
}
