<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Construct a request with a valid token
     *
     * @return self
     */
    public function withAuth(): self
    {
        $token = Auth::attempt([
            'email' => 'test@domain.com',
            'password' => 'Passw0rd'
        ]);

        return $this->withHeaders(['Authorization' => "Bearer {$token}"]);
    }

    // Register

    /**
     * Feature test for successful user registration
     *
     * @return void
     */
    public function test_register_with_valid_body(): void
    {
        $response = $this->post('/user/register', [
            'name' => 'A valid name',
            'email' => 'test2@domain.com',
            'password' => 'Passw0rd@2023',
            'phone' => '87654321'
        ]);

        $response->assertCreated()->assertJsonStructure([
            'type',
            'message',
            'token'
        ]);
    }

    /**
     * Feature test for unsuccessful user registration with missing or invalid values
     *
     * @return void
     */
    public function test_register_with_missing_and_invalid_values(): void
    {
        $response = $this->post('/user/register', [
            'name' => 213,
            'email' => 'test@domain.com',
            'password' => 'Passw0rd'
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for unsuccessful user registration with existing email
     *
     * @return void
     */
    public function test_register_with_existing_email(): void
    {
        $response = $this->post('/user/register', [
            'name' => 'A valid name',
            'email' => 'test@domain.com',
            'password' => 'Passw0rd@2023',
            'phone' => '87654321'
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Email Verification

    /**
     * Feature test for successful resend email verification
     *
     * @return void
     */
    public function test_resend_email_verification_with_valid_token(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'test2@domain.com'
        ]);

        $token = Auth::login($user);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->post('/email/re-verify');

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful resend email verification
     *
     * @return void
     */
    public function test_resend_email_verification_with_invalid_credentials(): void
    {
        $response = $this->withAuth()->post('/email/re-verify');

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Login

    /**
     * Feature test for successful user login
     *
     * @return void
     */
    public function test_login_with_credentials(): void
    {
        $res = $this->post('/user/login', [
            'email' => 'test@domain.com',
            'password' => 'Passw0rd',
            'remember' => true
        ]);

        $res->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user login with missing or invalid values
     *
     * @return void
     */
    public function test_login_with_missing_and_invalid_values(): void
    {
        $response = $this->post('/user/login', [
            'email' => 123,
            'password' => 'Passw0rd'
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for unsuccessful user login with invalid credentials
     *
     * @return void
     */
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/user/login', [
            'email' => 'test@domain.com',
            'password' => 'password',
            'remember' => true
        ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Logout

    /**
     * Feature test for successful user logout
     *
     * @return void
     */
    public function test_logout_with_credentials(): void
    {
        $response = $this->withAuth()->post('/user/logout');

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user logout with invalid credentials
     *
     * @return void
     */
    public function test_logout_with_invalid_credentials(): void
    {
        $response = $this
            ->withHeaders(['Authorization' => "Bearer invalid_token"])
            ->post('/user/logout');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Reset password

    /**
     * Feature test for successful user password reset
     *
     * @return void
     */
    public function test_reset_password_with_credentials(): void
    {
        $response = $this->withAuth()->post('/user/reset', [
            'newPassword' => 'ki7LPiwi--x=$ibRACRI',
            'oldPassword' => 'Passw0rd'
        ]);

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user password reset with missing or invalid values
     *
     * @return void
     */
    public function test_reset_password_with_missing_and_invalid_values(): void
    {
        $response = $this->withAuth()->post('/user/reset', [
            'oldPassword' => 123
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user password reset with invalid credentials
     *
     * @return void
     */
    public function test_reset_password_with_invalid_credentials(): void
    {
        $response = $this
            ->withHeaders(['Authorization' => "Bearer invalid_token"])
            ->post('/user/reset', [
                'newPassword' => 'ki7LPiwi--x=$ibRACRI',
                'oldPassword' => 'Passw0rd'
            ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Get user data

    /**
     * Feature test for successful user data retrieval
     *
     * @return void
     */
    public function test_get_user_with_credentials(): void
    {
        $response = $this->withAuth()->get('/user');

        $response->assertOk()->assertJsonStructure([
            'type',
            'message',
            'user' => [
                'id',
                'name',
                'phone',
                'email',
                'preferred_contact',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * Feature test for unsuccessful user data retrieval with invalid credentials
     *
     * @return void
     */
    public function test_get_user_with_invalid_credentials(): void
    {
        $response = $this->get('/user');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Update user data

    /**
     * Feature test for successful user update
     *
     * @return void
     */
    public function test_update_user_with_credentials_and_body(): void
    {
        $response = $this->withAuth()->put('/user', [
            'name' => 'Test User',
            'phone' => '1234567890',
            'preferred_contact' => 'phone'
        ]);

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user update with missing or invalid values
     *
     * @return void
     */
    public function test_update_user_with_invalid_values(): void
    {
        $response = $this->withAuth()->put('/user', [
            'name' => 'Test User',
            'phone' => 1234567890,
            'preferred_contact' => 'phone'
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user update with invalid credentials
     *
     * @return void
     */
    public function test_update_user_with_invalid_credentials(): void
    {
        $response = $this->put('/user', [
            'name' => 'Test User',
            'phone' => '1234567890',
            'preferred_contact' => 'phone'
        ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    // Delete user data

    /**
     * Feature test for successful user delete
     *
     * @return void
     */
    public function test_delete_user_with_credentials(): void
    {
        $response = $this->withAuth()->delete('/user');

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for unsuccessful user delete with invalid credentials
     *
     * @return void
     */
    public function test_delete_user_with_invalid_credentials(): void
    {
        $response = $this->get('/user');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }
}
