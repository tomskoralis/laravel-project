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
                    <div class="text-gray-900 dark:text-gray-100">
                        <div class="h-fit relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-500">
                            <table class="w-full text-sm md:text-base text-left">
                                <caption
                                    class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
                                    {{__('All Accounts')}}
                                    <p class="mt-1 text-sm md:text-base font-normal text-gray-500 dark:text-gray-400">
                                        {{__('Information about all your accounts.')}}
                                    </p>
                                </caption>
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-2 md:px-6 py-2">
                                        {{__('Number')}}
                                    </th>
                                    @if ($accountsHaveLabel)
                                        <th scope="col" class="px-2 md:px-6 py-2 text-center">
                                            {{__('Label')}}
                                        </th>
                                    @endif
                                    <th scope="col" class="px-2 md:px-6 py-2 text-center">
                                        {{__('Balance')}}
                                    </th>
                                    <th scope="col" class="px-2 md:px-6 py-2 text-center">
                                        {{__('Currency')}}
                                    </th>
                                    <th scope="col" class="px-2 md:px-6 py-2">
                                        <span class="sr-only">{{__('Details')}}</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($accounts as $account)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <th scope="row"
                                            class="px-2 md:px-6 py-2 font-medium whitespace-nowrap">
                                            {{$account->number}}
                                        </th>
                                        @if ($accountsHaveLabel)
                                            <td class="px-2 md:px-6 py-2 text-center">
                                                {!!$account->label!!}
                                            </td>
                                        @endif
                                        <td class="px-2 md:px-6 py-2 text-center">
                                            @if($account->balance < 0)
                                                <span class="text-red-600">{{$account->balanceFormatted}}</span>
                                            @else
                                                {{$account->balanceFormatted}}
                                            @endif
                                        </td>
                                        <td class="px-2 md:px-6 py-2 text-center">
                                            {{$account->currency}}
                                        </td>
                                        <td class="px-2 md:px-6 py-2 text-right">
                                            <a class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                                               href="{{route('account.show', $account->id)}}">
                                                {{__('Details')}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


                <div class="mt-4 flex gap-2 justify-between">
                    @if(!$accounts->isEmpty())
                        <a href="{{route('transaction.create')}}">
                            <x-primary-button>
                                {{__('New Transaction')}}
                            </x-primary-button>
                        </a>
                    @endif

                    @if (session('status') === 'account-closed')
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="mt-4 text-sm text-green-600 dark:text-green-400">
                            {{__('Account Closed.')}}
                        </p>
                    @endif

                    <a href="{{route('account.create')}}">
                        <x-primary-button>
                            {{__('Create Account')}}
                        </x-primary-button>
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
