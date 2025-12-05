<?php

namespace App\Traits;

use App\Jobs\SendSMS;

/*
  |--------------------------------------------------------------------------
  | Api Responser Trait
  |--------------------------------------------------------------------------
  |
  | This trait will be used for any response we sent to clients.
  |
 */

trait SMSTrait {

    /**
     * Return a success JSON response.
     * @param  int|null  $tel
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function envoiSMS($tel, $message, $campaign_id, $sender, $account_id, $historiqueSMS = null) {

        /* if (solde($account_id) <= 0) {
            return false;
        } */

        $data = [
            "token" => "e760ed27848bee578eadf0b8dd9fa4e2557c17086127ffc3aec66af68ec9fa46",
            "senderid" => $sender,
            "message" => str_replace(array("\r", "\n"), ' ', $message),
            "numeros" => $tel,
            "dateenvoi" => "",
            "pays" => "CMR",
            "campaign_id" => $campaign_id,
            "account_id" => $account_id,
            //"historiqueSMS" => $historiqueSMS
        ];

        SendSMS::dispatch($data);

        return true;
    }

}
