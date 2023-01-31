<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;

class EmailValid implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if (User::whereNull('deleted_at')->where('email', $value)->doesntExist()) {
            $fail('These credentials do not match our records.');
        }
    }
}
