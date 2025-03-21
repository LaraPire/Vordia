<?php

namespace Rayiumir\Vordia\Tests\Feature;

use Tests\TestCase;

class VordiaTest extends TestCase
{
    /**
     * Test mobile form submission success via HTTP.
     */
    public function test_mobile_form_submission_success(): void
    {
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'mobile' => '1234567890',
        ]);

        $response->assertStatus(200);
    }
}
