<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use App\Models\Loan;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = new Role();
        $userRole->name         = 'user';
        $userRole->display_name = 'Project user'; // optional
        $userRole->description  = 'User of a given project'; // optional
        $userRole->save();

        $adminRole = new Role();
        $adminRole->name         = 'admin';
        $adminRole->display_name = 'User Administrator'; // optional
        $adminRole->description  = 'User is allowed to manage and edit other users'; // optional
        $adminRole->save();


        $user = new User();
        $user->name         = 'customer';
        $user->email = 'customer@gmail.com'; // optional
        $user->password  = 'Password@123'; // optional
        $user->save();

        $user->attachRole($userRole);

        $user = new User();
        $user->name         = 'customer1';
        $user->email = 'customer1@gmail.com'; // optional
        $user->password  = 'Password@123'; // optional
        $user->save();

        $user->attachRole($userRole);

        $user = new User();
        $user->name         = 'customer2';
        $user->email = 'customer2@gmail.com'; // optional
        $user->password  = 'Password@123'; // optional
        $user->save();

        $user->attachRole($userRole);

        $user = new User();
        $user->name         = 'admin';
        $user->email = 'admin@gmail.com'; // optional
        $user->password  = 'Admin@123'; // optional
        $user->save();

        $user->attachRole($adminRole);

        $loan = new Loan();
        $loan->loan_amount = 2000;
        $loan->monthly_income = 5000;
        $loan->tenure_by_week = 2;
        $loan->slug  =  'loan-1';
        $loan->user_id = 1;
        $loan->balance_amount = 2000;
        $loan->save();

        $loan = new Loan();
        $loan->loan_amount = 2000;
        $loan->monthly_income = 5000;
        $loan->tenure_by_week = 3;
        $loan->slug  =  'loan-2';
        $loan->user_id = 3;
        $loan->balance_amount = 2000;
        $loan->save();

        $loan = new Loan();
        $loan->loan_amount = 2000;
        $loan->monthly_income = 5000;
        $loan->tenure_by_week = 3;
        $loan->slug  =  'loan-3';
        $loan->user_id = 3;
        $loan->balance_amount = 2000;
        $loan->save();

        $loan = new Loan();
        $loan->loan_amount = 2000;
        $loan->monthly_income = 5000;
        $loan->tenure_by_week = 3;
        $loan->slug  =  'loan-4';
        $loan->user_id = 3;
        $loan->balance_amount = 2000;
        $loan->status = 2;
        $loan->save();

    }
}
