<div class="">
    <div class="flex flex-col mb-4 justify-center">
        <!-- Search -->
        <div class="mx-auto px-4 container max-w-xl">
            <div class="flex justify-center mt-6 gap-4">
                <form action="{{ route('stok.search') }}" method="GET" class="w-full max-w-xl">
                    <div class="flex flex-col items-center border-b border-teal-500 py-2">
                        <!-- Input text -->
                        <input
                            class="appearance-none bg-transparent border-none w-full text-white mb-3 py-1 px-2 leading-tight focus:outline-none"
                            type="text" name="search" placeholder="Cari Item..." aria-label="Search items">
                        <select name="type"
                            class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black mb-2">
                            <option value="">Semua Tipe</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="rendah">Rendah</option>
                        </select>

                        <!-- Search Button -->
                        <button
                            class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded w-full mb-3"
                            type="submit">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex justify-center my-4 min-[1500px]:my-0 min-[1500px]:-translate-y-7 min-[1500px]:ml-[1048px]">
            <div class=" text-gray-900 dark:text-gray-100 start-0 ">
                <a href="/stok/add"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 mb-2 px-4 rounded justify-end">
                    + Tambah data barang</a>
            </div>
        </div>
        @if($barangs->isEmpty())
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
                            <th class="px-4 py-2">Isi/Bal</th>
                            <th class="px-4 py-2">Ukuran</th>
                            <th class="px-4 py-2">Jenis Tutup</th>
                            <th class="px-4 py-2">Harga Beli</th>
                            <th class="px-4 py-2">Harga Jual</th>
                            <th class="px-4 py-2">Tambahkan Stok</th>
                            <th class="px-4 py-2">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $index => $usr)
                            <tr class="bg-gray-100">
                                <td class="border px-4 py-2">{{ $barangs->firstItem() + $index }}</td>
                                <td class="border px-4 py-2">{{ $usr->id_barang }}</td>
                                <td class="border px-4 py-2">{{ $usr->nama_barang }}</td>
                                <td class="border px-4 py-2">{{ $usr->stok }}</td>
                                <td class="border px-4 py-2">{{ $usr->bal }}</td>
                                <td class="border px-4 py-2">{{ $usr->ukuran }}</td>
                                <td class="border px-4 py-2">{{ $usr->jenis_tutup }}</td>
                                <td class="border px-4 py-2 max-w-sm">{{ $usr->harga_beli }}</td>
                                <td class="border px-4 py-2 max-w-sm">{{ $usr->harga_jual }}</td>
                                <td class="border px-4 py-2 w-sm">
                                    <form action="/tambahstok/{{$usr->id_barang}}" method="post">
                                        @csrf
                                        @method('put')
                                        <input type="number" placeholder="Stok..." name="stok" id="stok" class="w-24" required>
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Tambah</button>
                                    </form>
                                </td>
                                <td class="border px-4 py-2 ">
                                    <div class="flex items-center justify-center">
                                        <a href="/stok/edit/{{ $usr->id_barang }}"
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded ">
                                            Edit
                                        </a>
                                        <form action="/stok/delete/{{ $usr->id }}" method="POST" id="deleteForm{{$usr->id}}">
                                            @csrf
                                            @method('delete')
                                            <button type="button" onclick="validasiForm{{$usr->id}}()"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded ml-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @include('modalCustom.themodal', ['message' => 'Yakin Mau Hapus Barang '.$usr->nama_barang, 'form' => 'deleteForm'.$usr->id,'theVal'=>$usr->id])
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <!-- Pagination Links -->
    </div>
    <div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto">
        {{ $barangs->appends(request()->query())->onEachSide(1)->links() }}
    </div>
</div>