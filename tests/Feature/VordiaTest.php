<?php

namespace Rayiumir\Vordia\Tests\Feature;

use Tests\TestCase;

class VordiaTest extends TestCase
{
    /**
     * Test mobile form submission success via HTTP.
     *
     * @return void
     */
    public function testMobileFormSubmissionSuccess()
    {
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'mobile' => '1234567890',
        ]);

        $response->assertStatus(200);
    }
}
