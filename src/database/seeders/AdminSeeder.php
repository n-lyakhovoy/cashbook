<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем супер-админа
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@cashbook.local',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super-admin');
        Setting::create([
            'user_id' => $superAdmin->id,
            'receive_on_intake' => true,
            'receive_on_payout' => true,
        ]);

        // Создаем админа с правами на чтение и запись
        $adminWrite = User::create([
            'name' => 'Administrator Write',
            'email' => 'admin@cashbook.local',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $adminWrite->assignRole('admin-write');
        Setting::create([
            'user_id' => $adminWrite->id,
            'receive_on_intake' => true,
            'receive_on_payout' => true,
        ]);

        // Создаем админа с правами только на чтение
        $adminRead = User::create([
            'name' => 'Administrator Read',
            'email' => 'admin-read@cashbook.local',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $adminRead->assignRole('admin-read');
        Setting::create([
            'user_id' => $adminRead->id,
            'receive_on_intake' => false,
            'receive_on_payout' => false,
        ]);
    }
}
