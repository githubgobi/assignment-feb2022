<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\Loan;


class LoanInitalTest extends TestCase
{

    public $api_user_email = 'customer@gmail.com';

    public $api_user_password = 'Password@123';

    public $api_user1_email = 'customer1@gmail.com';

    public $api_user1_password = 'Password@123';

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
    * Test Apply Loan.
    *
    * @return void
    */
    public function testLoanValidation()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'loan_amount' => '',
            'monthly_income' => '',
            'tenure_by_week' => ''
        ]);

        $response
            ->assertStatus(422);
    }


    /**
    * Test Apply Loan.
    *
    * @return void
    */
    public function testLoanSuccess()
    {
        $user = User::where('email', $this->api_user1_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'loan_amount' => 10000,
            'monthly_income' => 25000,
            'tenure_by_week' => 12
        ]);

        $response
            ->assertStatus(201);
    }

    /**
    * Test Approve Loan by user.
    *
    * @return void
    */
    public function testLoanApprovalValidationError()
    {
        $user = User::where('email', $this->api_user1_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-1?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(422);
    }

    /**
    * Test Approve Loan by user.
    *
    * @return void
    */
    public function testLoanApprovalByUserError()
    {
        $user = User::where('email', $this->api_user1_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-1?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'status' => 1,

        ]);

        $response
            ->assertStatus(403)
            ->assertExactJson([
                'data' => 'you have no permission',
            ]);
    }

    /**
    * Test Approve Loan by Admin.
    *
    * @return void
    */
    public function testLoanRejectByAdmin()
    {
        $user = User::where('email', $this->api_admin_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-2?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'status' => 3,

        ]);

        $response
            ->assertStatus(200);
    }

    /**
    * Test Approve Loan by Admin.
    *
    * @return void
    */
    public function testLoanApprovalByAdmin()
    {
        $user = User::where('email', $this->api_admin_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-1?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'status' => 1,
        ]);

        $response
        ->assertStatus(200);
    }

    /**
    * Test Try to Reject already Approve Loan by Admin.
    *
    * @return void
    */
    public function testLoanRejectByAdminApprovedLoan()
    {
        $user = User::where('email', $this->api_admin_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-1?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'status' => 3,

        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'data' => 'loan status already updated'
            ]);
    }

     /**
    * Test Try to Approved already Rejected Loan by Admin.
    *
    * @return void
    */
    public function testLoanApproveByAdminRejectedLoan()
    {
        $user = User::where('email', $this->api_admin_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-2?token=' . $token;

        $response = $this->json('POST', $baseUrl, [
            'status' => 1,

        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'data' => 'loan status already updated'
            ]);
    }

    /**
    * Get Loan Success
    *
    * @return void
    */
    public function testLoanSuccessInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-1?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(200);
    }

    /**
    * Get Loan Failure
    *
    * @return void
    */
    public function testLoanFailureInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/loan-2?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(404);
    }
}
