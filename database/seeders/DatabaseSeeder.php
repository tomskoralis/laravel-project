<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\SecurityCode;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)
            ->has(Account::factory()->state(['currency' => 'EUR']))
            ->has(Account::factory(1))
            ->has(SecurityCode::factory(20))
            ->create();
        Transaction::factory(50)->create();
    }
}
