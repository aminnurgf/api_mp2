<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Membuat admin default: admin / admin123
     */
    public function run()
    {
        Admin::firstOrCreate(
            ['username' => 'admin'],
            ['password' => 'admin123']
        );
    }
}

