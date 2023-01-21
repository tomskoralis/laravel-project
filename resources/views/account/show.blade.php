<x-app-layout :title="'Account'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('View Account')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        @if($account->label)
                            {!! $account->label !!} ({{$account->number}})
                        @else
                            {{$account->number}}
                        @endif
                    </h2>

                    <p class="dark:text-white">
                        {{__('Currency:')}} {{$account->currency}}
                    </p>

                    <p class="dark:text-white">
                        {{__('Balance:')}}
                        @if($account->balance < 0)
                            <span class="text-red-600">{{$account->balanceFormatted}}</span>
                        @else
                            {{$account->balanceFormatted}}
                        @endif
                    </p>

                    <p class="dark:text-white">
                        {{__('Created at:')}} {{$account->formatTimestamp($account->created_at)}}
                    </p>

                    <p class="dark:text-white">
                        {{__('Updated at:')}} {{$account->formatTimestamp($account->updated_at)}}
                    </p>

                    <div class="mt-4 flex gap-2">
                        <x-primary-button>
                            <a href="{{route('account.edit', $account->id)}}">
                                {{__('Edit Label')}}
                            </a>
                        </x-primary-button>
                        <x-primary-button>
                            <a href="{{route('account.close', $account->id)}}">
                                {{__('Close account')}}
                            </a>
                        </x-primary-button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
