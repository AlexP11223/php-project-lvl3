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

            $response = $analyzer->requestData($this->url->address);
            $this->url->update($response);

            if ($response['body']) {
                try {
                    $pageInfo = $analyzer->extractPageInfo($response['body']);
                    $this->url->update($pageInfo);
                } catch (\Throwable $ex) {
                    self::logError($ex);
                }
            }

            $this->url->setState(Url::SUCCEEDED);
        } catch (\Throwable $ex) {
            $this->url->setState(Url::FAILED);

            self::logError($ex);
        }
    }

    private function logError($ex)
    {
        Log::error($ex);
        if (env('APP_DEBUG')) {
            var_dump($ex);
        }
    }
}
