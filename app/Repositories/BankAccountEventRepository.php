<?php

namespace App\Repositories;

use App\Models\BankAccountEvent;
use Illuminate\Http\Request;

class BankAccountEventRepository
{
    public function __construct(BankAccountEvent $bankAccountEvent)
    {
        $this->bankAccountEventModel = $bankAccountEvent;
    }

    public function storeTransaction(Request $request): BankAccountEvent
    {
        return $this->bankAccountEventModel->create(
            [
                'type' => $request->type,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'amount' => $request->amount
            ]
        );
    }
}
