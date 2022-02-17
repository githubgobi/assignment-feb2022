<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\Loan;

class LoanRepaymentTest extends TestCase
{
    public $api_user_email = 'customer@gmail.com';

    public $api_user_password = 'Password@123';

    public $api_user1_email = 'customer1@gmail.com';

    public $api_user1_password = 'Password@123';

    public $api_user2_email = 'customer2@gmail.com';

    public $api_user2_password = 'Password@123';

    public $api_admin_email = 'admin@gmail.com';

    public  $api_admin_password = 'Admin@123';

    public $loan_slug = 'slug1';

    public $loan_id = 1;

    public $user_id=1;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
    * Get Loan Repayment Rejected Loan
    *
    * @return void
    */
    public function testLoanRepaymentRejectedLoanInfo()
    {
        $user = User::where('email', $this->api_user1_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/repayment/pay?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(404)->assertExactJson([
            'data' => 'no pending loan not found',
        ]);
    }


    /**
    * Get Loan Repayment Success Loan
    *
    * @return void
    */
    public function testLoanRepaymentSuccessLoanInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/repayment/pay?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(200)->assertExactJson([
            'data' => 'repayment success',
        ]);
    }

    /**
    * Get Loan Repayment Next Due Loan
    *
    * @return void
    */
    public function testLoanRepaymentNextDueLoanInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/repayment/next?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(200);
    }
    /**
    * Get Loan Repayment Complete Loan
    *
    * @return void
    */
    public function testLoanRepaymentCompleteLoanInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/repayment/pay?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(200)->assertExactJson([
            'data' => 'all the repayments successfully completed. thanks for contacting us. now you are eligible for applying loan',
        ]);
    }

    /**
    * Get Loan Repayment No Due Loan
    *
    * @return void
    */
    public function testLoanRepaymentNoDueLoanInfo()
    {
        $user = User::where('email', $this->api_user_email)->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = config('app.url') . '/api/v1/loan/repayment/next?token=' . $token;

        $response = $this->json('GET', $baseUrl, []);

        $response->assertStatus(200)->assertExactJson([
            'data' => 'no repayments pending',
        ]);
    }
}
