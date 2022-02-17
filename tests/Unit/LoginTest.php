<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;

class LoginTest extends TestCase
{

    public $api_user_email = 'customer@gmail.com';

    public $api_user_password = 'Password@123';

    public $api_admin_email = 'admin@gmail.com';

    public  $api_admin_password = 'Admin@123';

    public $loan_slug = 'slug1';

    public $loan_id = 1;

    public $user_id=1;
    /**
     *
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
    * Login as default API user and get token back.
    *
    * @return void
    */
    public function testUserLogin()
    {
        $baseUrl = config('app.url') . '/api/v1/login';
        $email = $this->api_user_email;
        $password = $this->api_user_password;

        $response = $this->json('POST', $baseUrl . '/', [
                'email' => $email,
                'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    public function testLoginError()
    {
        $baseUrl = config('app.url') . '/api/v1/login';
        $email = $this->api_user_email;
        $password = '';

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'details'
            ]);
    }

    /**
    * Login as default API user and get token back.
    *
    * @return void
    */
    public function testAdminLogin()
    {
        $baseUrl = config('app.url') . '/api/v1/login';
        $email = $this->api_admin_email;
        $password = $this->api_admin_password;

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    /**
    * Test logout.
    *
    * @return void
    */
    public function testUserLogout()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/logout?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out'
            ]);
    }
    /**
    * Test logout.
    *
    * @return void
    */
    public function testAdminLogout()
    {
        $user = User::where('email', $this->api_admin_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/logout?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out'
            ]);
    }

    /**
    * Test token refresh.
    *
    * @return void
    */
    public function testRefresh()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/refresh?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    /**
    * Get all users.
    *
    * @return void
    */
    public function testGetInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/me?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response->assertStatus(200);
    }
}
