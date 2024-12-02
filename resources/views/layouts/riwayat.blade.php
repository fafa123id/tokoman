<div class="flex justify-center">
    <form action="{{ route('riwayatFilter') }}" method="GET" class="w-full max-w-xl text-center">
        <div class="flex flex-col items-center py-2">
            <!-- Dropdowns -->
            <div class="flex w-full justify-between mb-3">
                <select name="jenis_riwayat"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black mr-2">
                    <option value="">Semua Riwayat</option>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
                <select name="year"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black mr-2">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year->year }}">{{ $year->year }}</option>
                    @endforeach
                </select>

                <select name="month"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black mr-2">
                    <option value="">Semua Bulan</option>
                    @foreach($months as $month)
                        <option value="{{ $month->month }}">{{ date("F", mktime(0, 0, 0, $month->month, 10)) }}</option>
                    @endforeach
                </select>

                <select name="week"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black">
                    <option value="">Semua Minggu</option>
                    <option value="today">Hari ini</option>
                    <option value="1">Minggu 1</option>
                    <option value="2">Minggu 2</option>
                    <option value="3">Minggu 3</option>
                    <option value="4">Minggu 4</option>
                    <option value="5">Minggu 5</option>
                </select>
            </div>

            <!-- Search Button -->
            <button
                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded w-full mb-3"
                type="submit">
                Filter
            </button>
        </div>
    </form>
</div>


<div class="mt-8 bg-white p-4 rounded-lg shadow">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Semua Transaksi</h2>
    </div>
    @if ($riwayatTerbaru->count() == 0)
        <p class="text-center text-gray-500">Belum ada data</p>
    @endif
    @foreach ($riwayatTerbaru as $riwayat)
        <ul
            class="divide-y divide-gray-200 px-2 py-2 rounded-lg my-2 {{$riwayat->jenis_riwayat == 'masuk' ? 'bg-blue-100' : 'bg-green-100'}}">
            <li class="py-4 flex justify-between items-center ">
                <div>
                    <p class="text-md text-gray-600">{{$riwayat->nama_barang}}</p>
                    <p class="text-sm">Jumlah: {{$riwayat->jumlah}}</p>
                    <p class="text-sm">{{$riwayat->jenis_riwayat == 'masuk' ? 'Beli' : 'Jual'}} Rp:
                        {{$riwayat->total_harga}}</p>
                </div>
                <div class="text-sm text-gray-600">{{$riwayat->tanggal}} {{$riwayat->jam_dibuat}}
                    <div class="text-sm text-gray-600">stok {{$riwayat->jenis_riwayat}}</div>
                </div>

            </li>
            <!-- More transactions here -->
        </ul>
    @endforeach
</div>
<div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto">
    {{ $riwayatTerbaru->appends(request()->query())->onEachSide(1)->links() }}
</div>

