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

    /**
     * @var bool
     */
    public $outputErrorsToConsole;

    public function __construct(Url $url)
    {
        $this->url = $url;
        $this->outputErrorsToConsole = env('APP_DEBUG');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->url->state = Url::PROCESSING;
            $this->url->save();

            $analyzer = new Analyzer();

            $response = $analyzer->requestData($this->url->address);
            $this->url->update($response);

            if ($response['body']) {
                try {
                    $pageInfo = $analyzer->extractPageInfo($response['body']);
                    $this->url->update($pageInfo);
                } catch (\Throwable $ex) {
                    $this->logError($ex);
                }
            }

            $this->url->state = Url::SUCCEEDED;
            $this->url->save();
        } catch (\Throwable $ex) {
            $this->url->state = Url::FAILED;
            $this->url->save();

            $this->logError($ex);
        }
    }

    private function logError($ex)
    {
        Log::error($ex);
        if ($this->outputErrorsToConsole) {
            var_dump($ex);
        }
    }
}
