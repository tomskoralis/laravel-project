<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $from = Account::inRandomOrder()->limit(1)->get()->first();
        return [
            'outgoing_amount' => fake()->randomFloat(2, 0.01, ($from->balance/10)),
            'from_account_id' => $from->id,
            'to_account_id' => Account::inRandomOrder()->whereNot('id', $from->id)->get()->first()->id,
        ];
    }
}
