<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountEvent;
use Illuminate\Http\Response;

class ResetController extends Controller
{
    /**
     * Reset all the tables in the database.
     *
     * @return void
     */
    public function reset(): Response
    {
        BankAccount::truncate();
        BankAccountEvent::truncate();

        return response('OK', 200);
    }
}
