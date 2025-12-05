<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\{ Transaction, Account };
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class SendSMS implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        Log::info($this->data);
        Log::info(json_encode($this->data));

        /* Cameroun API*/
        $response = Http::withoutVerifying()->withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json'
                ])->post('https://smsvas.com/bulk/public/index.php/api/v1/send', [
                    "token" => $this->data['token'],
                    "senderid" => $this->data['senderid'],
                    "sms" => $this->data['message'],
                    "mobiles" => $this->data['numeros']
                ])->json();

        Log::info($response);

        if ((isset($response['responsecode']) && $response['responsecode']) == 1 || (isset($response['status']) && $response['status'] == 'success')) {
            $solde = Account::find($this->data['account_id']);
            $solde->sms -= ceil(strlen($this->data['message']) / 160);
            $solde->save();
            $statut = 1;
        } else {
            $statut = 0;
        }

       /*  if (!is_null($this->data['historiqueSMS'])) {
            $historiqueSMS = $this->data['historiqueSMS'];
            $historiqueSMS->statut = $statut;
            $historiqueSMS->save();
            return $historiqueSMS;
        } */

        $historique = Transaction::create([
            'phone' => $this->data['numeros'],
            'status' => $statut,
            'campaign_id' => $this->data['campaign_id']
        ]);

        return $historique;
    }

}
