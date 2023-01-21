<x-app-layout :title="'Buy ' . $cryptocurrencyName">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Cryptocurrency')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">

                        @if($cryptocurrency === null)
                            <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                                {{__('No cryptocurrency found!')}}
                            </p>
                        @else
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{__('Buy ')}}
                                {!!$cryptocurrency->getName()!!}
                                @if($cryptocurrency->getName() !== $cryptocurrency->getSymbol())
                                    ({!!$cryptocurrency->getSymbol()!!})
                                @endif
                            </h2>

                            <p>
                                {{__('Price: ')}}
                                {!!$cryptocurrency->getPriceFormatted($convertTo)!!}
                            </p>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{__("Enter the account using which to buy cryptocurrency, the account in which to store the cryptocurrency and the amount to buy.")}}
                            </p>

                            <form method="post" action="{{route('cryptocurrency.buy', $cryptocurrency->getSymbol())}}"
                                  class="mt-6 space-y-6">
                                @csrf

                                <div class="mb-4">
                                    <x-input-label for="from_account_id" :value="__('Account')"/>
                                    <x-select name="from_account_id" id="from_account_id">
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}"
                                                    @if(old('from_account_id') == $account->id) selected @endif>
                                                {{$account->label ?? $account->number}} {{$account->balanceFormatted}}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error :messages="$errors->buyCryptocurrency->get('from_account_id')"
                                                   class="mt-2"/>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="to_account_id" :value="__('Account')"/>
                                    <x-select name="to_account_id" id="to_account_id">
                                        @foreach($cryptoAccounts as $cryptoAccount)
                                            <option value="{{$cryptoAccount->id}}"
                                                    @if(old('to_account_id') == $cryptoAccount->id) selected @endif>
                                                {{$cryptoAccount->label ?? $cryptoAccount->number}} {{$cryptoAccount->balanceFormatted}}
                                            </option>
                                        @endforeach
                                        <option value="new" @if(old('to_account_id') == 'new') selected @endif>
                                            Create a new account
                                        </option>
                                    </x-select>
                                    <x-input-error :messages="$errors->buyCryptocurrency->get('to_account_id')"
                                                   class="mt-2"/>
                                </div>

                                <div>
                                    <x-input-label for="amount" :value="__('Amount')"/>
                                    <x-text-input id="amount" name="amount" type="text"
                                                  :value="old('amount')" class="mt-1 block w-full"/>
                                    <x-input-error :messages="$errors->buyCryptocurrency->get('amount')" class="mt-2"/>
                                </div>

                                <div>
                                    <x-input-label for="security_code"
                                                   :value="__('Security Code № ') . $securityCodeNumber"/>
                                    <x-text-input id="security_code" name="security_code" type="text"
                                                  class="mt-1 block w-full"/>
                                    <x-input-error :messages="$errors->buyCryptocurrency->get('security_code')"
                                                   class="mt-2"/>
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{__('Buy')}}</x-primary-button>

                                    @if (session('status') === 'cryptocurrency-bought')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                           x-init="setTimeout(() => show = false, 5000)"
                                           class="text-sm text-green-600 dark:text-green-400">
                                            {{__('Transaction successful.')}}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
