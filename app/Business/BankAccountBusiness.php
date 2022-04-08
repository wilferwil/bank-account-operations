<?php

namespace App\Business;

use App\Repositories\BankAccountEventRepository;
use App\Repositories\BankAccountRepository;
use App\Models\BankAccountEvent;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountBusiness
{
    private $bankAccountEventRepository;
    private $bankAccountRepository;

    public function __construct()
    {
        $this->bankAccountRepository = new BankAccountRepository(new BankAccount());
        $this->bankAccountEventRepository = new BankAccountEventRepository(new BankAccountEvent());
    }

    public function processTransaction(Request $request): array
    {
        $processTransaction = 'process' . ucfirst($request->type);

        $bankAccount = $this->$processTransaction($request);

        $this->bankAccountEventRepository->storeTransaction($request);

        return $bankAccount;
    }

    public function getBankAccount(int $id): ?BankAccount
    {
        return $this->bankAccountRepository->getBankAccount($id);
    }

    public function createStarterAccount(int $id): BankAccount
    {
        return $this->bankAccountRepository->createStarterAccount($id);
    }

    public function accountExists(int $id): bool
    {
        return (bool) $this->getBankAccount($id);
    }

    public function hasEnoughFunds(int $id, float $amount): bool
    {
        $bankAccount = $this->getBankAccount($id);

        return $bankAccount->balance + $bankAccount->credit_limit >= $amount;
    }

    public function processDeposit(Request $request): array
    {
        if (!$this->accountExists($request->destination)) {
            $this->createStarterAccount($request->destination);
        }

        $this->bankAccountRepository->increaseBalanceById($request->destination, $request->amount);

        return ['destination' => $this->bankAccountRepository->getBankAccount($request->destination)];
    }

    public function processWithdraw(Request $request): array
    {
        $this->bankAccountRepository->decreaseBalanceById($request->origin, $request->amount);

        return ['origin' => $this->bankAccountRepository->getBankAccount($request->origin)];
    }

    public function processTransfer(Request $request): array
    {
        $withdraw = $this->processWithdraw($request);
        $deposit = $this->processDeposit($request);
        return array_merge($withdraw, $deposit);
    }
}
