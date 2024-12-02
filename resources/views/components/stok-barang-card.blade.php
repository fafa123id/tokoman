@if ($barang->stok > 0)
    <?php $pathimage = 'https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pngwing.com.png'?>
    <div
        class="mx-auto min-[400px]:w-[380px] rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-500 bg-gray-100 dark:bg-gray-700 mb-10 w-full">
        @if($barang->pathImg1 == '')
            <img class="rounded-xl mx-auto mt-[15px] max-[400px]:w-[300px]  w-[350px] h-[350px]" src="https://placeholder.pics/svg/300"
                alt="Gambar {{ $barang->nama_barang }}">
        @else
            @if($barang->pathImg2 == '')
                <img class=" rounded-xl mx-auto mt-[15px] max-[400px]:w-[300px] w-[350px] h-[350px]" src="{{$barang->pathImg1}}"
                    alt="Gambar {{ $barang->nama_barang }}">
            @else
                <div class="mx-auto w-[350px] h-[350px] rounded-lg">
                    <!-- Slides -->
                    <div class="relative h-[350px]">
                        <button onclick="showNextImage{{$barang->id_barang}}()"
                            class="absolute z-10 inset-0 w-[30px] h-[30px] mt-[175px] max-[400px]:translate-x-7"><img
                                src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pngwing.com+(3).png"></button>

                        <img src="{{$barang->pathImg1}}"
                            class="rounded-xl mx-auto max-[400px]:w-[300px] mt-[15px] w-[350px] h-[350px] {{$barang->nama_barang}} transition-opacity duration-1000 ease-in-out">

                        <img src="{{$barang->pathImg2}}"
                            class="{{$barang->nama_barang}}2 hidden transition-opacity duration-1000 ease-in-out rounded-xl mx-auto mt-[15px] max-[400px]:w-[300px] w-[350px] h-[350px]">
                        <button onclick="showNextImage{{$barang->id_barang}}()"
                            class="absolute inset-0 w-[30px] h-[30px] mt-[175px] ml-[320px] max-[400px]:-translate-x-7"><img
                                src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pngwing.com+(2).png"></button>
                        <script>
                            let {{$barang->jenis_tutup . $barang->id_barang . 'index'}}=0;
                            let {{$barang->jenis_tutup . $barang->id_barang}} = document.getElementsByClassName('{{$barang->nama_barang}}');
                            let {{$barang->jenis_tutup . $barang->id_barang . '2'}} = document.getElementsByClassName('{{$barang->nama_barang . '2'}}');
                            function showNextImage{{$barang->id_barang}}() {
                                for (let i = 0; i < {{$barang->jenis_tutup . $barang->id_barang}}.length; i++) {
                                    if ({{$barang->jenis_tutup . $barang->id_barang . 'index'}}==0||{{$barang->jenis_tutup . $barang->id_barang . 'index'}}%2==0) {
                                                                {{$barang->jenis_tutup . $barang->id_barang}}[i].classList.add('transition-opacity', 'opacity-0');
                                                                {{$barang->jenis_tutup . $barang->id_barang . '2'}}[i].classList.remove('transition-opacity', 'opacity-0');
                                        setTimeout(() => {
                                                                    {{$barang->jenis_tutup . $barang->id_barang}}[i].classList.add('hidden');
                                                                    {{$barang->jenis_tutup . $barang->id_barang . '2'}}[i].classList.remove('hidden');
                                        }, 1000);
                                    }
                                    else {
                                                                {{$barang->jenis_tutup . $barang->id_barang}}[i].classList.remove('transition-opacity', 'opacity-0');
                                                                {{$barang->jenis_tutup . $barang->id_barang . '2'}}[i].classList.add('transition-opacity', 'opacity-0');
                                        setTimeout(() => {
                                                                {{$barang->jenis_tutup . $barang->id_barang . '2'}}[i].classList.remove('transition-opacity', 'opacity-0');
                                                                {{$barang->jenis_tutup . $barang->id_barang}}[i].classList.remove('hidden');
                                                                {{$barang->jenis_tutup . $barang->id_barang . '2'}}[i].classList.add('hidden');
                                        }, 1000);
                                    }
                                                            {{$barang->jenis_tutup . $barang->id_barang . 'index'}}++
                                                        }
                            }
                        </script>
                    </div>
                </div>



            @endif
        @endif
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2 text-blue-800 dark:text-teal-500">{{ $barang->nama_barang }}</div>
            <p class="text-black text-base dark:text-white">
                <strong>Jenis Tutup:</strong> {{ $barang->jenis_tutup }}<br>
            </p>
            <p class="text-black text-base dark:text-white">
                <strong>Ukuran:</strong> {{ $barang->ukuran }}
            </p>
            <p class="text-black text-base dark:text-white">
                <strong>Stok:</strong> {{ $barang->stok }}
            </p>
            <p class="text-black text-base dark:text-white">
                <strong>Isi per Bal:</strong> {{ $barang->bal }}
            </p>
            <p class="text-black text-base dark:text-white">
                <strong>Harga:</strong> {{ $barang->harga_jual }}
            </p>
            <div class="text-black text-base dark:text-white">
                <strong>Minat?</strong>
            </div>
            <button onclick="openWhatsapp{{$barang->id}}()"
                class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 flex">Order di sini
                <img class="max-w-[30px] max-h-[30px] ml-2" src="{{ $pathimage }}" alt="">
            </button>
        </div>
    </div>
    <script>
        function openWhatsapp{{$barang->id}}(){
            window.open('/wa/{{$barang->nama_barang}}')
        }
    </script>
@endif
