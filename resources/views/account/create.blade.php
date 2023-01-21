<x-app-layout :title="'Create Account'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Create account')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{__('Create a new regular account')}}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{__("Enter the currency in which to open the account. Cryptocurrency accounts can only be created while buying a cryptocurrency. Entering a label is not required.")}}
                    </p>

                    <form method="post" action="{{route('account.store')}}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="currency" :value="__('Currency')"/>
                            <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full"
                                          :value="old('currency')" autocomplete="currency" autofocus/>
                            <x-input-error :messages="$errors->accountCreation->get('currency')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="label" :value="__('Label')"/>
                            <x-text-input id="label" name="label" type="text" class="mt-1 block w-full"
                                          :value="old('label')" autocomplete="label"/>
                            <x-input-error :messages="$errors->accountCreation->get('label')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="security_code" :value="__('Security Code № ') . $securityCodeNumber"/>
                            <x-text-input id="security_code" name="security_code" type="text"
                                          class="mt-1 block w-full"/>
                            <x-input-error :messages="$errors->accountCreation->get('security_code')" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{__('Confirm')}}</x-primary-button>

                            @if (session('status') === 'account-created')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                   x-init="setTimeout(() => show = false, 5000)"
                                   class="text-sm text-green-600 dark:text-green-400">
                                    {{__('Account Created.')}}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
