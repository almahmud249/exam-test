<?php

use App\Http\Controllers\Individual\IndividualDashboardController;
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

Route::prefix('individual')->middleware(['user.type:individual'])->group(function (){
    Route::get('/dashboard', [IndividualDashboardController::class,'create'])->name('individual.dashboard');
    Route::get('/all-transaction', [IndividualDashboardController::class,'allTransactions'])->name('individual.all.transaction');
    Route::get('/all-deposit-transaction', [IndividualDashboardController::class,'allDepositTransactions'])->name('individual.all.deposit.transaction');
    Route::get('/all-withdraw-transaction', [IndividualDashboardController::class,'allWithdrawTransactions'])->name('individual.all.withdraw.transaction');
    Route::get('/create-deposit', [IndividualDashboardController::class,'createDeposit'])->name('individual.create.deposit');
    Route::post('/deposit', [IndividualDashboardController::class,'deposit'])->name('individual.deposit');
    Route::get('/create-withdraw', [IndividualDashboardController::class,'createWithdraw'])->name('individual.create.withdraw');
    Route::post('/withdraw', [IndividualDashboardController::class,'withdraw'])->name('individual.withdraw');
});
