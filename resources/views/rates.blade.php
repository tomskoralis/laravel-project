<x-app-layout :title="'Exchange Rates'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Exchange Rates')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if($rates->isEmpty())
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No exchange rates found!')}}
                    </p>
                @else
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{__('Exchange rates used in the currency conversion')}}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{__("Exchange rates were gathered from bank.lv at ")}}
                        {{$timeUpdatedAt}}
                    </p>

                    <div class="hidden sm:flex gap-8">
                        <div
                            class="max-w-sm text-right dark:text-white grid grid-cols-[minmax(2rem,_auto)_minmax(4.5rem,_auto)_minmax(5rem,_auto)]">
                            <div class="pr-3 font-bold text-left flex items-end">
                                &#8470;
                            </div>
                            <div class="pr-3 font-bold flex items-end justify-end">
                                {{__('Currency (Symbol)')}}
                            </div>
                            <div class="font-bold flex items-end justify-end">
                                {{__('Rate')}}
                            </div>
                            @for($key = 0; $key < $rates->count()/2; $key++)
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                    {{$key + 1}}
                                </div>
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                    {{$rates[$key]->currencyFormatted()}}
                                </div>
                                <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                    {{$rates[$key]->rateFormatted()}}
                                </div>
                            @endfor
                        </div>

                        <div
                            class="max-w-sm text-right dark:text-white grid grid-cols-[minmax(2rem,_auto)_minmax(4.5rem,_auto)_minmax(5rem,_auto)]">
                            <div class="pr-3 font-bold text-left flex items-end">
                                &#8470;
                            </div>
                            <div class="pr-3 font-bold flex items-end justify-end">
                                {{__('Currency (Symbol)')}}
                            </div>
                            <div class="font-bold flex items-end justify-end">
                                {{__('Rate')}}
                            </div>
                            @for($key; $key < $rates->count(); $key++)
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                    {{$key + 1}}
                                </div>
                                <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                    {{$rates[$key]->currencyFormatted()}}
                                </div>
                                <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                    {{$rates[$key]->rateFormatted()}}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div
                        class="sm:hidden max-w-xs text-right dark:text-white grid grid-cols-[minmax(2rem,_auto)_minmax(4.5rem,_auto)_minmax(5rem,_auto)]">
                        <div class="pr-3 font-bold text-left flex items-end">
                            &#8470;
                        </div>
                        <div class="pr-3 font-bold flex items-end justify-end">
                            {{__('Currency (Symbol)')}}
                        </div>
                        <div class="font-bold flex items-end justify-end">
                            {{__('Rate')}}
                        </div>
                        @foreach($rates as $key => $rate)
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                {{$key + 1}}
                            </div>
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                {{$rate->currencyFormatted()}}
                            </div>
                            <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                {{$rate->rateFormatted()}}
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
        </div>
    </div>
</x-app-layout>
