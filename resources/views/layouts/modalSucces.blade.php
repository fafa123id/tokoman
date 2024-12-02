<x-modal name="confirm-user-deletion" :show="true" focusable>
    <div class="space-y-6 p-6">
        <h2 class="text-lg font-medium text-green-500 ">
            {{ __('Success!') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __($message) }}
        </p>

        <div class="mt-6 flex justify-center">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Ok') }}
            </x-secondary-button>
        </div>
    </div>

</x-modal>