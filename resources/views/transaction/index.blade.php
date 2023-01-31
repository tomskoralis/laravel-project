<x-app-layout :title="'Transactions'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Transactions')}}
        </h2>
    </x-slot>

    <div class="py-6"
         x-data="{
            url: new URL(window.location.href),
            show: new URLSearchParams(location.search).get('crypto') || false,
            page: new URLSearchParams(location.search).get('page') || 1
         }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-fit flex content-center">
                    <form method="get" action="{{route('transactions.index')}}"
                          class="max-w-xs lg:max-w-none flex flex-col flex-wrap sm:flex-row items-start sm:items-center gap-x-1 gap-y-1 sm:gap-y-2">

                        <div class="flex content-center gap-1">
                            <input id="cryptoCheckbox" type="checkbox" name="crypto" x-model="show"
                                   class="w-5 h-5 self-center bg-gray-200 border-gray-400 rounded dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                   @if($query['crypto'] ?? false) checked @endif>
                            <label for="cryptoCheckbox" class="flex content-center">Crypto</label>
                        </div>

                        <div class="hidden" :class="{ 'hidden': show }">
                            <x-select name="account_id" id="account_id" class="w-40">
                                <option value="regular"
                                        @if(($query['account_id'] ?? 'regular') == 'regular') selected @endif>
                                    {{__('Standard')}}
                                </option>
                                @foreach($accounts as $account)
                                    <option value="{{$account->id}}"
                                            @if(($query['account_id'] ?? '') == $account->id) selected @endif>
                                        {{$account->label ?? $account->number}}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="hidden" :class="{ 'hidden': !show }">
                            <x-select name="crypto_account_id" id="crypto_account_id" class="w-40">
                                <option value="crypto"
                                        @if(($query['crypto_account_id'] ?? 'crypto') == 'crypto') selected @endif>
                                    {{__('Crypto')}}
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
                            <x-text-input id="from_user_name" name="from_user_name" type="text" class="w-36 block"
                                          placeholder="{{__('Sender name')}}"
                                          value="{{$query['from_user_name'] ?? ''}}"
                                          autocomplete="from_user_name"/>
                        </div>

                        <div>
                            <x-text-input id="to_user_name" name="to_user_name" type="text" class="w-36 block"
                                          placeholder="{{__('Recipient name')}}"
                                          value="{{$query['to_user_name'] ?? ''}}"
                                          autocomplete="to_user_name"/>
                        </div>

                        <label>
                            <input id="date_from" name="date_from" type="date" value="{{$query['date_from'] ?? ''}}"
                                   placeholder="Date from"
                                   class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"/>
                        </label>

                        <label>
                            <input id="date_to" name="date_to" type="date" value="{{$query['date_to'] ?? ''}}"
                                   placeholder="Date to"
                                   class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"/>
                        </label>

                        <x-primary-button>{{__('Search')}}</x-primary-button>

                        <script>
                            flatpickr('#date_from');
                            flatpickr('#date_to');
                        </script>
                    </form>
                </div>
            </div>

            <div
                class="mt-4 p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @if($transactions->isEmpty())
                    <p class="w-fit px-4 py-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No transactions found!')}}
                    </p>
                @else
                    <div class="w-fit">
                        <ul>
                            @foreach($transactions as $key => $transaction)
                                <li class="my-2 sm:flex sm:gap-1">
                                    <p class="font-semibold">
                                        {{($transactions->currentPage() - 1) * $countPerPage + $key + 1}}:
                                        {{$transaction->type}} {{$transaction->amountFormatted}}
                                    </p>
                                    @if(
                                        !isset($query['account_id']) ||
                                        $transaction->from_account_id != $query['account_id']
                                        )
                                        <p class="sm:ml-0 ml-4">
                                            @if($transaction->type === 'Buying')
                                                {{__('with')}}
                                            @else
                                                {{__('from')}}
                                            @endif
                                            {{$transaction->fromAccountName}}
                                        </p>
                                    @endif
                                    @if(
                                        !isset($query['account_id']) ||
                                        $transaction->to_account_id != $query['account_id']
                                        )
                                        <p class="sm:ml-0 ml-4">
                                            @if($transaction->type === 'Selling')
                                                {{__('into')}}
                                            @else
                                                {{__('to')}}
                                            @endif
                                            {{$transaction->toAccountName}}
                                        </p>
                                    @endif
                                    <div class="sm:pl-2 sm:grow sm:flex sm:justify-end">
                                        <a class="sm:ml-0 ml-4 text-blue-800 dark:text-blue-400 underline"
                                           href="{{route('transaction.show', $transaction->id)}}">Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-6 flex flex-col gap-3 items-start sm:flex-row sm:justify-center">
                            <x-secondary-button
                                class="h-10"
                                :disabled="$transactions->onFirstPage()"
                                @click="page--;
                                    url.searchParams.set('page', page);
                                    window.location.href = url.toString();">
                                {{__('Previous')}}
                            </x-secondary-button>

                            <form method="get"
                                  @submit.prevent
                                  @submit="url.searchParams.set('page', document.getElementById('page-input').value);
                                       window.location.href = url.toString();">
                                <x-text-input id="page-input" name="page" type="text"
                                              class="{{$transactions->lastPage() < 10 ? 'w-28' : 'w-32' }} h-10 block text-center"
                                              placeholder="Page {{$transactions->currentPage()}} of {{$transactions->lastPage()}}">
                                </x-text-input>
                            </form>

                            <x-secondary-button
                                class="h-10"
                                :disabled="$transactions->onLastPage()"
                                @click="page++;
                                    url.searchParams.set('page', page);
                                    window.location.href = url.toString();">
                                {{__('Next')}}
                            </x-secondary-button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
