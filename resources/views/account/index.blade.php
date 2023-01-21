<x-app-layout :title="'Accounts'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Accounts')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if($accounts->isEmpty())
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No accounts found!')}}
                    </p>
                @else
                    <div class="max-w-xl">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{__('All Accounts')}}
                        </h2>

                        <div
                            @if ($accountsHaveLabel)
                                class="text-right dark:text-white grid grid-cols-[minmax(8rem,_auto)_minmax(6rem,_auto)_minmax(8rem,_auto)_minmax(5rem,_auto)]"
                            @else
                                class="text-right dark:text-white grid grid-cols-[minmax(8rem,_auto)_minmax(8rem,_auto)_minmax(5rem,_auto)]"
                            @endif
                        >
                            <div class="pr-3 font-bold text-left flex items-end">
                                {{__('Number')}}
                            </div>
                            @if ($accountsHaveLabel)
                                <div class="pr-3 font-bold flex items-end justify-end">
                                    {{__('Label')}}
                                </div>
                            @endif
                            <div class="pr-3 font-bold flex items-end justify-end">
                                {{__('Balance')}}
                            </div>
                            <div class="font-bold flex items-end justify-end">
                                {{__('Currency')}}
                            </div>
                            @foreach($accounts as $account)
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                    <a class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                                       href="{{route('account.show', $account->id)}}">
                                        {{$account->number}}
                                    </a>
                                </div>
                                @if ($accountsHaveLabel)
                                    <div
                                        class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-ellipsis whitespace-nowrap overflow-hidden">
                                        {!!$account->label!!}
                                    </div>
                                @endif
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                    @if($account->balance < 0)
                                        <span class="text-red-600">{{$account->balanceFormatted}}</span>
                                    @else
                                        {{$account->balanceFormatted}}
                                    @endif
                                </div>
                                <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                    {{$account->currency}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('status') === 'account-closed')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                       class="text-sm text-green-600 dark:text-green-400">
                        {{__('Account Closed.')}}
                    </p>
                @endif

                <div class="mt-4 flex gap-2">
                    @if(!$accounts->isEmpty())
                        <x-primary-button>
                            <a href="{{route('transaction.create')}}">
                                {{__('New Transaction')}}
                            </a>
                        </x-primary-button>
                    @endif
                    <x-primary-button>
                        <a href="{{route('account.create')}}">
                            {{__('Create Account')}}
                        </a>
                    </x-primary-button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
