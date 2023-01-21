<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Hash;

class SecurityCodeValid implements InvokableRule
{
    protected array $data = [];

    public function __invoke($attribute, $value, $fail): void
    {
        if (
            !Hash::check(
                $value,
                auth()->user()->securityCodes()->where(
                    'number',
                    session()->pull('securityCodeNumber')
                )->get()->value('code')
            )
        ) {
            $fail('The :attribute is invalid.');
        }
    }

    public function setData($data): static
    {
        $this->data = $data;
        return $this;
    }
}
