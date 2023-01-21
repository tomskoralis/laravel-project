<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\SecurityCode;
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
        if (
            $account->user_id !== auth()->user()->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        $securityCodeNumber = SecurityCode::where('user_id', auth()->user()->id)
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
        if (
            $account->user_id !== auth()->user()->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        if ((float)$account->balance !== 0.0) {
            return back()->with('status', 'account-not-empty');
        }

        $request->validateWithBag('accountDeletion', [
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        $account->closed_at = Carbon::now();
        $account->save();

        return Redirect::to(route('accounts.index'))->with('status', 'account-closed');
    }
}
