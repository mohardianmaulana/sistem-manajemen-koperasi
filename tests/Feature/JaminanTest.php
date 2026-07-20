<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pinjaman\Entities\Jaminan;
use Tests\TestCase;

class JaminanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_create_jaminan_sukses()
    {
        $response = $this->post("jaminan/store", 
            [
                'nama' => 'Surat tanah',
                'deskripsi' => 'Ini surat tanah',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'nama' => 'Surat tanah',
            'deskripsi' => 'Ini surat tanah',
        ]);
    }

    public function test_create_jaminan_gagal_nama_atau_deskripsi_kosong()
    {
        $response = $this->post("jaminan/store", 
            [
                'nama' => null,
                'deskripsi' => null,
            ]
        );

        $response->assertSessionHasErrors('nama', 'deskripsi');

        $this->assertDatabaseCount('jaminan', 0);
    }

    public function test_update_jaminan_sukses()
    {
        $jaminan = Jaminan::factory()->create();
        $response = $this->put("jaminan/update/{$jaminan->id}", 
            [
                'nama' => 'Sertifikat rumah',
                'deskripsi' => 'Ini sertifikat rumah',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'id' => $jaminan->id,
            'nama' => 'Sertifikat rumah',
        ]);
    }

    public function test_update_jaminan_gagal_nama_atau_deskripsi_kosong()
    {
        $jaminan = Jaminan::factory()->create();
        $response = $this->put("jaminan/update/{$jaminan->id}", 
            [
                'nama' => null,
                'deskripsi' => null,
            ]
        );

        $response->assertSessionHasErrors('nama', 'deskripsi');

        $this->assertDatabaseHas('jaminan', 
        [
            'nama' => 'Surat tanah',
            'deskripsi' => 'Ini surat tanah',
        ]);
    }
}
