<?php

namespace App\Jobs;

use App\Models\TrainingAssignment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendTrainingRequestToWebhook implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public TrainingAssignment $trainingAssignment)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $webhookUrl = config('app.training_request_webhook_url');

        $payload = [
            'content' => null,
            'embeds' => [
                [
                    'title' => 'New Training Assignment Created',
                    'description' => "A new training assignment has been created for {$this->trainingAssignment->student->nameReversed} ({$this->trainingAssignment->student->id}).",
                    'fields' => [
                        [
                            'name' => 'Assignment ID',
                            'value' => (string)$this->trainingAssignment->id,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Training Type',
                            'value' => $this->trainingAssignment->training_type->mapToString(),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Created At',
                            'value' => $this->trainingAssignment->created_at->toDateTimeString(),
                            'inline' => false,
                        ],
                    ],
                    'color' => 5814783,
                ],
            ],
        ];

        // Send the payload to the webhook URL
        Http::post($webhookUrl, $payload);
    }
}
