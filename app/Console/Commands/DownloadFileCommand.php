<?php

namespace App\Console\Commands;

use App\Http\Controllers\FileHandlerController;
use Illuminate\Console\Command;

class DownloadFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:file {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download files command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $info = ( new FileHandlerController() )->downloadLink( request(), $this->argument('url') );
        $this->info( $info );
    }
}
