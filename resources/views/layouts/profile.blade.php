<div class="py-12 flex">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(Auth::user()->adminVerified == null)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Akun Anda belum diterima oleh admin, tunggu hingga admin memverifikasi Anda') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Berikut detail akun Anda:') }}
                    </p>
                    
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Nama: ').Auth::user()->name }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Email: ').Auth::user()->email }}
                    </p>
                </div>
            </div>
        @else
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        @endif
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>