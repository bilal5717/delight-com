<?php

namespace Database\Seeders;

use App\Models\SliderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderImageMonthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            $entries = [
                [
                    'month'                => 'January', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'February', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'March', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'April', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'May', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'June', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'July', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'August', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'September', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'October', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'November', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ],
                [
                    'month'                => 'December', 
                    'url'                  => null,
                    'created_at'           => now()->format('Y-m-d H:i:s'),
                    'updated_at'           => now()->format('Y-m-d H:i:s'),
                ]
            ];
            
            $tableName = (new SliderImage())->getTable();
            foreach ($entries as $entry) {
                $entry = arrayTranslationsToJson($entry);
                $entryId = DB::table($tableName)->insertGetId($entry);
            }
        }
    }
}
