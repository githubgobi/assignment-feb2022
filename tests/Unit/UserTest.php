<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testCreateUserwithEmailValidation()
    {
            $data = [
                    'name' => "customer3",
                    'email' => "This is a customer",
                    'password' => 'Welcome@123',
                    'password_confirmation' => 'Welcome@123',
                            ];

        $response = $this->json('POST', '/api/v1/signup',$data);
        $response->assertStatus(422);
    }

    public function testCreateUserwithConfirmPasswordValidation()
    {
            $data = [
                    'name' => "customer3",
                    'email' => "customer3@gmail.com",
                    'password' => 'Welcome@123',
                    'password_confirmation' => 'Welcome',
                            ];

        $response = $this->json('POST', '/api/v1/signup',$data);
        $response->assertStatus(422);
    }

    public function testCreateUserwithDiffrentRole()
    {
            $data = [
                    'name' => "customer3",
                    'email' => "customer3@gmail.com",
                    'password' => 'Welcome@123',
                    'password_confirmation' => 'Welcome@123',
                    'is_admin' => 'X'
                            ];

        $response = $this->json('POST', '/api/v1/signup',$data);
        $response->assertStatus(422);
    }

    public function testCreateUserwithSuccess()
    {
            $data = [
                    'name' => "customer4",
                    'email' => "customer4@gmail.com",
                    'password' => 'Welcome@123',
                    'password_confirmation' => 'Welcome@123',
                            ];

        $response = $this->json('POST', '/api/v1/signup',$data);
        $response->assertStatus(201);
    }
}
