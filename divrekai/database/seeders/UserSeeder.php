<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPicRole = Role::where('username', 'admin_pic')->first();

        User::create([
            'username' => 'Admin PIC',
            'password' => Hash::make('adminpic123'),
            'role_id' => $adminPicRole->id,
            'unit_id' => null,
            
        ]);
        
         $adminUnitRole = Role::where('username', 'admin_unit')->first();
            $adminUnits = [
            1  => 'admin it',
            2  => 'admin operasi',
            3  => 'admin pengamanan',
            4  => 'admin humas',
            5  => 'admin sdm',
            6  => 'admin fp',
            7  => 'admin ap',
            8  => 'admin ab',
            9  => 'admin jj',
            10 => 'admin sarana',
            11 => 'admin aset',
            12 => 'admin kna',
            13 => 'admin pengadaan',
            14 => 'admin penagihan',
            15 => 'admin keuangan',
            16 => 'admin sitel',
            17 => 'admin dokumen',
            18 => 'admin kesehatan',
        ];

        foreach ($adminUnits as $unitId => $username) {
            User::create([
                'username' => $username,
                'password' => Hash::make('adminunit123'),
                'role_id'  => $adminUnitRole->id,
                'unit_id'  => $unitId,
            
        ]);
         $pimpinanRole = Role::where('username', 'pimpinan')->first();

        User::create([
            'username' => 'Pimpinan',
            'password' => Hash::make('pimpinan123'),
            'role_id' => $pimpinanRole->id,
            'unit_id' => null,
            
        ]);
    }
}
}