<x-app-layout :title="'Account'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('View Account')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-fit dark:text-gray-100">
                    <div class="max-w-lg h-fit relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-500">
                        <table class="w-full text-sm sm:text-base text-left">
                            <caption
                                class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
                                @if($account->label)
                                    {!! $account->label !!}
                                @else
                                    {{$account->number}}
                                @endif
                                <p class="mt-1 text-sm sm:text-base font-normal text-gray-500 dark:text-gray-400">
                                    {{__('Information about the account.')}}
                                </p>
                            </caption>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                    @if($account->label)
                                        {{__('Number')}}
                                    @else
                                        {{__('Label')}}
                                    @endif
                                </th>
                                <td class="px-2 sm:px-6 py-2 text-right">
                                    @if($account->label)
                                        {{$account->number}}
                                    @else
                                        {!! $account->label !!}
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                    {{__('Currency:')}}
                                </th>
                                <td class="px-2 sm:px-6 py-2 text-right">
                                    {{$account->currency}}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                    {{__('Balance:')}}
                                </th>
                                <td class="px-2 sm:px-6 py-2 text-right">
                                    @if($account->balance < 0)
                                        <span class="text-red-600">{{$account->balanceFormatted}}</span>
                                    @else
                                        {{$account->balanceFormatted}}
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                    {{__('Created at:')}}
                                </th>
                                <td class="px-2 sm:px-6 py-2 text-right">
                                    {{$account->formatTimestamp($account->created_at)}}
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                    {{__('Updated at')}}
                                </th>
                                <td class="px-2 sm:px-6 py-2 text-right">
                                    {{$account->formatTimestamp($account->updated_at)}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="w-full mt-4 flex justify-between gap-2">
                        <a href="{{route('account.edit', $account->id)}}">
                            <x-primary-button>
                                {{__('Edit Label')}}
                            </x-primary-button>
                        </a>

                        <a href="{{route('account.close', $account->id)}}">
                            <x-primary-button>
                                {{__('Close account')}}
                            </x-primary-button>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
