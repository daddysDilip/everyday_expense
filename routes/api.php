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
    Route::post('account/updateDateFormat', [App\Http\Controllers\API\RegisterController::class, 'updateDateFormat']);
    Route::post('account/getUserDetail', [App\Http\Controllers\API\RegisterController::class, 'getUserDetail']);
    Route::post('account/getLanguageSetting', [App\Http\Controllers\API\RegisterController::class, 'getLanguageSetting']);
    Route::post('account/updateProfile', [App\Http\Controllers\API\RegisterController::class, 'updateprofile']);

    // user tickets
    Route::post('ticket/addUserTicket', [App\Http\Controllers\API\TicketController::class, 'addUserTicket']);
    Route::post('ticket/updateTicket', [App\Http\Controllers\API\TicketController::class, 'edit']);
    Route::post('ticket/getAllTickets', [App\Http\Controllers\API\TicketController::class, 'getUserTickets']);

    //user transaction
    Route::post('transaction/addTransaction', [App\Http\Controllers\API\TransactionController::class, 'add']);
    Route::post('transaction/editTransaction', [App\Http\Controllers\API\TransactionController::class, 'edit']);
    Route::post('transaction/deleteTransaction', [App\Http\Controllers\API\TransactionController::class, 'delete']);
    Route::get('transaction/getSymbols', [App\Http\Controllers\API\TransactionController::class, 'getSymbols']);
    Route::post('transaction/getTicketTransactions', [App\Http\Controllers\API\TransactionController::class, 'get']);
    Route::post('transaction/getTransactionDetail', [App\Http\Controllers\API\TransactionController::class, 'getDetail']);

    // user category
    Route::post('category/getMyCategory', [App\Http\Controllers\API\CategoryController::class, 'get']);
    Route::post('category/addMyCategory', [App\Http\Controllers\API\CategoryController::class, 'add']);
    Route::post('category/editMyCategory', [App\Http\Controllers\API\CategoryController::class, 'edit']);
    Route::post('category/deleteMyCategory', [App\Http\Controllers\API\CategoryController::class, 'delete']);


    // user Reminders
    Route::post('reminder/getMyReminder', [App\Http\Controllers\API\ReminderController::class, 'get']);
    Route::post('reminder/addMyReminder', [App\Http\Controllers\API\ReminderController::class, 'add']);
    Route::post('reminder/editMyReminder', [App\Http\Controllers\API\ReminderController::class, 'edit']);
    Route::post('reminder/deleteMyReminder', [App\Http\Controllers\API\ReminderController::class, 'delete']);
    Route::post('reminder/onOffReminder', [App\Http\Controllers\API\ReminderController::class, 'on_off']);

    // Reports
    Route::post('report/getAllTransactions', [App\Http\Controllers\API\ReportController::class, 'getAllTransactions']);
    Route::post('report/gethomeData', [App\Http\Controllers\API\ReportController::class, 'homeData']);
});
