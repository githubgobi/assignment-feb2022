<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_loan_repayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('loan_id')->unsigned()->nullable();
            $table->decimal('amount_paid', 15, 3)->default(0);
            $table->date('paid_date')->nullable();
            $table->date('weekly_due_date')->nullable();
            $table->decimal('remaining_balance', 15, 3)->default(0);
            $table->integer('status')->default(0)->comment('0-pending,1-completed');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_loan_repayments');
    }
}
