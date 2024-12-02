<!-- Hamburger Button -->
<div class="flex items-center justify-center space-x-4">
    <button id="menuButton"
        class="md:hidden p-4 focus:outline-none flex justify-center bg-transparent text-black dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 rounded-md"
        onclick="toggleNavbar()">
        <!-- Hamburger Icon -->
        <svg id="hamburgerIcon" class="w-10 h-6" fill="none" stroke="currentColor" viewBox="0 0 40 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 6h36M2 12h36M2 18h36"></path>
        </svg>
        <!-- Cross Icon -->
        <svg id="crossIcon" class="w-10 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

</div>

<div
    class=" dark:text-white text-black h-full border-r border-t dark:border-gray-700 transform -translate-x-full sm:translate-x-0 max-md:hidden transition-transform duration-300 this">
    @if (Auth::user()->email_verified_at != null)
        <div class="p-4 text-xl font-semibold border-b dark:border-gray-700">Manajemen</div>
        <ul class="space-y-2 p-5">
            <li>
                <a href="{{ route('stok.index') }}"
                    class="{{ request()->routeIs('stok.*') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>📦</span>
                    <span>Manajemen Stok</span>
                </a>
            </li>
            @if(in_array(Auth::user()->role_id, [0, 2]))
                <li>
                    <a href="{{ route('admin.index') }}"
                        class="{{ request()->routeIs('admin.*') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                        <span>🧑‍💼</span>
                        <span>Manajemen Pegawai</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->role_id == 2)
                <li>
                    <button onclick="document.getElementById('parrentMu').classList.remove('hidden')"
                        class="flex items-center text-left space-x-2 py-1 w-full hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                        <span>🥮</span>
                        <span>Ubah Token Register</span>
                    </button>
                </li>
            @endif
        </ul>
        <div class="p-4 text-xl font-semibold border-y dark:border-gray-700">Analisis</div>
        <ul class="space-y-2 p-5">
            <li>
                <a href="{{ route('profit.index') }}"
                    class="{{ request()->routeIs('profit.index') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>📊</span>
                    <span>Profit</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan') }}"
                    class="{{ request()->routeIs('laporan') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>📝</span>
                    <span>Penjualan</span>
                </a>
            </li>
            <li>
                <a href="/riwayat"
                    class="{{ request()->routeIs('riwayat', 'riwayatFilter') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>⏰</span>
                    <span>Riwayat</span>
                </a>
            </li>
        </ul>
        <div class="p-4 text-xl font-semibold border-y dark:border-gray-700">Kerja Sama</div>
        <ul class="space-y-2 p-5">
            <li>
                <a href="/agents"
                    class="{{ request()->routeIs('agents.*') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>👤</span>
                    <span>Agent</span>
                </a>
            </li>
            <li>
                <a href="/mitra"
                    class="{{ request()->routeIs('mitra.*') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                    <span>👥</span>
                    <span>Mitra</span>
                </a>
            </li>
        </ul>
    @endif
    <div class="p-4 text-xl font-semibold border-y dark:border-gray-700">Pengaturan</div>
    <ul class="space-y-2 p-5">
        <li>
            <a href="{{ route('profile.edit') }}"
                class="{{ request()->routeIs('profile.edit') ? 'bg-blue-500 text-white px-3 py-1 rounded-md shadow' : '' }} flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow">
                <span>👤</span>
                <span>Profil</span>
            </a>
        </li>
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                @method('post')
                <button type="submit"
                    class="flex items-center space-x-2 py-1 hover:bg-gray-700 hover:text-white hover:rounded-md hover:shadow w-full">
                    <span>🚪</span>
                    <span>Keluar</span>
                </button>
            </form>
            </a>
        </li>
    </ul>
</div>
<script>
    function toggleNavbar() {
        const navbar = document.querySelector('.this');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const crossIcon = document.getElementById('crossIcon');
        const menuButton = document.getElementById('menuButton');
        hamburgerIcon.classList.toggle('hidden');
        crossIcon.classList.toggle('hidden');
        if (navbar.classList.contains('max-md:hidden')) {
            navbar.classList.remove('max-md:hidden');
        } else {
            navbar.classList.add('max-md:hidden');
        }
        navbar.classList.toggle('-translate-x-full');
    }
</script>