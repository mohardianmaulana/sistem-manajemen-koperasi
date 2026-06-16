<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Tests\TestCase;

class SkemaPinjamanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_create_skema_pinjaman_sukses()
    {
        $response = $this->post("skema_pinjaman/store", 
            [
                'nama' => 'Bunga Rendah',
                'min_nominal' => 1000000,
                'max_nominal' => 5000000,
                'min_tenor' => 10,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('skema_pinjaman', 
        [
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
    }

    public function test_create_skema_pinjaman_gagal_nama_skema_duplikat()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);

        $response = $this->post("skema_pinjaman/store", 
            [
                'nama' => 'Bunga Rendah',
                'min_nominal' => 1500000,
                'max_nominal' => 6500000,
                'min_tenor' => 15,
                'max_tenor' => 30,
                'bunga' => 2,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('nama');

        $this->assertDatabaseCount('skema_pinjaman', 1);
    }

    public function test_create_skema_pinjaman_gagal_min_max_nominal_dan_tenor_dan_bunga_kurang_dari_0()
    {
        $response = $this->post("skema_pinjaman/store", 
            [
                'nama' => 'Bunga Rendah',
                'min_nominal' => -1,
                'max_nominal' => -1,
                'min_tenor' => -1,
                'max_tenor' => -1,
                'bunga' => -1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors([
            'min_nominal',
            'max_nominal',
            'min_tenor',
            'max_tenor',
            'bunga',
        ]);

        $this->assertDatabaseCount('skema_pinjaman', 0);
    }

    public function test_create_skema_pinjaman_gagal_min_nominal_lebih_besar_dari_max_nominal()
    {
        $response = $this->post("skema_pinjaman/store", 
            [
                'nama' => 'Bunga Rendah',
                'min_nominal' => 600000,
                'max_nominal' => 500000,
                'min_tenor' => 10,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('max_nominal');

        $this->assertDatabaseCount('skema_pinjaman', 0);
    }

    public function test_create_skema_pinjaman_gagal_min_tenor_lebih_besar_dari_max_tenor()
    {
        $response = $this->post("skema_pinjaman/store", 
            [
                'nama' => 'Bunga Rendah',
                'min_nominal' => 100000,
                'max_nominal' => 500000,
                'min_tenor' => 30,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('max_tenor');

        $this->assertDatabaseCount('skema_pinjaman', 0);
    }

    public function test_update_skema_pinjaman_sukses()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
        $response = $this->put("skema_pinjaman/update/{$skema_pinjaman->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => 900000,
                'max_nominal' => 4000000,
                'min_tenor' => 8,
                'max_tenor' => 15,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('skema_pinjaman', 
        [
            'id' => $skema_pinjaman->id,
            'nama' => 'Bunga Sedikit Rendah',
            'min_nominal' => 900000,
            'max_nominal' => 4000000,
            'min_tenor' => 8,
            'max_tenor' => 15,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
            'status' => 'aktif',
        ]);
    }

    public function test_update_skema_pinjaman_gagal_nama_skema_duplikat()
    {
        $skema_pinjaman1 = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
        $skema_pinjaman2 = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Sedikit Rendah',
            'min_nominal' => 500000,
            'max_nominal' => 2500000,
            'min_tenor' => 8,
            'max_tenor' => 15,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
            'status' => 'aktif',
        ]);
        $response = $this->put("skema_pinjaman/update/{$skema_pinjaman1->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => 900000,
                'max_nominal' => 400000,
                'min_tenor' => 8,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('nama');

        // jumlah data tetap
        $this->assertDatabaseCount('skema_pinjaman', 2);

        // data lama tidak berubah
        $this->assertDatabaseHas('skema_pinjaman', [
            'id' => $skema_pinjaman1->id,
            'nama' => 'Bunga Rendah',
        ]);
    }

    public function test_update_skema_pinjaman_gagal_min_max_nominal_dan_tenor_dan_bunga_kurang_dari_0()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
        $response = $this->put("skema_pinjaman/update/{$skema_pinjaman->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => -1,
                'max_nominal' => -1,
                'min_tenor' => -1,
                'max_tenor' => -1,
                'bunga' => -1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors([
            'min_nominal',
            'max_nominal',
            'min_tenor',
            'max_tenor',
            'bunga',
        ]);

        // jumlah data tetap
        $this->assertDatabaseCount('skema_pinjaman', 1);

        // data lama tidak berubah
        $this->assertDatabaseHas('skema_pinjaman', [
            'id' => $skema_pinjaman->id,
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
        ]);
    }

    public function test_update_skema_pinjaman_gagal_min_nominal_lebih_besar_dari_max_nominal()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
        $response = $this->put("skema_pinjaman/update/{$skema_pinjaman->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => 6000000,
                'max_nominal' => 5000000,
                'min_tenor' => 10,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('max_nominal');

        // jumlah data tetap
        $this->assertDatabaseCount('skema_pinjaman', 1);

        // data lama tidak berubah
        $this->assertDatabaseHas('skema_pinjaman', [
            'id' => $skema_pinjaman->id,
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
        ]);
    }

    public function test_update_skema_pinjaman_gagal_min_tenor_lebih_besar_dari_max_tenor()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga rendah',
            'status' => 'aktif',
        ]);
        $response = $this->put("skema_pinjaman/update/{$skema_pinjaman->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => 1000000,
                'max_nominal' => 4000000,
                'min_tenor' => 30,
                'max_tenor' => 20,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertSessionHasErrors('max_tenor');

        // jumlah data tetap
        $this->assertDatabaseCount('skema_pinjaman', 1);

        // data lama tidak berubah
        $this->assertDatabaseHas('skema_pinjaman', [
            'id' => $skema_pinjaman->id,
            'nama' => 'Bunga Rendah',
            'min_nominal' => 1000000,
            'max_nominal' => 5000000,
            'min_tenor' => 10,
            'max_tenor' => 20,
            'bunga' => 1,
        ]);
    }

    public function test_delete_skema_pinjaman()
    {
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'nama' => 'Bunga Sedikit Rendah',
            'min_nominal' => 900000,
            'max_nominal' => 4000000,
            'min_tenor' => 18,
            'max_tenor' => 15,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
            'status' => 'aktif',
        ]);
        $response = $this->delete("skema_pinjaman/delete/{$skema_pinjaman->id}", 
            [
                'nama' => 'Bunga Sedikit Rendah',
                'min_nominal' => 900000,
                'max_nominal' => 4000000,
                'min_tenor' => 18,
                'max_tenor' => 15,
                'bunga' => 1,
                'jaminan' => 'tidak',
                'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
                'status' => 'aktif',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseMissing('skema_pinjaman', 
        [
            'id' => $skema_pinjaman->id,
            'nama' => 'Bunga Sedikit Rendah',
            'min_nominal' => 900000,
            'max_nominal' => 4000000,
            'min_tenor' => 18,
            'max_tenor' => 15,
            'bunga' => 1,
            'jaminan' => 'tidak',
            'deskripsi' => 'Ini pinjaman bunga sedikit rendah',
            'status' => 'aktif',
        ]);
    }
}
