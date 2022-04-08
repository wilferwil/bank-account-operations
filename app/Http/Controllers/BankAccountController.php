<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Business\BankAccountBusiness;

class BankAccountController extends Controller
{
    private $bankAccountBusiness;
    private $bankAccountValidation = ['account_id' => 'required|numeric'];
    private $bankAccountEventValidation = [
        'type' => 'required|string|in:deposit,withdraw,transfer',
        'amount' => 'required|numeric|gt:0',
        'origin' => 'required_if:type,transfer,withdraw|integer|gt:0',
        'destination' => 'required_if:type,transfer,deposit|integer|gt:0'
    ];

    /**
     * Constructor instantiating business classes.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bankAccountBusiness = new BankAccountBusiness();
    }

    public function balance(Request $request): JsonResponse
    {
        $this->validate($request, $this->bankAccountValidation);

        if (!$this->bankAccountBusiness->accountExists($request->account_id)) {
            return response()->json(0, JsonResponse::HTTP_NOT_FOUND);
        }

        $bankAccount = $this->bankAccountBusiness->getBankAccount($request->account_id);

        return response()->json((float) $bankAccount->balance, JsonResponse::HTTP_OK);
    }

    public function event(Request $request): JsonResponse
    {
        $this->validate($request, $this->bankAccountEventValidation);

        if (
            in_array($request->type, ['withdraw', 'transfer'])
            && !$this->bankAccountBusiness->accountExists($request->origin)
        ) {
            return response()->json(0, JsonResponse::HTTP_NOT_FOUND);
        } elseif (
            in_array($request->type, ['withdraw', 'transfer'])
            && !$this->bankAccountBusiness->hasEnoughFunds($request->origin, $request->amount)
        ) {
            return response()->json(0, JsonResponse::HTTP_PAYMENT_REQUIRED);
        }

        $event = $this->bankAccountBusiness->processTransaction($request);
        
        return response()->json($event, JsonResponse::HTTP_CREATED);
    }
}
