<x-app-layout :title="'Security Codes'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Security Codes')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{__('Security Codes')}}
                        </h2>

                        @if (session('status') === 'codes-generated')
                            <p x-data="{ show: true }" x-show="show" x-transition
                               x-init="setTimeout(() => show = false, 5000)"
                               class="text-sm text-green-600 dark:text-green-400">
                                {{__('Generated new security codes!')}}
                            </p>
                        @endif
                    </div>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{__("Security codes are case sensitive. The security codes can be viewed only once each time new codes are generated. The security codes were updated at ")}}
                        {{$timeUpdatedAt}}
                    </p>
                </div>

                @if(!isset($securityCodes) || $securityCodes->isEmpty())
                    <p class="max-w-xl px-4 py-2 mt-2 rounded-lg bg-red-100 border-2 border-red-600 text-sm text-red-600 font-medium">
                        {{__('Cannot view security codes!')}}
                    </p>
                @else

                    <ul class="grid grid-rows-1 grid-flow-row md:grid-rows-4 md:grid-flow-col gap-x-4 text-gray-900 dark:text-gray-100">
                        @foreach($securityCodes as $key => $securityCode)
                            <li class="w-fit">
                                {{$key + 1}}. {{$securityCode}}
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
