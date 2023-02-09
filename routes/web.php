<?php

use App\Http\Controllers\Account\AccountCreateController;
use App\Http\Controllers\Account\AccountDeleteController;
use App\Http\Controllers\Account\AccountReadController;
use App\Http\Controllers\Account\AccountUpdateController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cryptocurrency\CryptocurrencyBuyController;
use App\Http\Controllers\Cryptocurrency\CryptocurrencyReadController;
use App\Http\Controllers\Cryptocurrency\CryptocurrencySellController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityCodesController;
use App\Http\Controllers\Transaction\TransactionCreateController;
use App\Http\Controllers\Transaction\TransactionReadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/rates', [ExchangeRateController::class, 'index'])
        ->name('exchange-rates');

    Route::get('/accounts', [AccountReadController::class, 'index'])
        ->name('accounts.index');
    Route::get('/accounts/{account}', [AccountReadController::class, 'show'])
        ->name('account.show');
    Route::get('/accounts/{account}/edit', [AccountUpdateController::class, 'edit'])
        ->name('account.edit');
    Route::put('/accounts/{account}', [AccountUpdateController::class, 'updateForm'])
        ->name('account.update');
    Route::get('/accounts/{account}/close', [AccountDeleteController::class, 'close'])
        ->name('account.close');
    Route::delete('/accounts/{account}', [AccountDeleteController::class, 'destroy'])
        ->name('account.destroy');
    Route::get('/account/create', [AccountCreateController::class, 'create'])
        ->name('account.create');
    Route::post('/account/create', [AccountCreateController::class, 'store'])
        ->name('account.store');

    Route::get('/transactions', [TransactionReadController::class, 'index'])
        ->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionReadController::class, 'show'])
        ->name('transaction.show');
    Route::get('/transaction/new', [TransactionCreateController::class, 'create'])
        ->name('transaction.create');
    Route::post('/transaction/new', [TransactionCreateController::class, 'store'])
        ->name('transaction.store');

    Route::get('/cryptocurrencies', [CryptocurrencyReadController::class, 'index'])
        ->name('cryptocurrencies.index');
    Route::get('/cryptocurrencies/{symbol}', [CryptocurrencyReadController::class, 'show'])
        ->name('cryptocurrency.show');
    Route::get('/cryptocurrencies/{symbol}/buy', [CryptocurrencyBuyController::class, 'show'])
        ->name('cryptocurrency.buyForm');
    Route::post('/cryptocurrencies/{symbol}/buy', [CryptocurrencyBuyController::class, 'buy'])
        ->name('cryptocurrency.buy');
    Route::get('/cryptocurrencies/{symbol}/sell', [CryptocurrencySellController::class, 'show'])
        ->name('cryptocurrency.sellForm');
    Route::post('/cryptocurrencies/{symbol}/sell', [CryptocurrencySellController::class, 'sell'])
        ->name('cryptocurrency.sell');

    Route::get('/codes/update', [SecurityCodesController::class, 'updateForm'])
        ->name('codes.update');
    Route::put('/codes/generate', [SecurityCodesController::class, 'update'])
        ->name('codes.generate');
    Route::middleware('prevent-back-history')
        ->get('/codes', [SecurityCodesController::class, 'index'])
        ->name('codes.index');
});

require __DIR__ . '/auth.php';
