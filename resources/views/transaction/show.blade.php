<x-app-layout :title="'Transaction'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Transaction')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="max-w-lg h-fit relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-500">
                    <table class="w-full text-sm sm:text-base text-left">
                        <caption
                            class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
                            {{__('Transaction details')}}
                        </caption>
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('Id')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->id}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('Type')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->type}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('Amount')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->amountFormatted}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                @switch($transaction->type)
                                    @case('Transferring')
                                    @case('Incoming')
                                        {{__('Converted from')}}
                                        @break
                                    @case('Outgoing')
                                    @case('Selling')
                                        {{__('Converted to')}}
                                        @break
                                    @case('Buying')
                                        {{__('Bought with')}}
                                        @break
                                    @default
                                        {{__('Unknown')}}
                                @endswitch:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->amountConvertedFormatted}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('From')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->fromUserName}} ({{$transaction->fromAccountName}})
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('To')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->toUserName}} ({{$transaction->toAccountName}})
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                {{__('Time')}}:
                            </th>
                            <td class="px-2 sm:px-6 py-2 text-right">
                                {{$transaction->timeFormatted}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
