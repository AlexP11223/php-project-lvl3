<?php

namespace App\Jobs;

use App\Analysis\Analyzer;
use App\Url;
use Illuminate\Support\Facades\Log;

class AnalysisJob extends Job
{
    /**
     * @var Url
     */
    private $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->url->setState(Url::PROCESSING);

            $analyzer = new Analyzer();
            $results = $analyzer->requestData($this->url->address);
            $this->url->update($results);

            $this->url->setState(Url::SUCCEEDED);
        } catch (\Throwable $ex) {
            $this->url->setState(Url::FAILED);

            Log::error($ex);
        }
    }
}
