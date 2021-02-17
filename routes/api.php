<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('account/register', [App\Http\Controllers\API\RegisterController::class, 'register']);
// Route::post('account/register', 'API\RegisterController@register');
// Route::post('account/login', 'API\RegisterController@login');

Route::middleware('auth:api')->group(function () {

	// user account
    Route::get('account/countryLanguages', [App\Http\Controllers\API\RegisterController::class, 'getCountryLanguages']);
    Route::post('account/updateLanguage', [App\Http\Controllers\API\RegisterController::class, 'updateLanguage']);
    Route::post('account/getLanguageSetting', [App\Http\Controllers\API\RegisterController::class, 'getLanguageSetting']);

    // user tickets
    Route::post('ticket/addUserTicket', [App\Http\Controllers\API\TicketController::class, 'addUserTicket']);
    Route::post('ticket/updateTicket', [App\Http\Controllers\API\TicketController::class, 'edit']);
    Route::post('ticket/getAllTickets', [App\Http\Controllers\API\TicketController::class, 'getUserTickets']);

    //user transaction
    Route::post('transaction/addTransaction', [App\Http\Controllers\API\TransactionController::class, 'add']);
    Route::post('transaction/editTransaction', [App\Http\Controllers\API\TransactionController::class, 'edit']);
    Route::post('transaction/deleteTransaction', [App\Http\Controllers\API\TransactionController::class, 'delete']);
    Route::get('transaction/getSymbols', [App\Http\Controllers\API\TransactionController::class, 'getSymbols']);

});
