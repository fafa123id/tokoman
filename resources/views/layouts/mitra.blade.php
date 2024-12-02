@foreach ($mitra as $agent)
    <div class="kumpulanModal">
        @include('modalCustom.themodal', ['message' => 'Yakin Mau Hapus Mitra ' . $agent->name, 'form' => 'deleteForm' . $agent->id, 'theVal' => $agent->id])
    </div>
@endforeach
<div class="container mx-auto px-4 dark:text-white text-black">
    <h1 class="text-3xl text-center font-bold my-4">Mitra</h1>
    <a href="{{ route('mitra.add') }}"
        class="bg-blue-500 px-4 py-2 rounded hover:bg-gray-700 hover:rounded-md hover:shadow">Tambah Mitra</a>
    @if($mitra->isEmpty())
        <p class="text-center">Belum ada data</p>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 my-4">
        @foreach ($mitra as $mitras)
            <div class="dark:bg-gray-500 bg-white p-4 rounded shadow relative">
                <div class="flex justify-between items-center pb-4" x-data="{ open: false }">
                    <h1 class="text-xl font-bold">{{ $mitras->name }}</h1>
                    <button @click="open = !open" onclick="document.getElementById('modal{{$mitras->id}}').classList.remove('hidden')"
                        class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-1 text-center inline-flex items-center dark:bg-blue-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                    <div x-show="open" id="modal{{$mitras->id}}" @click.away="open = false"
                        class="absolute right-0 mr-3 mt-2 top-12 w-24 bg-white dark:bg-gray-700 rounded-md shadow-lg p-1 z-20 hidden">
                        <a href="{{ route('mitra.edit', $mitras->id) }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Edit</a>
                        <form id="deleteForm{{$mitras->id}}" action="{{ route('mitra.destroy', $mitras->id) }}"
                            method="POST">
                            @csrf
                            @method('delete')
                            <button type="button" onclick="validasiForm{{$mitras->id}}()"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Hapus</button>
                        </form>
                    </div>
                </div>
                <p class=" break-words">{{ $mitras->address}}</p>
                <p>{{ $mitras->noTelp }}</p>
                <div class>
                    <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/mitra/{{$mitras->images}}" alt="Image"
                        class="w-full h-60 object-cover my-2">
                </div>
            </div>
        @endforeach
        <div class="mt-8 flex min-[477px]:justify-center mx-auto overflow-auto mb-2">
            {{ $mitra->appends(request()->query())->onEachSide(1)->links() }}
        </div>
    </div>
</div>