<x-custom-modal name="parrentMu" :show="true" focusable :parrent="'parrentMu'">
    <div class="space-y-6 p-6 shadow-md rounded-lg">
        <h2 class="text-lg font-semibold text-blue-600">
            {{ __('Ubah Token Register!') }}
        </h2>

        <form id="newToken" action="{{route('newToken')}}" method="post" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="token" class="block text-sm font-medium dark:text-white text-gray-700">Pilih token yang diubah</label>
                <select required name="token" id="1" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="0">Untuk Admin</option>
                    <option value="1">Untuk Pegawai</option>
                </select>
            </div>
            <div>
                <label for="old" class="block text-sm font-medium dark:text-white text-gray-700">Token lama</label>
                <input required type="password" name="old" placeholder="Masukkan token di sini" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="new" class="block text-sm font-medium dark:text-white text-gray-700">Token Baru</label>
                <input required type="password" name="new" placeholder="Masukkan token baru di sini" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="new2" class="block text-sm font-medium dark:text-white text-gray-700">Konfirmasi Token Baru</label>
                <input required type="password" name="new2" placeholder="Konfirmasi token baru" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </form>
    </div>
</x-custom-modal>