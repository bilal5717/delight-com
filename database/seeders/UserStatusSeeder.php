<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_status')->insert([
            [
                'id' => 1,
                'title' => 'Verified',
                'icon' => 'verified.png',
                'status' => 'verified',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'New',
                'icon' => 'new.png',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'Pending',
                'icon' => 'pending.png',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'Not Verified',
                'icon' => 'not-verified.png',
                'status' => 'not verified',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'Premium',
                'icon' => 'premium.png',
                'status' => 'premium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
