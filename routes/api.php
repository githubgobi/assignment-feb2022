<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([ 'middleware' => 'api','prefix' => 'v1'], function ($router) {
    Route::post('login', 'AuthController@login');
 });
 Route::group(['prefix' => 'v1','middleware' => ['api','jwt.verify']], function () {

    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::post('me', 'AuthController@me')->name('me');

    Route::get('loan', 'LoanController@index')->name('loan-list');
    Route::post('loan', 'LoanController@store')->name('loan');
    Route::get('loan/{slug}', 'LoanController@view')->name('loan-view');
    Route::post('loan/{slug}', 'LoanController@update')->name('loan-update');

    Route::get('loan/repayment/pay', 'RepaymentController@loanRepayment')->name('repayment');
    Route::get('loan/repayment/next', 'RepaymentController@nextLoanRepayment')->name('next-repayment');

});

Route::group(['prefix' => 'v1'], function ($router) {
    Route::post('signup', 'UserController@store');
    // Route::get('users', 'UserController@index');
 });


Route::fallback(function () {
    return response()->json(["message"=>'url not found'],404);
});
