<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Traits\RateCalculateTrait;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BusinessDashboardController extends Controller
{
    use RateCalculateTrait;

    public function create()
    {
        return view('business.dashboard');
    }

    public function allTransactions(Request $request)
    {
        $all_transactions = Transaction::where('user_id', $request->user()->id)->get();
        return view('Business.all-transactions', compact('all_transactions'));
    }

    public function createDeposit()
    {
        return view('Business.deposit-form');
    }

    public function allDepositTransactions(Request $request)
    {
        $all_transactions = Transaction::where('transaction_type', 'deposit')->where('user_id', $request->user()->id)->get();
        return view('Business.all-transactions', compact('all_transactions'));
    }

    public function allWithdrawTransactions(Request $request)
    {
        $all_transactions = Transaction::where('transaction_type', 'withdraw')->where('user_id', $request->user()->id)->get();
        return view('Business.all-transactions', compact('all_transactions'));
    }

    public function deposit(TransactionRequest $request)
    {
        $user = $request->user();
        $user->update([
            'balance' => $request->amount + $user->balance
        ]);
        $user->transactions()->create($request->validated());

        return redirect()->route('business.all.transaction');
    }

    public function createWithdraw()
    {
        return view('Business.withdraw-form');
    }

    public function withdraw(TransactionRequest $request)
    {

        $user = $request->user();
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'You do not have sufficient balance');
        }

        $currentDate = Carbon::now();

        $withdrawalFee = 0; // Initialize the withdrawal fee to zero

        if (!$currentDate->isFriday()) {
            // Calculate the fee only if it's not a Friday and the withdrawal amount is greater than $1,000
            $currentDate = Carbon::now();
            $firstDayOfMonth = $currentDate->startOfMonth();
            $lastDayOfMonth = $currentDate->endOfMonth();

            // Calculate the total withdrawals for the current month
            $totalWithdrawals = $user->transactions()->where('transaction_type','withdraw')
                ->where('created_at', '>=', $firstDayOfMonth)
                ->where('created_at', '<=', $lastDayOfMonth)
                ->sum('amount');
            $remainingFreeWithdrawalLimit = 5000 - $totalWithdrawals;
            if ($request->amount > $remainingFreeWithdrawalLimit) {
                $excessAmount = $request->amount - $remainingFreeWithdrawalLimit; // Calculate the excess amount
                $feeRate = 0.025; // 2.5% fee rate, adjust as needed
                $withdrawalFee = $excessAmount * $feeRate;
            }
            if ($request->amount > 1000) {
                $excessAmount = $request->amount - 1000; // Calculate the excess amount
                $withdrawalFee = $this->rateCalculate('0.025', $excessAmount);
            }else{
                $withdrawalFee = $this->rateCalculate('0.025', $request->amount);
            }
        }
        $newBalance = $user->balance - $request->amount - $withdrawalFee;
        $user->update(['balance' => $newBalance]);
        $user->transactions()->create([
            'amount' => $request->amount,
            'fee' => $withdrawalFee,
        ]);
        return redirect()->route('business.all.transaction');
    }


}
