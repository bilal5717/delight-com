<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Swoole\Process;


class SwooleAutostartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:autostart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the Swoole HTTP server automatically';

    // ...

    public function handle()
    {
        // Fetch server configuration values from .env file
        $host = env('SWOOLE_HTTP_HOST', '0.0.0.0');
        $port = env('SWOOLE_HTTP_PORT', 8000);
        $workerNum = env('SWOOLE_WORKER_NUM', 4);

        // Create a new Swoole HTTP server
        // ...

        $this->info('Swoole server started successfully.');
    }
}
