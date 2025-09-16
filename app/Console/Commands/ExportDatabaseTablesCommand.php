<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportDatabaseTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export specific tables from the database';

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
     * @return int
     */
    public function handle()
    {
        // Define Tables to export
        $tables_to_export = ['blacklist', 'meta_tags'];

        // Export file name
        $file_name = "specific_tables.sql";

        // String of table names for the export command
        $tables = implode(" ", $tables_to_export);
        // Fetch credentials of source DB
        $config = config('mysqldump'); // Load configuration from config/mysqldump.php
        $source_db_username = env('SOURCE_DB_USER');
        $source_db_password = env('SOURCE_DB_PASS');
        $source_db_name = env('SOURCE_DB_NAME');

        // Location where the MySQL dump file will be saved
        $dump_file_path = storage_path('app/temp/' . $file_name);

        // Build the mysqldump command
        $dump_command = "mysqldump --login-path=local --skip-lock-tables -u $source_db_username -p$source_db_password $source_db_name --tables " . implode(" ", $tables_to_export) . " > $dump_file_path --no-tablespaces";

        // Execute the mysqldump command
        exec($dump_command, $output, $exitCode);

        // Check if the export was successful
        if ($exitCode === 0) {
            // Record successful export in the dump_exports_import table
            $timestamp = now();
            DB::table('dump_exports_import')->insert([
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'Successful_exports' => 'update',
                'Title' => 'Successfully Completed Export jobs',
                'Url' => $source_db_name,
                'text' => 'source DB',
                'Description' => 'export ' . $file_name,
                'Status' => 'Successful',
            ]);

            // Update export logs in the jobs table
            DB::table('Jobs')->where('url', $source_db_name)->update([
                'status' => 'Successful',
                'updated_at' => $timestamp,
            ]);

            // Log the success
            Log::info("Exported tables to $file_name successfully.");
        } else {
            // Log the error
            Log::error("Error exporting tables: " . implode("\n", $output));

            // You can also add code here to send alerts or notifications to administrators
        }

        return 0;
    }
}