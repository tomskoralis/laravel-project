<x-app-layout :title="'Cryptocurrencies'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Cryptocurrencies')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if($cryptocurrencies->isEmpty())
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No cryptocurrencies found!')}}
                    </p>
                @else
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{__('Cryptocurrencies')}}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{__("Cryptocurrency data was gathered from coinmarketcap.com.")}}
                    </p>

                    <div class="max-w-xl text-right dark:text-white grid
                         grid-cols-[minmax(4rem,_auto)_minmax(6.5rem,_auto)_minmax(5rem,_auto)_minmax(5rem,_auto)_minmax(4.25rem,_auto)]">

                        <div class="pr-3 font-bold text-left flex items-end">
                            {{__('Name (Symbol)')}}
                        </div>
                        <div class="pr-3 font-bold flex items-end justify-end">
                            {{__('Price')}}
                        </div>
                        <div class="pr-3 font-bold flex items-end justify-end">
                            {{__('Change 1h')}}
                        </div>
                        <div class="pr-3 font-bold flex items-end justify-end">
                            {{__('Change 24h')}}
                        </div>
                        <div class="font-bold flex items-end justify-end">
                            {{__('Change 7d')}}
                        </div>

                        @foreach($cryptocurrencies as $cryptocurrency)
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700 text-left">
                                <a class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                                   href="{{route('cryptocurrency.show',$cryptocurrency->getSymbol())}}">
                                    {!!$cryptocurrency->getName()!!}
                                    @if($cryptocurrency->getName() !== $cryptocurrency->getSymbol())
                                        ({!!$cryptocurrency->getSymbol()!!})
                                    @endif
                                </a>
                            </div>
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                {!!$cryptocurrency->getPriceFormatted($convertTo)!!}
                            </div>
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                @if($cryptocurrency->getChange1h() > 0)
                                    <span class="text-green-700 dark:text-green-400">
                                        &#9650;{!!round($cryptocurrency->getChange1h(), 2)!!}%
                                    </span>
                                @else
                                    <span class="text-red-700 dark:text-red-400">
                                        &#9660;{!!abs(round($cryptocurrency->getChange1h(), 2))!!}%
                                    </span>
                                @endif
                            </div>
                            <div class="pr-3 border-t-2 border-gray-300 dark:border-gray-700">
                                @if($cryptocurrency->getChange24h() > 0)
                                    <span class="text-green-700 dark:text-green-400">
                                        &#9650;{!!round($cryptocurrency->getChange24h(), 2)!!}%
                                    </span>
                                @else
                                    <span class="text-red-700 dark:text-red-400">
                                        &#9660;{!!abs(round($cryptocurrency->getChange24h(), 2))!!}%
                                    </span>
                                @endif
                            </div>
                            <div class="border-t-2 border-gray-300 dark:border-gray-700">
                                @if($cryptocurrency->getChange7d() > 0)
                                    <span class="text-green-700 dark:text-green-400">
                                        &#9650;{!!round($cryptocurrency->getChange7d(), 2)!!}%
                                    </span>
                                @else
                                    <span class="text-red-700 dark:text-red-400">
                                        &#9660;{!!abs(round($cryptocurrency->getChange7d(), 2))!!}%
                                    </span>
                                @endif
                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
