<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();

        /*
        |--------------------------------------------------------------------------
        | CORE - MASTER
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 1,
            'modul' => 'Core',
            'label' => 'Master',
            'url' => '',
            'icon' => 'fas fa-columns',
            'can' => '',
            'active' => serialize(['admin']),
            'urut' => 7,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 2,
            'modul' => 'Core',
            'label' => 'User',
            'url' => 'users',
            'icon' => 'fas fa-fw fa-users',
            'can' => serialize(['users', 'users*']),
            'active' => serialize(['admin']),
            'urut' => 1,
            'parent_id' => 1,
        ]);

        Menu::create([
            'id' => 3,
            'modul' => 'Core',
            'label' => 'Menu',
            'url' => 'menus',
            'icon' => 'fas fa-bars',
            'can' => serialize(['menus', 'menus*']),
            'active' => serialize(['admin']),
            'urut' => 2,
            'parent_id' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | CORE - ROLES & PERMISSIONS
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 4,
            'modul' => 'Core',
            'label' => 'Roles & Permisions',
            'url' => '',
            'icon' => 'fas fa-address-card',
            'can' => '',
            'active' => serialize(['admin']),
            'urut' => 8,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 5,
            'modul' => 'Core',
            'label' => 'Roles',
            'url' => 'roles',
            'icon' => 'far fa-circle',
            'can' => serialize(['roles', 'roles*']),
            'active' => serialize(['admin']),
            'urut' => 1,
            'parent_id' => 4,
        ]);

        Menu::create([
            'id' => 6,
            'modul' => 'Core',
            'label' => 'Permissions',
            'url' => 'permissions',
            'icon' => 'far fa-circle',
            'can' => serialize(['permissions', 'permissions*']),
            'active' => serialize(['admin']),
            'urut' => 2,
            'parent_id' => 4,
        ]);


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA - PINJAMAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 7,
            'modul' => 'Core',
            'label' => 'Pinjaman',
            'url' => '',
            'icon' => 'far fa-circle',
            'can' => '',
            'active' => serialize(['anggota']),
            'urut' => 4,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 8,
            'modul' => 'Core',
            'label' => 'Pengajuan Pinjaman',
            'url' => '/pengajuan_pinjaman/indexAnggota',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pengajuan_pinjaman/indexAnggota',
                '/pengajuan_pinjaman/indexAnggota*'
            ]),
            'active' => serialize(['anggota']),
            'urut' => 2,
            'parent_id' => 7,
        ]);

        Menu::create([
            'id' => 9,
            'modul' => 'Core',
            'label' => 'Simulasi Pinjaman',
            'url' => 'simulasi_pinjaman/',
            'icon' => 'far fa-circle',
            'can' => serialize([
                'simulasi_pinjaman/',
                'simulasi_pinjaman/*'
            ]),
            'active' => serialize(['anggota']),
            'urut' => 1,
            'parent_id' => 7,
        ]);

        Menu::create([
            'id' => 10,
            'modul' => 'Core',
            'label' => 'Riwayat pinjaman',
            'url' => '/pinjaman/indexAnggota',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pinjaman/indexAnggota',
                '/pinjaman/indexAnggota*'
            ]),
            'active' => serialize(['anggota']),
            'urut' => 3,
            'parent_id' => 7,
        ]);

        Menu::create([
            'id' => 11,
            'modul' => 'Core',
            'label' => 'Angsuran',
            'url' => '/angsuran/getAngsuran',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/angsuran/getAngsuran',
                '/angsuran/getAngsuran*'
            ]),
            'active' => '',
            'urut' => 3,
            'parent_id' => 7,
        ]);


        /*
        |--------------------------------------------------------------------------
        | KETUA - PERSETUJUAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 12,
            'modul' => 'Core',
            'label' => 'Persetujuan',
            'url' => '/persetujuan',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/persetujuan',
                '/persetujuan*'
            ]),
            'active' => serialize(['ketua']),
            'urut' => 2,
            'parent_id' => 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | KOORDINATOR - MASTER PINJAMAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 13,
            'modul' => 'Core',
            'label' => 'Master Pinjaman',
            'url' => '',
            'icon' => 'far fa-circle',
            'can' => '',
            'active' => serialize(['koordinator']),
            'urut' => 6,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 14,
            'modul' => 'Core',
            'label' => 'Jenis jaminan',
            'url' => 'jaminan/index',
            'icon' => 'far fa-circle',
            'can' => serialize([
                'jaminan/index',
                'jaminan/index*'
            ]),
            'active' => serialize(['koordinator']),
            'urut' => 1,
            'parent_id' => 13,
        ]);

        Menu::create([
            'id' => 15,
            'modul' => 'Core',
            'label' => 'Jenis skema pinjaman',
            'url' => 'skema_pinjaman/',
            'icon' => 'far fa-circle',
            'can' => serialize([
                'skema_pinjaman/',
                'skema_pinjaman/*'
            ]),
            'active' => serialize(['koordinator']),
            'urut' => 2,
            'parent_id' => 13,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PINJAMAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 16,
            'modul' => 'Core',
            'label' => 'Pinjaman',
            'url' => '/pinjaman',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pinjaman',
                '/pinjaman*'
            ]),
            'active' => serialize([
                'ketua',
                'bendahara',
                'koordinator'
            ]),
            'urut' => 1,
            'parent_id' => 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 17,
            'modul' => 'Core',
            'label' => 'Verifikasi',
            'url' => '',
            'icon' => 'far fa-circle',
            'can' => '',
            'active' => serialize([
                'koordinator'
            ]),
            'urut' => 5,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 18,
            'modul' => 'Core',
            'label' => 'Pengajuan Pinjaman',
            'url' => 'pengajuan_pinjaman/',
            'icon' => 'far fa-circle',
            'can' => serialize([
                'pengajuan_pinjaman/',
                'pengajuan_pinjaman/*'
            ]),
            'active' => serialize(['koordinator']),
            'urut' => 1,
            'parent_id' => 17,
        ]);

        Menu::create([
            'id' => 19,
            'modul' => 'Core',
            'label' => 'Verifikasi pembayaran',
            'url' => '/pembayaran/verifikasi',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pembayaran/verifikasi',
                '/pembayaran/verifikasi*'
            ]),
            'active' => serialize(['koordinator']),
            'urut' => 4,
            'parent_id' => 17,
        ]);

        Menu::create([
            'id' => 20,
            'modul' => 'Core',
            'label' => 'Pencairan',
            'url' => '/persetujuan/pencairanPinjaman',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pencairan',
                '/pencairan*'
            ]),
            'active' => serialize(['bendahara']),
            'urut' => 2,
            'parent_id' => 17,
        ]);

        Menu::create([
            'id' => 21,
            'modul' => 'Core',
            'label' => 'Konfirmasi auto debet',
            'url' => '/angsuran',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/angsuran',
                '/angsuran*'
            ]),
            'active' => serialize(['koordinator']),
            'urut' => 3,
            'parent_id' => 17,
        ]);

                /*
        |--------------------------------------------------------------------------
        | SIMPANAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 22,
            'modul' => 'Core',
            'label' => 'Simpanan',
            'url' => '',
            'icon' => 'fas fa-archive',
            'can' => '',
            'active' => serialize([
                'koordinator',
                'bendahara',
                'anggota'
            ]),
            'urut' => 1,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 23,
            'modul' => 'Core',
            'label' => 'Simpanan Pokok',
            'url' => '/simpanan',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/simpanan',
                '/simpanan*'
            ]),
            'active' => serialize([
                'koordinator',
                'anggota'
            ]),
            'urut' => 1,
            'parent_id' => 22,
        ]);

        Menu::create([
            'id' => 24,
            'modul' => 'Core',
            'label' => 'Simpanan Wajib',
            'url' => '/simpanan-wajib',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/simpanan-wajib',
                '/simpanan-wajib*'
            ]),
            'active' => serialize([
                'koordinator',
                'anggota'
            ]),
            'urut' => 2,
            'parent_id' => 22,
        ]);

        Menu::create([
            'id' => 25,
            'modul' => 'Core',
            'label' => 'Simpanan Sukarela',
            'url' => '/simpanan-sukarela',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/simpanan-sukarela',
                '/simpanan-sukarela*'
            ]),
            'active' => serialize([
                'koordinator',
                'anggota'
            ]),
            'urut' => 3,
            'parent_id' => 22,
        ]);

        Menu::create([
            'id' => 26,
            'modul' => 'Core',
            'label' => 'Penarikan Simpanan Sukarela',
            'url' => '/pencairan-simpanan',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pencairan-simpanan',
                '/pencairan-simpanan*'
            ]),
            'active' => serialize([
                'koordinator',
                'bendahara'
            ]),
            'urut' => 4,
            'parent_id' => 22,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SISA HASIL USAHA
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 27,
            'modul' => 'Core',
            'label' => 'Sisa Hasil Usaha',
            'url' => '',
            'icon' => 'fas fa-chart-pie',
            'can' => '',
            'active' => serialize([
                'koordinator',
                'bendahara',
                'anggota'
            ]),
            'urut' => 2,
            'parent_id' => 0,
        ]);

        Menu::create([
            'id' => 28,
            'modul' => 'Core',
            'label' => 'SHU Koperasi',
            'url' => '/shu-koperasi',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/shu-koperasi',
                '/shu-koperasi*'
            ]),
            'active' => serialize([
                'koordinator'
            ]),
            'urut' => 1,
            'parent_id' => 27,
        ]);

        Menu::create([
            'id' => 29,
            'modul' => 'Core',
            'label' => 'SHU Anggota',
            'url' => '/shu',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/shu',
                '/shu*'
            ]),
            'active' => serialize([
                'koordinator',
                'anggota'
            ]),
            'urut' => 2,
            'parent_id' => 27,
        ]);

        Menu::create([
            'id' => 30,
            'modul' => 'Core',
            'label' => 'Penyaluran SHU',
            'url' => '/pencairan',
            'icon' => 'far fa-circle',
            'can' => serialize([
                '/pencairan',
                '/pencairan*'
            ]),
            'active' => serialize([
                'koordinator',
                'bendahara',
                'anggota'
            ]),
            'urut' => 3,
            'parent_id' => 27,
        ]);


        /*
        |--------------------------------------------------------------------------
        | JADWAL SIMPANAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 31,
            'modul' => 'Core',
            'label' => 'Jadwal Simpanan',
            'url' => '/jadwal-simpanan',
            'icon' => 'far fa-calendar-alt',
            'can' => serialize([
                '/jadwal-simpanan',
                '/jadwal-simpanan*'
            ]),
            'active' => serialize([
                'koordinator'
            ]),
            'urut' => 3,
            'parent_id' => 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PENDAFTARAN
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 32,
            'modul' => 'Core',
            'label' => 'Pendaftaran',
            'url' => '/user',
            'icon' => 'fas fa-child',
            'can' => serialize([
                '/user',
                '/user*'
            ]),
            'active' => serialize([
                'admin'
            ]),
            'urut' => 9,
            'parent_id' => 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | RAT
        |--------------------------------------------------------------------------
        */

        Menu::create([
            'id' => 33,
            'modul' => 'Core',
            'label' => 'RAT',
            'url' => '/rat',
            'icon' => 'far fa-bookmark',
            'can' => serialize([
                '/rat',
                '/rat*'
            ]),
            'active' => serialize([
                'koordinator'
            ]),
            'urut' => 4,
            'parent_id' => 0,
        ]);
    }
}