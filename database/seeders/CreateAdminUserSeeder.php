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
        'name'           => 'Administrator',
        'nip'            => '199001010001',
        'email'          => 'ncadvertise@gmail.com',
        'username'       => 'super',
        'password'       => Hash::make('admin!@#123'),
        'unit'           => 0,
        'staff'          => 0,
        'no_rek'         => '1234567890',
        'tempat_lahir'   => 'Banyuwangi',
        'tanggal_lahir'  => '1990-01-01',
        'alamat'         => 'Politeknik Negeri Banyuwangi',
        'no_hp'          => '081234567890',
        'file_sk'        => null,
        'role_aktif'     => 'admin',
        'status'         => 2,
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

        User::create([
        'name' => 'Ahmad Fauzi',
        'nip' => '199001010003',
        'email' => 'ahmad.fauzi@example.com',
        'username' => 'ahmadfauzi',
        'password' => Hash::make('anggota123'),
        'unit' => 1,
        'staff' => 1,
        'no_rek' => '1000000001',
        'tempat_lahir' => 'Banyuwangi',
        'tanggal_lahir' => '1990-01-01',
        'alamat' => 'Banyuwangi',
        'no_hp' => '081234560001',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Budi Santoso',
        'nip' => '199002020004',
        'email' => 'budi.santoso@example.com',
        'username' => 'budisantoso',
        'password' => Hash::make('anggota123'),
        'unit' => 2,
        'staff' => 2,
        'no_rek' => '1000000002',
        'tempat_lahir' => 'Jember',
        'tanggal_lahir' => '1990-02-02',
        'alamat' => 'Jember',
        'no_hp' => '081234560002',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Dewi Lestari',
        'nip' => '199003030005',
        'email' => 'dewi.lestari@example.com',
        'username' => 'dewilestari',
        'password' => Hash::make('anggota123'),
        'unit' => 3,
        'staff' => 3,
        'no_rek' => '1000000003',
        'tempat_lahir' => 'Situbondo',
        'tanggal_lahir' => '1990-03-03',
        'alamat' => 'Situbondo',
        'no_hp' => '081234560003',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Eko Prasetyo',
        'nip' => '199004040006',
        'email' => 'eko.prasetyo@example.com',
        'username' => 'ekoprasetyo',
        'password' => Hash::make('anggota123'),
        'unit' => 4,
        'staff' => 4,
        'no_rek' => '1000000004',
        'tempat_lahir' => 'Bondowoso',
        'tanggal_lahir' => '1990-04-04',
        'alamat' => 'Bondowoso',
        'no_hp' => '081234560004',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Fitri Handayani',
        'nip' => '199005050007',
        'email' => 'fitri.handayani@example.com',
        'username' => 'fitrihandayani',
        'password' => Hash::make('anggota123'),
        'unit' => 5,
        'staff' => 5,
        'no_rek' => '1000000005',
        'tempat_lahir' => 'Banyuwangi',
        'tanggal_lahir' => '1990-05-05',
        'alamat' => 'Banyuwangi',
        'no_hp' => '081234560005',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Hendra Wijaya',
        'nip' => '199006060008',
        'email' => 'hendra.wijaya@example.com',
        'username' => 'hendrawijaya',
        'password' => Hash::make('anggota123'),
        'unit' => 6,
        'staff' => 6,
        'no_rek' => '1000000006',
        'tempat_lahir' => 'Malang',
        'tanggal_lahir' => '1990-06-06',
        'alamat' => 'Malang',
        'no_hp' => '081234560006',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Indah Permata',
        'nip' => '199007070009',
        'email' => 'indah.permata@example.com',
        'username' => 'indahpermata',
        'password' => Hash::make('anggota123'),
        'unit' => 7,
        'staff' => 7,
        'no_rek' => '1000000007',
        'tempat_lahir' => 'Surabaya',
        'tanggal_lahir' => '1990-07-07',
        'alamat' => 'Surabaya',
        'no_hp' => '081234560007',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

    User::create([
        'name' => 'Joko Saputra',
        'nip' => '199008080010',
        'email' => 'joko.saputra@example.com',
        'username' => 'jokosaputra',
        'password' => Hash::make('anggota123'),
        'unit' => 8,
        'staff' => 8,
        'no_rek' => '1000000008',
        'tempat_lahir' => 'Lumajang',
        'tanggal_lahir' => '1990-08-08',
        'alamat' => 'Lumajang',
        'no_hp' => '081234560008',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);

        User::create([
        'name' => 'Rina Oktaviani',
        'nip' => '199009090011',
        'email' => 'rina.oktaviani@example.com',
        'username' => 'rinaoktaviani',
        'password' => Hash::make('anggota123'),
        'unit' => 9,
        'staff' => 9,
        'no_rek' => '1000000009',
        'tempat_lahir' => 'Banyuwangi',
        'tanggal_lahir' => '1990-09-09',
        'alamat' => 'Jl. Ikan Tongkol No. 15, Banyuwangi',
        'no_hp' => '081234560009',
        'file_sk' => null,
        'role_aktif' => 'anggota',
        'status' => 2,
    ])->assignRole($anggotaRole);


        // =======================
        // OPERATOR
        // =======================
        $operatorRole = Role::create([
            'name' => 'operator'
        ]);

        $operatorRole->syncPermissions($anggotaPermissions);
    }
}