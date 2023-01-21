<x-app-layout :title="'New Transaction'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('New Transaction')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{__('Transfer currency to another account')}}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{__("Enter the accounts from and to which to transfer currency and the amount to transfer.")}}
                        </p>

                        <form method="post" action="{{route('transaction.store')}}" class="mt-6 space-y-6">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="from_account_id" :value="__('Account')"/>
                                <x-select name="from_account_id" id="from_account_id">
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}"
                                                @if(old('from_account_id') == $account->id) selected @endif>
                                            {{$account->number}} {{$account->balanceFormatted}}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error :messages="$errors->storeTransaction->get('from_account_id')"
                                               class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="to_account_number" :value="__('To Account')"/>
                                <x-text-input id="to_account_number" name="to_account_number" type="text"
                                              :value="old('to_account_number')" class="mt-1 block w-full"
                                              autocomplete="to_account_number"/>
                                <x-input-error :messages="$errors->storeTransaction->get('to_account_number')"
                                               class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="amount" :value="__('Amount')"/>
                                <x-text-input id="amount" name="amount" type="text" :value="old('amount')"
                                              class="mt-1 block w-full"/>
                                <x-input-error :messages="$errors->storeTransaction->get('amount')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="security_code"
                                               :value="__('Security Code № ') . $securityCodeNumber"/>
                                <x-text-input id="security_code" name="security_code" type="text"
                                              class="mt-1 block w-full"/>
                                <x-input-error :messages="$errors->storeTransaction->get('security_code')"
                                               class="mt-2"/>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{__('Transfer')}}</x-primary-button>

                                @if (session('status') === 'transaction-successful')
                                    <p x-data="{ show: true }" x-show="show" x-transition
                                       x-init="setTimeout(() => show = false, 5000)"
                                       class="text-sm text-green-600 dark:text-green-400">
                                        {{__('Transaction successful.')}}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
