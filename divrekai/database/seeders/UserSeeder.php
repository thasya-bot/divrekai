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

        User::create([
            'username' => 'Admin Unit',
            'password' => Hash::make('adminunit123'),
            'role_id' => $adminUnitRole->id,
            'unit_id' => null,
            
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