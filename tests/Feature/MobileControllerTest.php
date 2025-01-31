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
     *
     * @return void
     */
    public function test_mobile_get_returns_view()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('Vordia::auth.index');
    }

    /**
     * Test mobile POST request fails validation when mobile is missing.
     *
     * @return void
     */
    public function test_mobile_post_validation_failure()
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors('mobile');
    }

    /**
     * Test mobile POST request creates a new user if one does not exist,
     * sets the OTP and login token, and sends an OTP notification.
     *
     * @return void
     */
    public function test_mobile_post_creates_new_user_and_sends_notification()
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
     *
     * @return void
     */
    public function test_mobile_post_updates_existing_user_and_sends_notification()
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
     *
     * @return void
     */
    public function test_checkOTP_validation_failure()
    {
        $response = $this->post('/check-otp', []);

        $response->assertSessionHasErrors(['otp', 'login_token']);
    }

    /**
     * Test that submitting an incorrect OTP returns an error.
     *
     * @return void
     */
    public function test_checkOTP_with_incorrect_otp()
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
     *
     * @return void
     */
    public function test_checkOTP_with_correct_otp()
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
