<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Rules\UserLabelUnique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function auth;

class AccountUpdateController extends Controller
{
    public function edit(Account $account): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (
            $account->user_id !== $user->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        return view('account.edit-label')
            ->with('account', $account);
    }

    public function updateForm(Request $request, Account $account): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (
            $account->user_id !== $user->id ||
            $account->closed_at !== null
        ) {
            abort(403);
        }

        $validated = $request->validateWithBag('labelUpdating', [
            'label' => [
                'max:63',
                'string',
                new UserLabelUnique,
            ],
        ]);

        $account->update([
            'label' => $validated['label'],
        ]);

        return back()->with('status', 'label-updated');
    }
}
