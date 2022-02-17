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

class LoanController extends Controller
{
   /**
    * Display a listing of the Loans if user has admin role show the all loans but customer showing only him/her loans.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       if(Auth::user()->hasRole('admin')){
            $loans = Loan::paginate(10);
       }else{
            $loans = Loan::where('user_id',Auth::user()->id)->paginate(10);
       }

       return response()->json(['data' => $loans],200);
   }

   /**
    * Store a newly created loan in database.
    *
    */
   public function store(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'loan_amount' => 'required|numeric|gt:1',
            'monthly_income' => 'required|numeric|gt:1',
            'tenure_by_week' => 'required|numeric|gt:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
            ], 422);
        }
        // if the user has admin role - not able to create loan request
        if(Auth::user()->hasRole('admin')){
            return response()->json([
                'data' => 'you are not allowed to create a loan',
            ], 422);
        }
        try {
            DB::beginTransaction();
            // If needed check the panding loan was avilable or not
            $loanExists = Loan::where('user_id',Auth::user()->id)->where('status','<',2)->first();
            if(!is_null($loanExists)){
                return response()->json([
                    'data' => 'already pending loan avilable. please complete the repayment',
                ], 200);
             }
            $loan = new Loan();
            $loan->loan_amount = $request->loan_amount;
            $loan->monthly_income = $request->monthly_income;
            $loan->tenure_by_week = $request->tenure_by_week;
            $loan->slug  =  (string) Str::uuid();
            $loan->user_id = Auth::user()->id;
            $loan->balance_amount = $request->loan_amount;
            $loan->save();
            DB::commit();
            return response()->json(['data' => $loan],201);
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollback();
            return response()->json(['data' => $exception->errorInfo ],400);
        }
   }

   /**
    * Update a latest loan on Database.
    *
    */
    public function update(Request $request,$slug)
    {
         $validator = Validator::make($request->all(), [
             'status' => 'required|numeric|in:1,3',
         ]);

         if ($validator->fails()) {
             return response()->json([
                 'data' => $validator->errors(),
             ], 422);
         }
         // if the user has user role - not able to approve/reject loan request
         if(!Auth::user()->hasRole('admin')){
            return response()->json([
                'data' => 'you have no permission',
            ], 403);
         }
         try {
             DB::beginTransaction();
             $loan = Loan::where('slug',$slug)->first();
             if(is_null($loan)){
                return response()->json([
                    'data' => 'loan not found',
                ], 404);
             }
             if($loan->status > 0){
                return response()->json([
                    'data' => 'loan status already updated',
                ], 400);
             }
             $loan->status = $request->status;
             if($loan->status==1){
                $loan->sactioned_date = Carbon::now()->toDateTimeString();
                $loanRepaymentAmount = round($loan->loan_amount / $loan->tenure_by_week);
                $calculatedAmount=0;
                // based on the repayment tenure caculating the repayment amoung on weekly basis and updated on status was pending(0)
                for ($i = 1; $i <= $loan->tenure_by_week; $i++) {
                    // this condition was when round of the value some amount was missed ( so when last tenure we storing total balance pending amount)
                    if($i==$loan->tenure_by_week){
                        $loanRepaymentAmount = $loan->loan_amount-$calculatedAmount;
                    }
                    $calculatedAmount= $calculatedAmount+$loanRepaymentAmount;
                    $loanRepayment = new LoanRepayment();
                    $loanRepayment->user_id = $loan->user_id;
                    $loanRepayment->loan_id = $loan->id;
                    $loanRepayment->amount_paid = $loanRepaymentAmount;
                    $loanRepayment->weekly_due_date = Carbon::now()->addWeeks($i);
                    $loanRepayment->slug  =  (string) Str::uuid();
                    $loanRepayment->save();
                }
             }
             $loan->update();
             DB::commit();
             return response()->json(['data' => $loan],201);
         } catch (\Illuminate\Database\QueryException $exception) {
             DB::rollback();
             return response()->json(['data' => $exception->errorInfo ],400);
         }
    }

    /**
    * Display a listing of the loan details.
    *
    * @return \Illuminate\Http\Response
    */
    public function view($slug)
    {
        // if the user has admin role - show loan details. if user check the loan belongs to this user or not
        if(Auth::user()->hasRole('admin')){
                $loan = Loan::with('repayments')->where('slug',$slug)->first();
        }else{
                $loan = Loan::with('repayments')->where('slug',$slug)->where('user_id',Auth::user()->id)->first();
        }
        if(is_null($loan)){
            return response()->json(['data' => 'loan not found'],404);
        }
        return response()->json(['data' => $loan],200);
    }
}
