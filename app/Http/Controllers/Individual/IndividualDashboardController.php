<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Traits\RateCalculateTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndividualDashboardController extends Controller
{
    use RateCalculateTrait;
    public function create()
    {
        return view('individual.dashboard');
    }

    public function allTransactions(Request $request)
    {
        $all_transactions = Transaction::where('user_id', $request->user()->id)->get();
        return view('Individual.all-transactions', compact('all_transactions'));
    }

    public function createDeposit()
    {
        return view('Individual.deposit-form');
    }

    public function deposit(TransactionRequest $request)
    {
        $user = $request->user();
        $user->update([
            'balance' => $request->amount + $user->balance
        ]);
        $user->transactions()->create($request->validated());

        return redirect()->route('individual.all.transaction');
    }

    public function createWithdraw()
    {
        return view('Individual.withdraw-form');
    }

    public function withdraw(TransactionRequest $request)
    {
        $user = $request->user();
        $withdrawalAmount = $request->amount;
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'You do not have sufficient balance');
        }

        $feeRate = 0.015; // Reduced fee rate of 1.5%

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
            return redirect()->route('individual.all.transaction');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function allDepositTransactions(Request $request)
    {
        $all_transactions = Transaction::where('transaction_type', 'deposit')->where('user_id', $request->user()->id)->get();
        return view('Individual.all-transactions', compact('all_transactions'));
    }

    public function allWithdrawTransactions(Request $request)
    {
        $all_transactions = Transaction::where('transaction_type', 'withdraw')->where('user_id', $request->user()->id)->get();
        return view('Individual.all-transactions', compact('all_transactions'));
    }
}
