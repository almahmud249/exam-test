<?php

namespace App\Traits;

use Carbon\Carbon;

trait RateCalculateTrait
{
    public function rateCalculate($user, $feeRate, $withdrawalAmount)
    {
        $balance = $user->balance;


        $withdrawalFee = 0; // Initialize the withdrawal fee to zero

            // Calculate the fee only if it's not a Friday and the withdrawal amount is greater than $1,000
            $currentDate = Carbon::now();
            $firstDayOfMonth = $currentDate->startOfMonth();
            $lastDayOfMonth = $currentDate->endOfMonth();

            // Calculate the total withdrawals for the current month
            $totalWithdrawals = $user->transactions()->where('transaction_type', 'withdraw')
                ->where('created_at', '>=', $firstDayOfMonth)
                ->where('created_at', '<=', $lastDayOfMonth)
                ->sum('amount');
            $remainingFreeWithdrawalLimit = 5000 - $totalWithdrawals;
            if ($withdrawalAmount > $remainingFreeWithdrawalLimit) {
                $excessAmount = $withdrawalAmount - $remainingFreeWithdrawalLimit; // Calculate the excess amount
                $withdrawalFee = $excessAmount * $feeRate;
                $newBalance = $balance - $withdrawalAmount - $withdrawalFee;
                if ($newBalance < 0) {
                    return redirect()->back()->with('error', 'You do not have sufficient balance after withdrawal and fees');
                }
            } else if ($withdrawalAmount > 1000) {
                $excessAmount = $withdrawalAmount - 1000; // Calculate the excess amount
                $withdrawalFee = $this->withdrawalFee($feeRate, $excessAmount);
                $newBalance = $user->balance - $withdrawalAmount - $withdrawalFee;

            } else {
                $withdrawalFee = $this->withdrawalFee($feeRate, $withdrawalAmount);
                $newBalance = $user->balance - $withdrawalAmount - $withdrawalFee;
            }

        return [
            'newBalance' => $newBalance,
            'withdrawalFee' => $withdrawalFee,
        ];
    }

    public function withdrawalFee($rate, $amount)
    {
        $withdrawalRates = $rate;
        $withdrawalFee = $amount * $withdrawalRates;
        return $withdrawalFee;
    }
}
