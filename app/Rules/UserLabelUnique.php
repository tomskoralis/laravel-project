<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class UserLabelUnique implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        if (
            $value &&
            auth()->user()->accounts()->whereNull('closed_at')->get()->contains('label', $value)
        ) {
            $fail('The :attribute must be unique.');
        }
    }
}
