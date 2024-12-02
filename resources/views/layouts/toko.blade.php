<div class="bg-white dark:bg-gray-800 shadow">
    <div class="relative h-[600px]">
        <img id="slideshow" class="absolute inset-0 w-full h-full object-fill opacity-50 fade"
            src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/TokoPlastik.jpg" alt="Gambar Toko">
        <div class="absolute inset-0 flex items-center justify-center text-white text-4xl font-bold text-center">
            <h1 class="text-7xl font-bold">Toko Aneka Botol Plastik</h1>
        </div>
    </div>
</div>

<script>
    const images = [
        "https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/TokoPlastik.jpg",
        "https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pelastik.jpg",
        "https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/pelastikstore.jpg"
    ];
    let currentIndex = 0;

    function changeImage() {
        const imgElement = document.getElementById('slideshow');
        imgElement.classList.remove('opacity-50');
        imgElement.classList.add('opacity-20');

        setTimeout(() => {
            currentIndex = (currentIndex + 1) % images.length;
            imgElement.src = images[currentIndex];
            imgElement.classList.remove('opacity-20');
            imgElement.classList.add('opacity-50');
        }, 1000);
    }

    setInterval(changeImage, 3000);
</script>

<style>
    .fade {
        transition: opacity 1s ease-in-out;
    }
</style>