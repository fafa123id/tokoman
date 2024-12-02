<div class="gap-6">

    <div class="flex justify-center mt-6">

        <form action="{{ route('search') }}" method="GET" class="w-full max-w-xl">
            <div class="flex flex-col items-center border-b border-teal-500 py-2">
                <!-- Input text -->
                <input
                    class="appearance-none bg-transparent border-none w-full text-white mb-3 py-1 px-2 leading-tight focus:outline-none"
                    type="text" name="search" placeholder="Search items..." aria-label="Search items">

                <!-- Dropdowns -->
                <div class="flex w-full justify-between mb-3">
                    <select name="stock"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black mr-2">
                        <option value="">Semua Stok</option>
                        <option value="high">Stok banyak (>20)</option>
                    </select>
                    <select name="type"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-black">
                        <option value="">Semua Tipe</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="rendah">Rendah</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button
                    class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded w-full mb-3"
                    type="submit">
                    Cari
                </button>

                <!-- Dashboard Link -->

            </div>
        </form>
    </div>
    @if($barangs->isEmpty()){
        <h1 class="text-black dark:text-white text-center text-3xl mt-10">Belum ada produk</h1>
        }
    @else
        <h1 class="text-black dark:text-white text-center text-3xl mt-10">Produk kami:</h1>
        <div class="w-full flex justify-center items-center">
            <div class="container my-10">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 max-lg:gap-4">
                    @foreach ($barangs as $barang)
                        <x-stok-barang-card :barang="$barang" />
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto mb-2">
            {{ $barangs->appends(request()->query())->onEachSide(1)->links() }}
        </div>
    @endif
    @if($agents->isEmpty()){
        <h1 class="text-black dark:text-white text-center text-3xl ">Belum ada agen</h1>
        }
    @else
        <h1 class="text-black dark:text-white text-center text-3xl ">Kerjasama Agen</h1>
        <div class="w-full flex justify-center items-center">
            <div class="container my-10">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 max-lg:gap-4">
                    @foreach ($agents as $agent)
                        <x-agent-card :agent="$agent" />
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto mb-2">
            {{ $agents->appends(request()->query())->onEachSide(1)->links() }}
        </div>
    @endif
    @if($mitra->isEmpty()){
        <h1 class="text-black dark:text-white text-center text-3xl">Belum ada mitra</h1>
        }
    @else
        <h1 class="text-black dark:text-white text-center text-3xl">Kerjasama Mitra</h1>
        <div class="w-full flex justify-center items-center">
            <div class="container my-10">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 lg:grid-cols-2 max-lg:gap-4">
                    @foreach ($mitra as $mitras)
                        <x-mitra-card :mitra="$mitras" />
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto mb-2">
            {{ $mitra->appends(request()->query())->onEachSide(1)->links() }}
        </div>
    @endif
</div>