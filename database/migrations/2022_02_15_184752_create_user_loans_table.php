<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->decimal('loan_amount',15,3)->default(0);
            $table->decimal('monthly_income',15,3)->default(0);
            $table->decimal('loan_paid',15,3)->default(0);
            $table->decimal('balance_amount',15,3)->default(0);
            $table->integer('tenure_by_week')->default(0);
            $table->integer('status')->default(0)->comment('0-pending,1-approved,2-completed,3-rejected');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->date('sactioned_date')->nullable();
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
        Schema::dropIfExists('user_loans');
    }
}
