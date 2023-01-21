<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'timezone' => $request->timezone,
        ]);

        $user->securityCodes()->createMany($this->generateSecurityCodes(20));

        event(new Registered($user));

        $account = (new Account)->fill([
            'number' => 'LV' . $this->generateAccountNumber(),
            'currency' => 'EUR',
            'user_id' => User::where('email', $request->email)->get()->value('id')
        ]);
        $account->user()->associate($user);
        $account->save();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    private function generateSecurityCodes(int $count): array
    {
        $securityCodes = [];
        for ($number = 1; $number <= $count; $number++) {
            $securityCodes [] = [
                'number' => $number,
                'code' => Hash::make(Str::random(8)),
            ];
        }
        return $securityCodes;
    }

    private function generateAccountNumber(): int
    {
        $number = mt_rand(100000000, 999999999);
        if (Account::where('number', $number)->exists()) {
            return $this->generateAccountNumber();
        }
        return $number;
    }
}
