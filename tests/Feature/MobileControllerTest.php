<?php

namespace Rayiumir\Vordia\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Rayiumir\Vordia\Http\Notifications\OTPSms;
use Tests\TestCase;

class MobileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a GET request to the mobile endpoint returns the expected view.
     */
    public function test_mobile_get_returns_view(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('Vordia::auth.index');
    }

    /**
     * Test mobile POST request fails validation when mobile is missing.
     */
    public function test_mobile_post_validation_failure(): void
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors('mobile');
    }

    /**
     * Test mobile POST request creates a new user if one does not exist,
     * sets the OTP and login token, and sends an OTP notification.
     */
    public function test_mobile_post_creates_new_user_and_sends_notification(): void
    {
        Notification::fake();

        $mobile = '1234567890';

        $response = $this->post('/login', [
            'mobile' => $mobile,
        ]);

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('login_token', $json);
        $user = User::where('mobile', $mobile)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->login_token);

        Notification::assertSentTo([$user], OTPSms::class);
    }

    /**
     * Test mobile POST request updates an existing user with new OTP and login token.
     */
    public function test_mobile_post_updates_existing_user_and_sends_notification(): void
    {
        Notification::fake();

        // Create an existing user with a given mobile number.
        $user = User::factory()->create([
            'mobile'      => '1234567890',
            'otp'         => null,
            'login_token' => null,
        ]);

        $response = $this->post('/login', [
            'mobile' => $user->mobile,
        ]);

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('login_token', $json);

        $user->refresh();
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->login_token);

        Notification::assertSentTo([$user], OTPSms::class);
    }

    /**
     * Test that the checkOTP endpoint fails validation when required fields are missing.
     */
    public function test_checkOTP_validation_failure(): void
    {
        $response = $this->post('/check-otp', []);

        $response->assertSessionHasErrors(['otp', 'login_token']);
    }

    /**
     * Test that submitting an incorrect OTP returns an error.
     */
    public function test_checkOTP_with_incorrect_otp(): void
    {
        $user = User::factory()->create([
            'otp'         => '123456',
            'login_token' => 'fixed_token',
        ]);

        $response = $this->post('/check-otp', [
            'otp'         => '000000',
            'login_token' => 'fixed_token',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['otp']
        ]);
    }

    /**
     * Test that submitting the correct OTP logs in the user successfully.
     */
    public function test_checkOTP_with_correct_otp(): void
    {
        $user = User::factory()->create([
            'otp'         => '123456',
            'login_token' => 'fixed_token',
        ]);

        $response = $this->post('/check-otp', [
            'otp'         => '123456',
            'login_token' => 'fixed_token',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'ورود با موفقیت انجام شد'
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
