<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\SecurityCodeValid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function auth;

class TransactionCreateController extends Controller
{
    public function create(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $accounts = $user->accounts()
            ->whereNull('closed_at')
            ->get();
        $securityCodeNumber = $user->securityCodes()
            ->inRandomOrder()
            ->limit(1)
            ->get()
            ->value('number');
        session(['securityCodeNumber' => $securityCodeNumber]);

        return view('transaction.create')->with([
            'accounts' => $accounts,
            'securityCodeNumber' => $securityCodeNumber,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $fromAccount = $user->accounts()
            ->whereNull('closed_at')
            ->findOrFail($request->from_account_id);

        if ($fromAccount->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validateWithBag('storeTransaction', [
            'from_account_id' => [
                'required',
                'numeric',
                'exists:accounts,id',
            ],
            'to_account_number' => [
                'required',
                'string',
                'exists:accounts,number',
                'not_in:' . Account::query()
                    ->where('id', $request->input('from_account_id'))
                    ->get()
                    ->value('number'),
                'in:' . Account::query()
                    ->where('number', $request->input('to_account_number'))
                    ->whereNull('closed_at')
                    ->get()
                    ->value('number'),
            ],
            'amount' => [
                'required',
                'numeric',
                $fromAccount->type === 'regular'
                    ? 'regex:/^\d*(?:\.\d{1,2})?$/'
                    : 'regex:/^\d*(?:\.\d{1,8})?$/',
                $fromAccount->type === 'regular'
                    ? 'min:0.01'
                    : 'min:0.00000001',
                'max:' . $fromAccount->balance
            ],
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        $toAccount = Account::query()
            ->where('number', $validated['to_account_number'])
            ->whereNull('closed_at')
            ->firstOrFail();

        Transaction::query()
            ->create([
                'outgoing_amount' => $validated['amount'],
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
            ]);

        return redirect()->back()->with('status', 'transaction-successful');
    }
}
