<?php

namespace App\Jobs;

use Log;
use App\Models\Content;
use App\Models\ContentTag;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateContentTags extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $content;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->content;
        if ( $content ) {
            Log::info("Start Gen Tags For : $content->title");
            $content->syncTags();
            Log::info("End Gen Tags.");
        }
    }
}
