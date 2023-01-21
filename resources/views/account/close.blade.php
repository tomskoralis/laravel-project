<x-app-layout :title="'Close Account'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Close account')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{__('Close account')}}
                        @if($account->label)
                            {!! $account->label !!} ({{$account->number}})
                        @else
                            {{$account->number}}
                        @endif
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{__("Enter your security code to confirm that you want to close the account. The account balance must be 0.")}}
                    </p>

                    <form method="post" action="{{route('account.destroy', $account->id)}}" class="mt-6 space-y-6">
                        @csrf
                        @method('delete')

                        <div>
                            <x-input-label for="security_code" :value="__('Security Code № ') . $securityCodeNumber"/>
                            <x-text-input id="security_code" name="security_code" type="text" autofocus
                                          class="mt-1 block w-full"/>
                            <x-input-error :messages="$errors->accountDeletion->get('security_code')" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-danger-button>{{__('Close account')}}</x-danger-button>

                            @if (session('status') === 'account-not-empty')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                   x-init="setTimeout(() => show = false, 5000)"
                                   class="text-sm text-red-600 dark:text-red-400">
                                    {{__('Account Not Empty!')}}
                                </p>
                            @endif
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
