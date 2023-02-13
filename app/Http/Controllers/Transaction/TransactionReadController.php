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
    private const COUNT_PER_PAGE = 15;

    public function index(Request $request): View
    {
        $query = $request->all();

        /** @var User $user */
        $user = auth()->user();

        $userAccounts = $user->accounts()->whereNull('closed_at');
        $accountId = $request->input('account_id');
        $cryptoAccountId = $request->input('crypto_account_id');

        if ($request->input('crypto')) {
            if ($cryptoAccountId === null || $cryptoAccountId === 'crypto') {
                $transactions = Transaction::query()
                    ->where(function (Builder $query) use ($userAccounts) {
                        $cryptoAccountIds = $userAccounts
                            ->where('type', 'crypto')
                            ->pluck('id')
                            ->toArray();
                        return $query
                            ->whereIn('from_account_id', $cryptoAccountIds)
                            ->orWhereIn('to_account_id', $cryptoAccountIds);
                    });
            } else {
                $transactions = Transaction::where(function (Builder $query) use ($cryptoAccountId) {
                    return $query
                        ->where('from_account_id', $cryptoAccountId)
                        ->orWhere('to_account_id', $cryptoAccountId);
                });
            }
        } else {
            $transactions = Transaction::query()->where(function (Builder $query) use ($userAccounts) {
                $accountIds = $userAccounts->where('type', 'regular')
                    ->pluck('id')
                    ->toArray();
                $externalAccountIds = Account::query()
                    ->where('type', 'regular')
                    ->whereNotIn('id', $userAccounts->pluck('id')->toArray())
                    ->pluck('id')
                    ->toArray();
                return $query
                    ->where(function (Builder $query) use ($accountIds, $externalAccountIds) {
                        return $query
                            ->whereIn('from_account_id', $accountIds)
                            ->whereIn('to_account_id', $externalAccountIds);
                    })->orWhere(function (Builder $query) use ($accountIds, $externalAccountIds) {
                        return $query
                            ->whereIn('from_account_id', $externalAccountIds)
                            ->whereIn('to_account_id', $accountIds);
                    })->orWhere(function (Builder $query) use ($accountIds, $externalAccountIds) {
                        return $query
                            ->whereIn('from_account_id', $accountIds)
                            ->whereIn('to_account_id', $accountIds);
                    });
            });
            if ($accountId !== null && $accountId !== 'regular') {
                $transactions = $transactions->where(function (Builder $query) use ($accountId) {
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
                $user->timezone ?? 'UTC'
            )->startOfDay()->setTimezone('UTC');

            if ($dayFrom->lessThan(now()->subYear())) {
                $dayFrom = now()->subYear()->format('Y-m-d');
                $query['date_from'] = $dayFrom;
            }
            $transactions = $transactions->where('time', '>', $dayFrom);
        }

        if ($request->input('date_to')) {
            $dayTo = Carbon::createFromFormat(
                'Y-m-d',
                $request->input('date_to'),
                $user->timezone ?? 'UTC'
            )->endofDay()->setTimezone('UTC');

            if ($dayTo->greaterThan(now())) {
                $dayTo = now();
                $query['date_to'] = $dayTo->format('Y-m-d');
            }
            $transactions = $transactions->where('time', '<', $dayTo);
        }

        if ($request->input('from_user_name')) {
            $transactions = $transactions->whereIn(
                'from_account_id',
                Account::query()
                    ->whereIn(
                        'user_id',
                        User::query()
                            ->where(
                                DB::raw('LOWER(name)'),
                                'LIKE',
                                '%' . strtolower($request->input('from_user_name')) . '%'
                            )
                            ->pluck('id')
                            ->toArray()
                    )
                    ->pluck('id')
                    ->toArray()
            );
        }

        if ($request->input('to_user_name')) {
            $transactions = $transactions->whereIn(
                'to_account_id',
                Account::query()
                    ->whereIn(
                        'user_id',
                        User::query()
                            ->where(
                                DB::raw('LOWER(name)'),
                                'LIKE',
                                '%' . strtolower($request->input('to_user_name')) . '%'
                            )
                            ->pluck('id')
                            ->toArray()
                    )
                    ->pluck('id')
                    ->toArray()
            );
        }

        $accounts = $user->accounts()
            ->whereNull('closed_at')
            ->where('type', 'regular')
            ->get();
        $cryptoAccounts = $user->accounts()
            ->whereNull('closed_at')
            ->where('type', 'crypto')
            ->get();
        $transactions = $transactions->orderByDesc('id')
            ->paginate(self::COUNT_PER_PAGE);

        return view('transaction.index')
            ->with([
                'query' => $query,
                'accounts' => $accounts,
                'cryptoAccounts' => $cryptoAccounts,
                'transactions' => $transactions,
                'countPerPage' => self::COUNT_PER_PAGE,
            ]);
    }

    public function show(Transaction $transaction): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (
            Account::query()->find($transaction->from_account_id)->user_id !== $user->id &&
            Account::query()->find($transaction->to_account_id)->user_id !== $user->id
        ) {
            abort(403);
        }

        return view('transaction.show')
            ->with([
                'transaction' => $transaction
            ]);
    }
}
