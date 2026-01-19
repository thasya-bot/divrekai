<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::insert([
            ['nama_unit' => 'IT'],
            ['nama_unit' => 'Operasi'],
            ['nama_unit' => 'Pengamanan'],
            ['nama_unit' => 'Humas'],
            ['nama_unit' => 'SDM dan Umum'],
            ['nama_unit' => 'Fasilitas Penumpang'],
            ['nama_unit' => 'Angkutan Penumpang'],
            ['nama_unit' => 'Angkutan Barang'],
            ['nama_unit' => 'Jalan dan Jembatan'],
            ['nama_unit' => 'Sarana'],
            ['nama_unit' => 'Aset'],
            ['nama_unit' => 'Komersial Non Angkutan'],
            ['nama_unit' => 'Pengadaan Barang dan Jasa'],
            ['nama_unit' => 'Penagihan'],
            ['nama_unit' => 'Keuangan'],
            ['nama_unit' => 'Sinyal Telekomunikasi'],
            ['nama_unit' => 'Dokumen'],
            ['nama_unit' => 'Kesehatan'],
        ]);
    }
}
