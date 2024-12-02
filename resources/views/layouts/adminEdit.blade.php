<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <a href="/admin"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-3 inline-block">Kembali</a>

                <form action="/admin/editsave/{{$user->id}}" method="post" class="" id="formEdit">
                    @csrf
                    @method('put')
                    <p class="text-red-500 text-xs italic">Kosongkan jika tidak ingin mengedit.</p>
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-bold mb-2">Nama:</label>
                        <input type="text" id="nama" name="nama"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{$user->name}}" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" name="email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{$user->email}}" required>
                    </div>
                    @if($userauth->id != $user->id)
                        <div class="mb-4">
                            <label for="role_id" class="block text-sm font-bold mb-2">Role:</label>
                            <select id="role_id" name="role_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="0" {{$user->role_id == 0 ? 'selected' : ''}}>Admin</option>
                                <option value="1" {{$user->role_id == 1 ? 'selected' : ''}}>Pegawai</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="role_id" value="{{$user->role_id}}">
                    @endif
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-bold mb-2">Password:</label>
                        <input type="password" id="password" name="password" minlength="8"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-bold mb-2">Konfirmasi
                            Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <button type="button" onclick ="validasiForm()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Simpan
                        Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('modalCustom.themodal', ['message' => 'Yakin Mau Edit Data?', 'form' => 'formEdit'])

