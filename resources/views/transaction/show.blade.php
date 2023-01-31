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

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{__('Transaction details')}}
                </h3>

                <ul class="mt-4 flex flex-col gap-2">
                    <li>{{__('Id')}}: {{$transaction->id}}</li>

                    <li>{{__('Type')}}: {{$transaction->type}}</li>
                    <li>{{__('Amount')}}: {{$transaction->amountFormatted}}</li>

                    @if($transaction->fromCurrency !== $transaction->toCurrency)
                        <p> @switch($transaction->type)
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
                            @endswitch: {{$transaction->amountConvertedFormatted}}</p>
                    @endif

                    <li>{{__('From')}}: {{$transaction->fromUserName}} ({{$transaction->fromAccountName}})</li>
                    <li>{{__('To')}}: {{$transaction->toUserName}} ({{$transaction->toAccountName}})</li>
                    <li>{{__('Time')}}: {{$transaction->timeFormatted}}</li>
                </ul>

            </div>
        </div>
    </div>
</x-app-layout>
