<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Role;
use App\Models\Loan;
use App\Models\LoanRepayment;

use DB;

class RepaymentController extends Controller
{
      /**
    * Update a status of repayment transcation.
    * if we have slug of open loan we can pay easily ( there is no check about date and all)
    *
    * @return \Illuminate\Http\Response
    */
    public function loanRepayment()
    {
        if(Auth::user()->hasRole('admin')){
            return response()->json(['data' => "access forbidden"],403);
        }
        $loan = Loan::where('user_id',Auth::user()->id)->where('status',1)->first();
        if(is_null($loan)){
            return response()->json(['data' => 'no pending loan not found'],404);
        }
        $getLatestRepayment = LoanRepayment::where('loan_id',$loan->id)->where('user_id',Auth::user()->id)->where('status',0)->orderBy('id','ASC')->first();
        if(is_null($getLatestRepayment)){
            return response()->json(['data' => 'Repayment Already Paid'],200);
        }
        try {
            DB::beginTransaction();
            $isCompleted=false;
            $getLatestRepayment->status = 1;
            $getLatestRepayment->paid_date = Carbon::now()->toDateTimeString();

            $balanceAmount = round($loan->balance_amount - $getLatestRepayment->amount_paid);
            $amountPaid = round($loan->loan_paid+$getLatestRepayment->amount_paid);
            if($balanceAmount==0 && $amountPaid==$loan->loan_amount){
                $loan->balance_amount = $balanceAmount;
                $loan->loan_paid = $amountPaid;
                $loan->status = 2;
                $loan->update();
                $isCompleted=true;
            }else{
                $loan->balance_amount = $balanceAmount;
                $loan->loan_paid = $amountPaid;
                $loan->update();
            }
            $getLatestRepayment->remaining_balance = $balanceAmount;
            $getLatestRepayment->update();
            DB::commit();
            if($isCompleted)
                return response()->json(['data' => "all the repayments successfully completed. thanks for contacting us. now you are eligible for applying loan"],200);
            return response()->json(['data' => "repayment success"],200);
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollback();
            return response()->json(['data' => $exception->errorInfo ],400);
        }
    }

    /**
    * Display a listing of the resource. Its showing next repayment of Pending Loans. there is slug needed for check the repayment
    *
    * @return \Illuminate\Http\Response
    */
    public function nextLoanRepayment()
    {
        if(Auth::user()->hasRole('admin')){
            return response()->json(['data' => "access forbidden"],403);
        }
        $loan = Loan::with('pending_repayments')->where('user_id',Auth::user()->id)->where('status',1)->first();
        if(is_null($loan)){
            return response()->json(['data' => 'no repayments pending'],404);
        }
        return response()->json(['data' => $loan->pending_repayments],200);
    }
}
