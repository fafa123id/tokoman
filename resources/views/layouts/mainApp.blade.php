<x-app-layout>
    
        @if(Auth::user()->role_id == 2)
            <div id="parrentMu" class="hidden">
                @include('layouts.formModal')
            </div>
        @endif
        <div class="flex flex-col min-h-screen h-full md:flex-row dark:bg-gray-800">
            <div class=" w-full md:w-64 dark:bg-gray-800 bg-slate-300">
                @include('layouts.navbarLeft') 
        </div>
            <aside class="overflow-auto w-full max-sm:min-h-screen px-4 py-2 dark:bg-gray-800 bg-slate-300">
                {{ $slot }}
            </aside>
        </div>
</x-app-layout>
<script src="https://tokoman.s3.ap-southeast-2.amazonaws.com/js/pusherTokomans.js"></script>