@include('modalCustom.themodal', ['message' => 'Yakin Mau Edit Data?', 'form' => 'formEdit'])
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4 dark:text-white text-black">Edit mitra</h1>
    <form id="formEdit" action="{{ route('mitra.update', $mitra->id) }}" method="POST" class="dark:text-white text-black"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-4">
            <label for="name" class="block">Nama</label>
            <input type="text" name="name" value="{{ $mitra->name }}" class="w-full px-4 py-2 text-black" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block">Alamat</label>
            <input type="text" name="address" value="{{ $mitra->address }}" class="w-full px-4 py-2 text-black"
                required>
        </div>
        <div class="mb-4">
            <label for="gmaps" class="block">Link Gmaps</label>
            <input type="text" name="gmaps" value="{{ $mitra->gmaps }}" class="w-full px-4 py-2 text-black" required>
        </div>
        <div class="mb-4">
            <label for="images" class="block">Gambar</label>
            <input type="file" accept=".jpg, .jpeg, .png" name="images" class="w-full px-4 py-2 text-black bg-white mb-2">
            <img class="w-[100px] h-[100px]" src="https://tokoman.s3.ap-southeast-2.amazonaws.com/mitra/{{$mitra->images}}" alt="">

        </div>
        <div class="mb-4">
            <label for="telephone" class="block">Telepon</label>
            <input type="text" name="noTelp" value="{{ $mitra->noTelp }}" class="w-full px-4 py-2 text-black" required>
        </div>
        <button type="button" onclick="validasiForm()" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>