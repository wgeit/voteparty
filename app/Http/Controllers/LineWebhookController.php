<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\LineGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LineWebhookController extends Controller
{

    public function sendToSlack($webhookUrl, $message)
    {
        
        $payload = [
            'text' => $message,
        ];
    
        $response = Http::timeout(5)->post($webhookUrl, $payload);

        if ($response->failed()) {
            Log::error('Slack notification failed', [
                'url' => $webhookUrl,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }

}
