<?php

namespace App\Actions\Finance;

use App\Models\BalanceMovement;
use App\Models\StoreBalance;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateWithdrawalAction
{
    /**
     * @param  array{amount:mixed,purpose:mixed,notes?:mixed,occurred_at?:mixed}  $data
     */
    public function execute(array $data, ?User $user = null): Withdrawal
    {
        $amount = filter_var($data['amount'] ?? null, FILTER_VALIDATE_INT);
        $purpose = trim((string) ($data['purpose'] ?? ''));

        if ($amount === false || $amount <= 0) {
            throw ValidationException::withMessages([
                'data.amount' => 'Nominal penarikan harus lebih dari Rp 0.',
            ]);
        }

        if ($purpose === '') {
            throw ValidationException::withMessages([
                'data.purpose' => 'Keperluan penarikan wajib diisi.',
            ]);
        }

        return DB::transaction(function () use ($amount, $data, $purpose, $user): Withdrawal {
            $balance = StoreBalance::query()
                ->whereKey(1)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                $balance = StoreBalance::query()->forceCreate([
                    'id' => 1,
                    'current_balance' => 0,
                ]);
            }

            $balanceBefore = (int) $balance->current_balance;

            if ($amount > $balanceBefore) {
                throw ValidationException::withMessages([
                    'data.amount' => 'Saldo toko tidak mencukupi untuk penarikan ini.',
                ]);
            }

            $balanceAfter = $balanceBefore - $amount;
            $occurredAt = $data['occurred_at'] ?? now();

            $withdrawal = Withdrawal::query()->create([
                'user_id' => $user?->id,
                'amount' => $amount,
                'purpose' => $purpose,
                'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'occurred_at' => $occurredAt,
            ]);

            $balance->forceFill(['current_balance' => $balanceAfter])->save();

            BalanceMovement::query()->create([
                'user_id' => $user?->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source_type' => $withdrawal::class,
                'source_id' => $withdrawal->id,
                'description' => $purpose,
                'occurred_at' => $occurredAt,
            ]);

            return $withdrawal->load('user', 'balanceMovement');
        });
    }
}
