<?php

namespace App\Services;

use App\DTOs\VisitingChecklistDTO as VisitingChecklistDTO;
use Illuminate\Support\Facades\Http;

class VisitingChecklistService
{
    public function getChecklistItems(string $cid) {
        $url = config('app.vatusa_api_url').'/v2/user/1574900/transfer/checklist';

        $response = Http::get($url, [
            'apikey' => config('app.vatusa_api_key'),
        ]);

        if ($response->status() !== 200) {
            return new VisitingChecklistDTO(null);
        }

        return new VisitingChecklistDTO($response->json());
    }
}