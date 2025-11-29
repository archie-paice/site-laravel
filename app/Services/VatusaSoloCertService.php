<?php

namespace App\Services;

use App\Models\SoloCert;
use DateTime;
use Http;
use Illuminate\Http\Client\ConnectionException;

class VatusaSoloCertService
{
    private string $API_URL;

    public function __construct() {
        $this->API_URL = config('app.vatusa.api_url') . '/v2/solo';
    }

    /**
     * @throws ConnectionException
     */
    public function createVatusaSoloCert(SoloCert $soloCert): int
    {
        $request = Http::post($this->API_URL, [
            'cid' => $soloCert->user_id,
            'position' => $soloCert->position,
            'expDate' => $soloCert->expires->format('Y-m-d')
        ]);

        return $request->status();
    }

    public function deleteVatusaSoloCert(SoloCert $soloCert): int
    {
        $request = Http::delete($this->API_URL, [
            'cid' => $soloCert->user_id,
            'position' => $soloCert->position,
        ]);

        return $request->status();
    }
}
