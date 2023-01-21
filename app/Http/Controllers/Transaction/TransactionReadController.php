<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function auth;

class TransactionReadController extends Controller
{
    public function index(Request $request): View
    {
        $userAccounts = auth()->user()->accounts()->whereNull('closed_at');

        $transactions = Transaction::where(function (Builder $query) use ($userAccounts) {
            $accountIds = $userAccounts->pluck('id')->toArray();
            return $query
                ->whereIn('to_account_id', $accountIds)
                ->orWhereIn('from_account_id', $accountIds);
        });

        $accountId = $request->input('account_id');
        $cryptoAccountId = $request->input('crypto_account_id');

        if ($request->input('crypto')) {
            if ($cryptoAccountId === null || $cryptoAccountId === 'crypto') {
                $transactions = $transactions->where(function (Builder $query) use ($userAccounts) {
                    $accountIds = $userAccounts->where('type', 'crypto')->pluck('id')->toArray();
                    return $query
                        ->whereIn('from_account_id', $accountIds)
                        ->orWhereIn('to_account_id', $accountIds);
                });
            } else {
                $transactions = $transactions->where(function (Builder $query) use ($cryptoAccountId) {
                    return $query
                        ->where('from_account_id', $cryptoAccountId)
                        ->orWhere('to_account_id', $cryptoAccountId);
                });
            }
        } else {
            $accountIds = $userAccounts->where('type', 'regular')->pluck('id')->toArray();
            $externalAccountIds = Account::whereNotIn('id', auth()->user()->accounts()->pluck('id')->toArray())
                ->pluck('id')
                ->toArray();
            $transactions = $transactions->where(function (Builder $query) use ($accountIds, $externalAccountIds) {
                return $query
                    ->whereIn('from_account_id', $accountIds)
                    ->whereIn('to_account_id', $accountIds)
                    ->orWhere(function (Builder $query) use ($accountIds, $externalAccountIds) {
                        return $query
                            ->whereIn('from_account_id', $accountIds)
                            ->whereIn('to_account_id', $externalAccountIds);
                    });
            });
            if ($accountId !== null && $accountId !== 'regular') {
                $transactions = $transactions->where(function (Builder $query) use ($accountId, $externalAccountIds) {
                    return $query
                        ->where('from_account_id', $accountId)
                        ->orWhere('to_account_id', $accountId);
                });
            }
        }

        if ($request->input('date_from')) {
            $dayFrom = Carbon::createFromFormat(
                'Y-m-d',
                $request->input('date_from'),
                auth()->user()->timezone ?? 'UTC'
            )->startOfDay()->setTimezone('UTC');
            $transactions = $transactions->where('time', '>', $dayFrom);
        }

        if ($request->input('date_to')) {
            $dayTo = Carbon::createFromFormat(
                'Y-m-d',
                $request->input('date_to'),
                auth()->user()->timezone ?? 'UTC'
            )->endofDay()->setTimezone('UTC');
            $transactions = $transactions->where('time', '<', $dayTo);
        }

        if ($request->input('from_user_name')) {
            $transactions = $transactions->whereIn(
                'from_account_id',
                Account::whereIn(
                    'user_id',
                    User::where(
                        DB::raw('LOWER(name)'),
                        'LIKE',
                        '%' . strtolower($request->input('from_user_name')) . '%'
                    )
                        ->pluck('id')
                        ->toArray()
                )->pluck('id')->toArray()
            );
        }

        if ($request->input('to_user_name')) {
            $transactions = $transactions->whereIn(
                'to_account_id',
                Account::whereIn(
                    'user_id',
                    User::where(
                        DB::raw('LOWER(name)'),
                        'LIKE',
                        '%' . strtolower($request->input('to_user_name')) . '%'
                    )
                        ->pluck('id')
                        ->toArray()
                )->pluck('id')->toArray()
            );
        }

        $query = $request->all();
        $accounts = auth()->user()->accounts()->whereNull('closed_at')->where('type', 'regular')->get();
        $cryptoAccounts = auth()->user()->accounts()->whereNull('closed_at')->where('type', 'crypto')->get();
        $transactions = $transactions->orderByDesc('id')->get();

        return view('transaction.index')
            ->with([
                'query' => $query,
                'accounts' => $accounts,
                'cryptoAccounts' => $cryptoAccounts,
                'transactions' => $transactions
            ]);
    }

    public function show(Transaction $transaction): View
    {
        if (
            Account::findOrFail($transaction->from_account_id)->user_id !== auth()->user()->id ||
            Account::findOrFail($transaction->to_account_id)->user_id !== auth()->user()->id
        ) {
            abort(403);
        }

        return view('transaction.show')
            ->with([
                'transaction' => $transaction
            ]);
    }
}
