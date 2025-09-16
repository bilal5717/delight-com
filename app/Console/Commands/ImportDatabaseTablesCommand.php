<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportDatabaseTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:database-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import database tables from dump files';

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
        // Initialize the application
        // Check for successful export logs
        // Implement the logic for this part
        
        // Example code to simulate successful export logs check
        $successfulExportLogs = DB::table('dump_exports_import')
            ->where('status', 'Successful')
            ->get();
            
        // Retrieve the file name to import
        $fileName = "specific_tables.sql";
        $dumpFilePath = storage_path('app/temp/' . $fileName);
        
        // Read the SQL dump file
        $sql = file_get_contents($dumpFilePath);        

        // Get the list of tables from the SQL dump file
        preg_match_all('/CREATE TABLE `(.*?)`/s', $sql, $matches);
        $tablesInDump = $matches[1];

        // Get the list of all tables in the database
        $allTables = SELF::getTableNames();
        
        // Determine which tables to ignore during the import
        $tablesToIgnore = array_diff($allTables, $tablesInDump);        

        // Retrieve the list of tables to ignore during the import, in this case all tables, except the list of tables exported in export command.
       // $tablesToIgnore = ['table_to_ignore_1', 'table_to_ignore_2'];            

        foreach ($successfulExportLogs as $log) {
            // Import process for each successful export log
            $this->importDatabaseTables($log->url, $allTables, $tablesInDump);
        }

        return 0;
    }

    /**
     * Import database tables for a given database.
     *
     * @param string $databaseName
     */
    private function importDatabaseTables($databaseName, $allTables, $tablesInDump)
    {
        // Retrieve target database credentials from configuration file
        $databaseConfig = config("mysqldump.databases.$databaseName");

        if (!$databaseConfig) {
            $this->error("Database configuration not found for '$databaseName'.");
            return;
        }
        
        // Retrieve the list of tables to ignore during the import, in this case all tables, except the list of tables exported in export command.
        //$tablesToIgnore = ['table_to_ignore_1', 'table_to_ignore_2']; // update table names or use following commmand
        $tablesToIgnore = array_diff($allTables, $tablesInDump); 

        // Retrieve the file name to import
        $fileName = "specific_tables.sql";
        $dumpFilePath = storage_path('app/temp/' . $fileName);

        // Schedule import job for the target database
        $job = [
            'title' => 'Import Database Tables',
            'queue' => 'imports',
            'url' => $databaseName,
            'text' => 'Update tables for Asia',
            'description' => 'Import specific tables SQL',
            'retry_attempts' => 0, // Initial retry attempts
            'status' => 'Pending',
            'timestamps' => Carbon::now(),
        ];

        DB::table('jobs')->insert($job);

        // import logic 
        try {
            // Start a database transaction
            DB::beginTransaction();
            
            // Read the SQL dump file
            $sql = file_get_contents($dumpFilePath); 
            
            // Determine which tables to ignore during the import
           $tablesToIgnore = array_diff($allTables, $tablesInDump);         
            
            // Split the SQL dump into individual queries
            $queries = explode(';', $sql); 
            
            // Define the delay between each import (e.g., 10 minutes)
            $delayBetweenImports = 10; // in minutes            
            
            foreach ($queries as $query) {
                // Remove leading/trailing white spaces and empty queries
                $query = trim($query);
                if (!empty($query)) {
                    // Execute each query individually
                    DB::connection($databaseName)->statement($query);            
                }
            }

            // You should add your import logic here

            // If import is successful
            DB::table('dump_exports_import')->insert([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'Successful_imports' => 'update status',
                'url' => $databaseName,
                'text' => 'Update tables for Asia',
                'description' => 'Import specific tables SQL',
                'status' => 'Successful',
            ]);

            // Commit the transaction on success
            DB::commit();

            // Update the job status to completed
            DB::table('jobs')->where('url', $databaseName)->update(['status' => 'Completed']);
        } catch (\Exception $e) {
            // Handle import errors

            // Log the error and increment retry attempts
            $this->logError($databaseName, $e->getMessage());

            // Check if retry attempts are within the limit
            if ($job['retry_attempts'] < 5) {
                // Schedule a retry job with a delay
                $this->scheduleRetry($job);
            } else {
                // Mark the job as failed if retry limit is reached
                DB::table('jobs')->where('url', $databaseName)->update(['status' => 'Failed']);
            }

            // Rollback the transaction on error
            DB::rollBack();
        }
    }

    /**
     * Log errors and retry attempts.
     *
     * @param string $databaseName
     * @param string $errorMessage
     */
    private function logError($databaseName, $errorMessage)
    {
        // Log the error message with context (job ID, timestamp, etc.)
        // You should use Laravel's logging facilities or Monolog here
        // Example: Log::error("Error importing tables for $databaseName: $errorMessage");
    }

    /**
     * Schedule a retry job for a failed import.
     *
     * @param array $job
     */
    private function scheduleRetry($job)
    {
        // Implement logic to schedule a retry job with a 30-minute delay
        // You can use Laravel's task scheduling or a queue system for this purpose
        // Example: Queue::later(1800, new RetryImportJob($job));
    }

    public function getTableNames(){
        try{
            $tables = DB::select('SHOW TABLES');
            foreach($tables as $key => $table){
                $table_names[] = $table->Tables_in_azfactor_mp; // Table_in_{{your database name in my case database name is devtrigger}}
            }
            return $table_names;
        }
        catch(\Exception $e){
            return false;
        }
    }
}
