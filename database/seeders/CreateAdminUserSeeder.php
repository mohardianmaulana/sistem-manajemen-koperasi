<?php

namespace Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Hash;
use DB;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        \Artisan::call('permission:create-permission-routes');

        // =======================
        // ADMIN
        // =======================
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'ncadvertise@gmail.com',
            'username' => 'super',
            'password' => Hash::make('admin!@#123'),
            'unit' => 0,
            'staff' => 0,
            'role_aktif' => 'admin',
            'status' => 2
        ]);

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $permissions = Permission::pluck('id', 'id')->all();

        $adminRole->syncPermissions($permissions);

        $admin->assignRole($adminRole);

        // =======================
        // ANGGOTA
        // =======================
        $anggotaPermissions = Permission::where('name', 'adminlte.darkmode.toggle')
            ->orWhere('name', 'logout.perform')
            ->orWhere('name', 'home.index')
            ->orWhere('name', 'login.show')
            ->pluck('id', 'id')
            ->all();

        $anggotaRole = Role::create([
            'name' => 'anggota'
        ]);

        $anggotaRole->syncPermissions($anggotaPermissions);

        $anggota = User::create([
            'name' => 'Anggota',
            'email' => 'anggota@example.com',
            'username' => 'anggota',
            'password' => Hash::make('anggota123'),
            'unit' => 1,
            'staff' => 1,
            'role_aktif' => 'anggota',
            'status' => 2
        ]);

        $anggota->assignRole($anggotaRole);

        // =======================
        // OPERATOR
        // =======================
        $operatorRole = Role::create([
            'name' => 'operator'
        ]);

        $operatorRole->syncPermissions($anggotaPermissions);
    }
}