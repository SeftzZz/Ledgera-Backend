<?php

namespace App\Services;

use Config\Services;

class WsEmitter
{
    protected string $endpoint = 'http://127.0.0.1:3003/emit';

    public function emit(array $payload): void
    {
        try {
            $client = Services::curlrequest();

            $client->post($this->endpoint, [
                'json'    => $payload,
                'timeout'=> 2
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'WS emit failed: ' . $e->getMessage());
        }
    }

    /**
     * =========================
     * EMIT JOBS UPDATED
     * =========================
     */
    public function jobsUpdated(array $jobs): void
    {
        $this->emit([
            'type' => 'jobs_updated',
            'data' => $jobs
        ]);
    }

    /**
     * =========================
     * EMIT APPLICATION COUNTS
     * =========================
     */
    public function applicationCountsUpdated(array $counts): void
    {
        $this->emit([
            'type' => 'application_counts_updated',
            'data' => $counts
        ]);
    }
}
