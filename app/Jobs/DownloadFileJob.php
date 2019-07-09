<?php

namespace App\Jobs;

use App\FileHandlersModel;
use App\Http\Controllers\FileHandlerController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DownloadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * DownloadFileJob constructor.
     * Create a new job instance.
     * @param FileHandlersModel $file
     * @return void
     */
    public function __construct( FileHandlersModel $file )
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ( new FileHandlerController )->downloadFile( $this->file );
    }
}
