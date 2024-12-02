<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class barangSeeder extends Seeder
{
    public function run()
    {
        $csrfToken = csrf_token();
        $faker = Faker::create('id_ID');
        $client = new Client([
            'base_uri' => 'http://127.0.0.1:8000/', // Ganti dengan URL aplikasi Laravel Anda
            'timeout'  => 10.0,
            'cookies' => true ,
        ]);

        for ($i = 0; $i < 1; $i++) {
            $nama_barang = $faker->word;
            $ukuran = $faker->randomElement(['500ml', '750ml', '1000ml']);
            $jenis_tutup = $faker->randomElement(['tinggi', 'rendah']);
            $bal = $faker->numberBetween(1, 20);

            try {
                $response = $client->request('POST', '/testingAPI123', [
                    'headers' => [
                        'X-CSRF-TOKEN' => $csrfToken,
                    ],
                    'form_params' => [
                        'nama' => $nama_barang,
                        'bal' => $bal,
                        'stok' => $faker->numberBetween(10, 100),
                        'buy' => $faker->numberBetween(1000, 10000),
                        'sell' => $faker->numberBetween(10000, 20000),
                        'jenis' => $jenis_tutup,
                        'ukuran' => $ukuran,
                    ]
                ]);
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                echo 'Request failed: ' . $e->getMessage();
            }
        }
    }
}