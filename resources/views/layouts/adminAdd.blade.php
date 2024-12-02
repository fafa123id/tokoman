<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <a href="/admin"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-3 inline-block">Kembali</a>

                <form action="{{url('/admin/addsave')}}" method="post" class="needs-validation" id="addForm">
                    @csrf
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-bold mb-2">Nama:</label>
                        <input type="text" id="nama" name="nama" value="{{old('nama')}}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic">Nama wajib diisi.</p>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" name="email" value="{{old('email')}}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="block text-sm font-bold mb-2">Role:</label>
                        <select id="role_id" name="role_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="0">Admin</option>
                            <option value="1" selected>Pegawai</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-bold mb-2" >Password:</label>
                        <input type="password" id="password" name="password" required minlength="8"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-bold mb-2">Konfirmasi
                            Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <button type="button" onclick="validasiForm()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Simpan
                        Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('modalCustom.themodal', ['message' => 'Yakin Mau Tambahkan Data?', 'form' => 'addForm'])


