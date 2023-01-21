<x-app-layout :title="$cryptocurrency !== null ? $cryptocurrency->getSymbol() : 'Unknown'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Cryptocurrency')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if($cryptocurrency === null)
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No cryptocurrency found!')}}
                    </p>
                @else
                    <div class="max-w-xl dark:text-gray-200">

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {!!$cryptocurrency->getName()!!}
                            @if($cryptocurrency->getName() !== $cryptocurrency->getSymbol())
                                ({!!$cryptocurrency->getSymbol()!!})
                            @endif
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{__("Cryptocurrency data was gathered from coinmarketcap.com.")}}
                        </p>

                        <p>
                            {{__('Price: ')}}
                            {!!$cryptocurrency->getPriceFormatted($convertTo)!!}
                        </p>
                        <p>
                            {{__('Change 1h: ')}}
                            @if($cryptocurrency->getChange1h() > 0)
                                <span class="text-green-700 dark:text-green-400">
                                    &#9650;{!!round($cryptocurrency->getChange1h(), 2)!!}%
                                </span>
                            @else
                                <span class="text-red-700 dark:text-red-400">
                                    &#9660;{!!abs(round($cryptocurrency->getChange1h(), 2))!!}%
                                </span>
                            @endif
                        </p>
                        <p>
                            {{__('Change 24h: ')}}
                            @if($cryptocurrency->getChange24h() > 0)
                                <span class="text-green-700 dark:text-green-400">
                                    &#9650;{!!round($cryptocurrency->getChange24h(), 2)!!}%
                                </span>
                            @else
                                <span class="text-red-700 dark:text-red-400">
                                    &#9660;{!!abs(round($cryptocurrency->getChange24h(), 2))!!}%
                                </span>
                            @endif
                        </p>
                        <p>
                            {{__('Change 7d: ')}}
                            @if($cryptocurrency->getChange7d() > 0)
                                <span class="text-green-700 dark:text-green-400">
                                    &#9650;{!!round($cryptocurrency->getChange7d(), 2)!!}%
                                </span>
                            @else
                                <span class="text-red-700 dark:text-red-400">
                                    &#9660;{!!abs(round($cryptocurrency->getChange7d(), 2))!!}%
                                </span>
                            @endif
                        </p>

                        <div class="mt-4 flex gap-2">
                            <a href="{{route('cryptocurrency.buyForm', $cryptocurrency->getSymbol())}}">
                                <x-primary-button>
                                    {{__('Buy')}} {{$cryptocurrency->getSymbol()}}
                                </x-primary-button>
                            </a>

                            <a href="{{route('cryptocurrency.sellForm', $cryptocurrency->getSymbol())}}">
                                <x-primary-button>
                                    {{__('Sell')}} {{$cryptocurrency->getSymbol()}}
                                </x-primary-button>
                            </a>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
