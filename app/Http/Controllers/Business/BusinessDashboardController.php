<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Traits\RateCalculateTrait;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $withdrawalAmount = $request->amount;
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'You do not have sufficient balance');
        }

        $totalWithdrawals = $user->transactions()->where('transaction_type', 'withdraw')
            ->sum('amount');

        if ($user->account_type === 'business' && $totalWithdrawals > 50000) {
            $feeRate = 0.015; // Reduced fee rate of 1.5%
        } else {
            $feeRate = 0.025; // Default fee rate of 2.5%
        }
        $currentDate = Carbon::now();
        if (!$currentDate->isFriday()) {
            $afterRate = $this->rateCalculate($user, $feeRate, $withdrawalAmount);
        } else {
            $newBalance = $user->balance - $withdrawalAmount;
            $afterRate = [
                'newBalance' => $newBalance,
                'withdrawalFee' => null,
            ];
        }

        DB::beginTransaction();
        try {
            $user->update(['balance' => $afterRate['newBalance']]);
            $user->transactions()->create([
                'amount' => $withdrawalAmount,
                'fee' => $afterRate['withdrawalFee'],
            ]);
            DB::commit();
            return redirect()->route('business.all.transaction');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }
}
