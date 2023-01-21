<?php

namespace Database\Factories;

use App\Models\SecurityCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<SecurityCode>
 */
class SecurityCodeFactory extends Factory
{
    private static int $number = 1;

    public function definition(): array
    {
        if (self::$number === $this->count + 1) {
            self::$number = 1;
        }
        return [
            'number' => self::$number++,
            'code' => Hash::make(Str::random(8)),
        ];
    }
}
