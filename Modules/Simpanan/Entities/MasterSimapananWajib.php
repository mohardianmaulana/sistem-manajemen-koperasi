<?php

namespace Modules\Simpanan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pinjaman\Entities\Anggota;

class MasterSimapananWajib extends Model
{
    use HasFactory;

    protected $table = 'master_simpanan_wajib';
    protected $fillable = [
        'nilai',
        'periode',
        'tahun',
        'bukti',
        'status',
        'id_anggota',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\MasterSimpananWajibFactory::new();
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
