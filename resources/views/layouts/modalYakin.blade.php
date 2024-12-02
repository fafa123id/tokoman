<?php $parrent = $parrent ?? ''; 
?>
@if($message)
    <x-custom-modal name="{{$parrent}}" :show="true" focusable :parrent="$parrent">
        <div class="space-y-6 p-6">
            <h2 class="text-lg font-medium text-red-500 ">
                {{ __('Tunggu Dulu!') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __($message) }}
            </p>
            <div class="flex justify-center gap-4">
                <div class="mt-6 flex justify-center">
                    <x-primary-button onclick="document.getElementById('{{$form}}').submit();">
                        {{ __('Iya') }}
                    </x-primary-button>
                </div>

                <div class="mt-6 flex justify-center">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Tidak') }}
                    </x-secondary-button>
                </div>
            </div>

        </div>
    </x-custom-modal>
@endif
