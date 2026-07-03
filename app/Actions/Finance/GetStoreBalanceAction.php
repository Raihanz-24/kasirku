<?php

namespace App\Actions\Finance;

use App\Models\StoreBalance;

class GetStoreBalanceAction
{
    public function execute(): StoreBalance
    {
        return StoreBalance::query()->find(1)
            ?? StoreBalance::query()->forceCreate([
                'id' => 1,
                'current_balance' => 0,
            ]);
    }
}
