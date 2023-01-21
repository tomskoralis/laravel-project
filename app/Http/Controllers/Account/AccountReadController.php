<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\View\View;
use function auth;

class AccountReadController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->whereNull('closed_at')->get();

        $accountsHaveLabel = false;
        foreach ($accounts as $account) {
            if ($account->label !== null) {
                $accountsHaveLabel = true;
                break;
            }
        }

        return view('account.index')
            ->with([
                'accounts' => $accounts,
                'accountsHaveLabel' => $accountsHaveLabel
            ]);
    }

    public function show(Account $account): View
    {
        if (
            $account->user_id !== auth()->user()->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        return view('account.show')
            ->with([
                'account' => $account
            ]);
    }
}
