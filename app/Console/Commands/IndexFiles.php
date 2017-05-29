<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IndexFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts an index of files in S3';

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
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new \App\Jobs\IndexFiles());
    }
}
