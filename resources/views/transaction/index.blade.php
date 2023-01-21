<x-app-layout :title="'Transactions'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Transactions')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="w-fit flex content-center mb-6">
                        <form method="get" action="{{route('transactions.index')}}" class="flex flex-wrap gap-2"
                              x-data="{show: @js($query['crypto'] ?? false)}">

                            <div class="flex content-center gap-1">
                                <x-input-label for="cryptoCheckbox">Crypto</x-input-label>
                                <input id="cryptoCheckbox" type="checkbox" name="crypto" x-model="show"
                                       class="w-4 h-4 self-center text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                       @if($query['crypto'] ?? false) checked @endif>
                            </div>

                            <div :class="{ 'hidden': show }">
                                <x-select name="account_id" id="account_id" class="w-48">
                                    <option value="regular"
                                            @if(($query['account_id'] ?? 'regular') == 'regular') selected @endif>
                                        {{__('Regular accounts')}}
                                    </option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}"
                                                @if(($query['account_id'] ?? '') == $account->id) selected @endif>
                                            {{$account->label ?? $account->number}}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div :class="{ 'hidden': !show }">
                                <x-select name="crypto_account_id" id="crypto_account_id" class="w-48">
                                    <option value="crypto"
                                            @if(($query['crypto_account_id'] ?? 'crypto') == 'crypto') selected @endif>
                                        {{__('Crypto accounts')}}
                                    </option>
                                    @foreach($cryptoAccounts as $account)
                                        <option value="{{$account->id}}"
                                                @if(($query['crypto_account_id'] ?? '') == $account->id) selected @endif>
                                            {{$account->label ?? $account->number}}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div>
                                <x-text-input id="from_user_name" name="from_user_name" type="text" class="w-44 block"
                                              placeholder="{{__('Sender name')}}"
                                              value="{{$query['from_user_name'] ?? ''}}"
                                              autocomplete="from_user_name"/>
                            </div>

                            <div>
                                <x-text-input id="to_user_name" name="to_user_name" type="text" class="w-44 block"
                                              placeholder="{{__('Recipient name')}}"
                                              value="{{$query['to_user_name'] ?? ''}}"
                                              autocomplete="to_user_name"/>
                            </div>

                            <div>
                                <input id="date_from" name="date_from" type="date" value="{{$query['date_from'] ?? ''}}"
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm dark:[color-scheme:dark]"/>
                            </div>

                            <x-input-label for="date_to" :value="__('to')"/>

                            <div>
                                <input id="date_to" name="date_to" type="date" value="{{$query['date_to'] ?? ''}}"
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm dark:[color-scheme:dark]"/>
                            </div>

                            <x-primary-button>{{__('Search')}}</x-primary-button>
                        </form>
                    </div>

                    @if($transactions->isEmpty())
                        <p class="w-fit px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                            {{__('No transactions found!')}}
                        </p>
                    @else
                        <div class="max-w-4xl">
                            <div class="max-w-4xl text-right dark:text-white grid
                                 grid-cols-[minmax(2rem,_auto)_minmax(4rem,_auto)_minmax(7rem,_auto)_minmax(8rem,_auto)_minmax(8rem,_auto)_minmax(6rem,_auto)]">
                                <div class="pr-3 font-bold text-left flex items-end">
                                    &#8470;
                                </div>
                                <div class="pr-3 font-bold flex items-end justify-end">
                                    {{__('Amount')}}
                                </div>
                                <div class="pr-3 font-bold flex items-end justify-end">
                                    {{__('Type')}}
                                </div>
                                <div class="pr-3 font-bold flex items-end justify-end">
                                    {{__('From')}}
                                </div>
                                <div class="pr-3 font-bold flex items-end justify-end">
                                    {{__('To')}}
                                </div>
                                <div class="font-bold flex items-end justify-end">
                                    {{__('Time')}}
                                </div>
                                @foreach($transactions as $key => $transaction)
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                        {{$key + 1}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 break-all">
                                        {{$transaction->amountFormatted}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->type}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->fromAccountNumber}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->toAccountNumber}}
                                    </div>
                                    <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->timestampFormatted}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
