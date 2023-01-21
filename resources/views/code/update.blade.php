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

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Generate new security codes') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Security codes can be viewed only once after generating new codes. Please enter your password to confirm you would like to generate new security codes.') }}
                    </p>

                    <form method="post" action="{{ route('codes.generate') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div class="mt-6">
                            <x-input-label for="password" :value="__('Password')"/>
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                          autofocus/>
                            <x-input-error :messages="$errors->codeGeneration->get('password')" class="mt-2"/>
                        </div>

                        <x-primary-button class="flex items-center gap-4">{{__('Generate')}}</x-primary-button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
