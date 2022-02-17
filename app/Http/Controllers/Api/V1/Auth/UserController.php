<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Role;

use DB;

class UserController extends Controller
{
    /**
    * Store a newly user based is_admin save as admin or customer.
    *
    */
   public function store(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|confirmed|min:6|max:15',
            'is_admin' => 'in:Y,N'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
            ], 422);
        }
       $userData = $request->all();
       try {
            DB::beginTransaction();
            $user = User::create($userData);
            if($request->is_admin=="Y"){
                    $role = Role::where('name','admin')->first();
                    $user->attachRole($role);
            }else{
                    $role = Role::where('name','user')->first();
                    $user->attachRole($role);
            }
            DB::commit();
            return response()->json(['data' => $user],201);
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollback();
            return response()->json(['data' => $exception->errorInfo ],400);
        }
   }
}
