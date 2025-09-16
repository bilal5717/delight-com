<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceSettingsInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceSettings = [
            ['setting_key' => 'calendar_help_text', 'setting_value' => '{"en":"<p>This is the calendar help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'time_help_text', 'setting_value' => '{"en":"<p>This is the time help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'buffer_help_text', 'setting_value' => '{"en":"<p>This is the buffer help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'inventory_help_text', 'setting_value' => '{"en":"<p>This is the inventory help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'cancellation_help_text', 'setting_value' => '{"en":"<p>This is the cancellation help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'package_type_help_text', 'setting_value' => '{"en":"<p>This is the package type help text for services.<\/p>"}', 'active' => 1],
            ['setting_key' => 'default_package_text_limit', 'setting_value' => '{"en":"<p>1000<\/p>"}', 'active' => 1],
        ];

        DB::table('service_settings_info')->truncate();
        DB::table('service_settings_info')->insert($serviceSettings);
    }
}