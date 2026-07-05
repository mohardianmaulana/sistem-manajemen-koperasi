<?php

namespace Modules\Simpanan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterJenisSimpanan extends Model
{
    use HasFactory;

    protected $table = 'master_simpanan_sukarela';
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
        return \Modules\Simpanan\Database\factories\MasterSimpananSukarelaFactory::new();
    }
}
