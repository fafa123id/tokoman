<div class="p-6 text-gray-900 dark:text-gray-100 text-center mx-auto">
    <a href="/stok/add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 mb-2 px-4 rounded inline-block">
        + Tambah data barang</a>
    <form action="/stok/deleteAll" method="POST">
        @csrf
        @method('delete')
        <button type="submit"
            class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">Hapus semua data
            barang</button>
    </form>
</div>
@if($barang->isEmpty())
    <div class="text-center text-white text-info">
        <p>Belum ada data</p>
    </div>
@else
    <div class="overflow-auto">
        <table class="table-auto border-collapse border mx-auto w-fit">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">ID Barang</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Isi/bal</th>
                    <th class="px-4 py-2">Ukuran</th>
                    <th class="px-4 py-2">Jenis Tutup</th>
                    <th class="px-4 py-2">Harga Beli</th>
                    <th class="px-4 py-2">Harga Jual</th>
                    <th class="px-4 py-2">Tambahkan Stok</th>
                    <th class="px-4 py-2">Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php    $a = 0;?>
                @foreach($barang as $usr)
                    <tr class="bg-gray-100">
                        <td class="border px-4 py-2"><?php        echo $a += 1?></td>
                        <td class="border px-4 py-2">{{ $usr->id_barang }}</td>
                        <td class="border px-4 py-2">{{ $usr->nama_barang }}</td>
                        <td class="border px-4 py-2">{{ $usr->stok }}</td>
                        <td class="border px-4 py-2">{{ $usr->bal }}</td>
                        <td class="border px-4 py-2">{{ $usr->ukuran }}</td>
                        <td class="border px-4 py-2">{{ $usr->jenis_tutup }}</td>
                        <td class="border px-4 py-2 max-w-sm">{{ $usr->harga_beli }}</td>
                        <td class="border px-4 py-2 max-w-sm">{{ $usr->harga_jual }}</td>
                        <td class="border px-4 py-2">
                            <form action="/tambahstok/{{$usr->id_barang}}" method="post">
                                @csrf
                                @method('put')
                                <Label for="stok">Masukkan stok: </Label>
                                <input type="number" name="stok" id="stok" required>
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                            </form>
                        </td>
                        <td class="border px-4 py-2 flex">
                            <a href="/stok/edit/{{ $usr->id_barang }}"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                            <form action="/stok/delete/{{ $usr->id }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded ml-2">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination Links -->
    <div class="text-center mt-4, bg-blue-700, text-white">
        {{ $barang->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </div>
@endif
