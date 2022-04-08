<?php

namespace App\Repositories;

use App\Models\BankAccount;

class BankAccountRepository
{
    public function __construct(BankAccount $bankAccount)
    {
        $this->bankAccountModel = $bankAccount;
    }

    public function getBankAccount(int $id): ?BankAccount
    {
        return $this->bankAccountModel->find($id);
    }

    public function createStarterAccount(int $id): BankAccount
    {
        return $this->bankAccountModel->create([
            'id' => $id,
            'balance' => 0,
            'credit_limit' => 0
        ]);
    }

    public function increaseBalanceById(int $id, float $amount): void
    {
        $this->bankAccountModel->find($id)->increment('balance', $amount);
    }

    public function decreaseBalanceById(int $id, float $amount): void
    {
        $this->bankAccountModel->find($id)->decrement('balance', $amount);
    }
}
