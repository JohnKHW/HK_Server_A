<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = [
            'username' => 'admin',
            'password' => 'adminpass',
            'role' => 'ADMIN',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
        $data[] = [
            'username' => 'user1',
            'password' => 'user1pass',
            'role' => 'USER',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
        $data[] = [
            'username' => 'user2',
            'password' => 'user2pass',
            'role' => 'USER',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
        $chunks = array_chunk($data, 3);
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }
    }
}
