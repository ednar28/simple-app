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
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow($this->now);
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        Carbon::setTestNow(); // clear mock

        parent::tearDown();
    }

    /**
     * GET request, expecting a JSON response.
     */
    public function customGetJson(string $prefix = '', array $data = [], array $headers = []): TestResponse
    {
        return $this->json(
            method: 'GET',
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        );
    }

    /**
     * POST request, expecting a JSON response.
     */
    public function customPostJson(array $data = [], $headers = []): TestResponse
    {
        return $this->postJson(
            uri: $this->url,
            data: $data,
            headers: $headers,
        );
    }

     /**
     * PUT request, expecting a JSON response.
     */
    public function customPutJson(array $data = [], string $prefix = '', $headers = []): TestResponse
    {
        return $this->putJson(
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        );
    }

     /**
     * DELETE request, expecting a JSON response.
     */
    public function customDeleteJson(string $prefix = '', array $data = [], $headers = []): TestResponse
    {
        return $this->deleteJson(
            uri: $this->url . "/$prefix",
            data: $data,
            headers: $headers,
        );
    }
}
