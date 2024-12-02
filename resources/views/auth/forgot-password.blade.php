<?php 
$email="";
if(isset($_GET['email'])){
    $email = $_GET['email'];
}
?>
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan email berisi tautan pengaturan ulang kata sandi yang memungkinkan Anda memilih yang baru.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="g-recaptcha mt-4" data-sitekey="{{env('RECAPTCHA_SITE_KEY')}}"></div>
        <x-input-error :messages="$errors->first('g-recaptcha-response', 'Isi captcha dulu.')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Tautan Reset Kata Sandi Email') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
