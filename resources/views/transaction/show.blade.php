<x-app-layout :title="'Transaction'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Transaction')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="max-w-xl">
                        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            @if($account->label)
                                {!! $account->label !!} ({{$account->number}})
                            @else
                                {{$account->number}}
                            @endif
                        </h2>

                        @if($transactions->isEmpty())
                            <p class="w-fit px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                                {{__('No incoming transactions found!')}}
                            </p>
                        @else
                            <div class="max-w-4xl text-right dark:text-white grid
                         grid-cols-[minmax(2rem,_auto)_minmax(7rem,_auto)_minmax(7rem,_auto)_minmax(8rem,_auto)_minmax(8rem,_auto)_minmax(6rem,_auto)]">
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
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->amountFormatted}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->type}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->fromAccountName}}
                                    </div>
                                    <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->toAccountName}}
                                    </div>
                                    <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                        {{$transaction->timestampFormatted}}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
