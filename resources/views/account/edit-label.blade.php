<x-app-layout :title="'Edit Account'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__('Edit Account Label')}}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{__('Edit the label of account ')}} {{$account->number}}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{__("Enter the new label for the account. The label must be unique between your accounts.")}}
                    </p>

                    <form method="post" action="{{route('account.update', $account->id)}}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label for="label" :value="__('Label')"/>
                            <x-text-input id="label" name="label" type="text" class="mt-1 block w-full"
                                          :value="old('label', $account->label)" autocomplete="label" autofocus/>
                            <x-input-error :messages="$errors->labelUpdating->get('label')" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{__('Save')}}</x-primary-button>

                            @if (session('status') === 'label-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                   x-init="setTimeout(() => show = false, 5000)"
                                   class="text-sm text-green-600 dark:text-green-400">
                                    {{__('Saved.')}}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
