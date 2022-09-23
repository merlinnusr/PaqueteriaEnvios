<?php

namespace App\Services;

use App\Models\User;

class WalletService
{

    public function walletUpdate($price)
    {
        $walletBalance = User::find(auth()->id())->getWalletBalance();

        $newBalance = [
            'wallet' =>  ($walletBalance ) + ($price) 
        ];
        $updatedUser = User::find(auth()->id())->update($newBalance);
        return $updatedUser; 
    }



}
