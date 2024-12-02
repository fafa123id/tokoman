<div
    class="mx-auto min-[400px]:w-[380px] rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-500 bg-gray-100 dark:bg-gray-700 mb-10 w-full">
    @if($mitra->images == '')
        <img class="rounded-xl mx-auto mt-[15px] max-[400px]:w-[300px]  w-[350px] h-[350px]"
            src="https://placeholder.pics/svg/300" alt="Gambar {{ $mitra->name }}">
    @else
        <img class=" rounded-xl mx-auto mt-[15px] max-[400px]:w-[300px] w-[350px] h-[350px]"
            src="https://tokoman.s3.ap-southeast-2.amazonaws.com/mitra/{{$mitra->images}}" alt="Gambar {{ $mitra->name }}">
    @endif
    <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2 text-blue-800 dark:text-teal-500">{{ $mitra->name }}</div>
        <p class="text-black text-base dark:text-white">
            <strong>Alamat:</strong> {{ $mitra->address }}<br>
        </p>
        <p class="text-black text-base dark:text-white">
            <strong>No. Telepon:</strong> {{ $mitra->noTelp }}
        </p>
        <button onclick="opengappsmitra{{$mitra->id}}()"
            class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 flex">Klik untuk
            melihat alamat lengkap
            <img class="max-w-[30px] max-h-[30px] ml-2"
                src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pngwing.com+(4).png" alt="">
        </button>
    </div>
</div>
<script>
    function opengappsmitra{{$mitra->id}}() {
        window.open('{{$mitra->gmaps}}')
    }
</script>