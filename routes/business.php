<?php

use App\Http\Controllers\Business\BusinessDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('business')->middleware(['user.type:business'])->group(function (){
    Route::get('/dashboard', [BusinessDashboardController::class,'create'])->name('business.dashboard');
    Route::get('/all-transaction', [BusinessDashboardController::class,'allTransactions'])->name('business.all.transaction');
    Route::get('/all-deposit-transaction', [BusinessDashboardController::class,'allDepositTransactions'])->name('business.all.deposit.transaction');
    Route::get('/all-withdraw-transaction', [BusinessDashboardController::class,'allWithdrawTransactions'])->name('business.all.withdraw.transaction');
    Route::get('/create-deposit', [BusinessDashboardController::class,'createDeposit'])->name('business.create.deposit');
    Route::get('/create-withdraw', [BusinessDashboardController::class,'createWithdraw'])->name('business.create.withdraw');
    Route::post('/deposit', [BusinessDashboardController::class,'deposit'])->name('business.deposit');
    Route::post('/withdraw', [BusinessDashboardController::class,'withdraw'])->name('business.withdraw');

});
