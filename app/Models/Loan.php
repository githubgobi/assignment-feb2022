<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_amount', 'monthly_income', 'tenure_by_week','slug','user_id'
    ];

    protected $table = 'user_loans';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id','created_at','updated_at','deleted_at','user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount_sactioned_date' => 'datetime',
    ];
    // get all the related repayment history
    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }
    // get all the pending repayment history
    public function pending_repayments()
    {
        return $this->hasOne(LoanRepayment::class)->where('user_loan_repayments.status', '=', 0);
    }

}
