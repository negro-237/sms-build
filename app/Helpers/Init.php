<?php
use App\Models\Account;

if (!function_exists('solde')) {

    function solde($account_id)
    {
        $solde = Account::find($account_id);

        return !is_null($solde) ? $solde->sms : 0;
    }

}
