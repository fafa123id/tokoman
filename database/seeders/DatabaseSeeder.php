<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\kontak;
use App\Models\tokenRegister;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Test Admin1',
            'email' => 'admin1@admin.admin',
            'password' => '12345678',
            'role_id'=>'0',
        ]);
        User::factory()->create([
            'name' => 'Test Admin2',
            'email' => 'admin2@admin.admin',
            'password' => '12345678',
            'role_id'=>'0',
        ]);
        User::factory()->create([
            'name' => 'Test Admin3',
            'email' => 'admin3@admin.admin',
            'password' => '12345678',
            'role_id'=>'0',
        ]);
        User::factory()->create([
            'name' => 'Test kary1',
            'email' => 'kary1@kary.kary',
            'password' => '12345678',
            'role_id'=>'1',
        ]);
        User::factory()->create([
            'name' => 'Test kary2',
            'email' => 'kary2@kary.kary',
            'password' => '12345678',
            'role_id'=>'1',
        ]);
        tokenRegister::insert([
            'role_id'=>'0',
            'token'=>Hash::make('adminregis00'),
        ]);
        tokenRegister::insert([
            'role_id'=>'1',
            'token'=>Hash::make('karyregis11'),
        ]);
        kontak::insert([
            'name'=>'ZidanElek',
            'noHp'=>'6281335760738',
        ]);
        // $this ->call(BarangSeeder::class);
    }
}

