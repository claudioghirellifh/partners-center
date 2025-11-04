<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use InteractsWithViews;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
