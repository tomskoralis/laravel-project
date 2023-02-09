<x-app-layout :title="$cryptocurrency !== null ? $cryptocurrency->getSymbol() : 'Unknown'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Cryptocurrency')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg dark:text-gray-200">

                @if(!isset($cryptocurrency))
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('No cryptocurrency found!')}}
                    </p>
                @else
                    <div class="w-full flex flex-wrap justify-between gap-y-2">
                        <div class="max-w-xl h-fit">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{$cryptocurrency->getName()}}
                                @if($cryptocurrency->getName() !== $cryptocurrency->getSymbol())
                                    ({{$cryptocurrency->getSymbol()}})
                                @endif
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{__("Cryptocurrency data was gathered from coinmarketcap.com.")}}
                            </p>

                            <div
                                class="max-w-xl h-fit relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-500">
                                <table class="w-full text-sm sm:text-base text-left">
                                    <caption
                                        class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
                                        {{__('Info')}}
                                        <p class="mt-1 text-sm sm:text-base font-normal text-gray-500 dark:text-gray-400">
                                            {{__('General information about the cryptocurrency.')}}
                                        </p>
                                    </caption>
                                    <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Fiat')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{$cryptocurrency->isFiat() ? 'Yes' : 'No'}}
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Active')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{$cryptocurrency->isActive() ? 'Yes' : 'No'}}
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Circulating supply')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{rtrim(rtrim(number_format($cryptocurrency->getCirculatingSupply(), 8), '0'), '.')}}
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Total supply')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{rtrim(rtrim(number_format($cryptocurrency->getTotalSupply(), 8), '0'), '.')}}
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Added at')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{$cryptocurrency->getAddedAtFormatted()}}
                                        </td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-800">
                                        <th scope="row"
                                            class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                            {{__('Updated at')}}
                                        </th>
                                        <td class="px-2 sm:px-6 py-2 text-right">
                                            {{$cryptocurrency->getUpdatedAtFormatted()}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div
                            class="max-w-lg h-fit relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-500">
                            <table class="w-full text-sm sm:text-base text-left">
                                <caption
                                    class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
                                    {{__('Quote')}}
                                    <p class="mt-1 text-sm sm:text-base font-normal text-gray-500 dark:text-gray-400">
                                        {{__('Information about the cryptocurrency quote.')}}
                                    </p>
                                </caption>
                                <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Price')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        {{$cryptocurrency->getPriceFormatted($convertTo)}}
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Change 1h')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        @if($cryptocurrency->getChange1h() > 0)
                                            <span class="text-green-700 dark:text-green-400">
                                    &#9650;{{round($cryptocurrency->getChange1h(), 2)}}%
                                </span>
                                        @else
                                            <span class="text-red-700 dark:text-red-400">
                                    &#9660;{{abs(round($cryptocurrency->getChange1h(), 2))}}%
                                </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Change 24h')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        @if($cryptocurrency->getChange24h() > 0)
                                            <span class="text-green-700 dark:text-green-400">
                                    &#9650;{{round($cryptocurrency->getChange24h(), 2)}}%
                                </span>
                                        @else
                                            <span class="text-red-700 dark:text-red-400">
                                    &#9660;{{abs(round($cryptocurrency->getChange24h(), 2))}}%
                                </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Change 7d')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        @if($cryptocurrency->getChange7d() > 0)
                                            <span class="text-green-700 dark:text-green-400">
                                    &#9650;{{round($cryptocurrency->getChange7d(), 2)}}%
                                </span>
                                        @else
                                            <span class="text-red-700 dark:text-red-400">
                                    &#9660;{{abs(round($cryptocurrency->getChange7d(), 2))}}%
                                </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Market cap')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        {{$cryptocurrency->getMarketCapFormatted($convertTo)}}
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Fully diluted market cap')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        {{$cryptocurrency->getFullyDilutedMarketCapFormatted($convertTo)}}
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Market cap dominance')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        {{rtrim(rtrim(number_format($cryptocurrency->getMarketCapDominance(), 8), '0'), '.')}}
                                        %
                                    </td>
                                </tr>
                                <tr class="bg-white dark:bg-gray-800">
                                    <th scope="row" class="px-2 sm:px-6 py-2 font-medium whitespace-nowrap">
                                        {{__('Quote updated at')}}
                                    </th>
                                    <td class="px-2 sm:px-6 py-2 text-right">
                                        {{$cryptocurrency->getQuoteUpdatedAtFormatted()}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(!empty($cryptocurrency->getTags()))
                        <h3 class="mt-4">
                            {{__('Tags: ')}}
                        </h3>
                        <div class="flex flex-wrap">
                            @foreach($cryptocurrency->getTags() as $tag)
                                <span
                                    class="m-1 rounded px-1 sm:px-2 py-0.5 sm:py-1 items-center text-xs sm:text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 leading-loose dark:text-gray-300">
                                        {{ $tag }}
                                    </span>
                            @endforeach
                        </div>
                    @endif

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

                @endif
            </div>
        </div>
    </div>
</x-app-layout>
