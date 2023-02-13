<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\SecurityCode;
use App\Models\User;
use App\Rules\SecurityCodeValid;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use function auth;

class AccountDeleteController extends Controller
{
    public function close(Account $account): View
    {
        /** @var User $user */
        $user = auth()->user();
        if (
            $account->user_id !== $user->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        $securityCodeNumber = SecurityCode::query()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->limit(1)
            ->get()
            ->value('number');
        session(['securityCodeNumber' => $securityCodeNumber]);

        return view('account.close')->with([
            'account' => $account,
            'securityCodeNumber' => $securityCodeNumber,
        ]);
    }

    public function destroy(Request $request, Account $account): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (
            $account->user_id !== $user->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        $request->validateWithBag('accountDeletion', [
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        if ((float)$account->balance !== 0.0) {
            return back()->with('status', 'account-not-empty');
        }

        $account->closed_at = Carbon::now();
        $account->save();

        return Redirect::to(route('accounts.index'))
            ->with('status', 'account-closed');
    }
}
