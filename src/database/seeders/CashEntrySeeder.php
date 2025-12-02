<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CashEntry;
use App\Models\User;

class CashEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@cashbook.local')->first();

        $entries = [
            [
                'amount' => 100000,
                'source' => 'Продажа товаров',
                'received_at' => now()->subDays(5),
                'admin_id' => $admin->id,
            ],
            [
                'amount' => 50000,
                'source' => 'Услуги',
                'received_at' => now()->subDays(3),
                'admin_id' => $admin->id,
            ],
            [
                'amount' => 75000,
                'source' => 'Консультации',
                'received_at' => now()->subDay(),
                'admin_id' => $admin->id,
            ],
        ];

        foreach ($entries as $entry) {
            CashEntry::create($entry);
        }
    }
}
