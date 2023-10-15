<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class IndividualDashboardController extends Controller
{
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
        $withdrawalRates = 0.015;
        $withdrawalFee = $request->amount * $withdrawalRates;
        $user = $request->user();
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'You do not have sufficient balance');
        }
        $newBalance = $user->balance - $request->amount - $withdrawalFee;

        $user->update(['balance' => $newBalance]);

        $user->transactions()->create($request->validated());
        return redirect()->route('individual.all.transaction');
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
