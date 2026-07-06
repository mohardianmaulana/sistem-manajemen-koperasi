<?php

namespace Modules\Simpanan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterJenisSimpanan extends Model
{
    use HasFactory;

    protected $table = 'master_jenis_simpanan';
    protected $fillable = [
        'nama_jenis_simpanan',
        'tanggal_mulai',
        'tanggal_berakhir',

    ];
    
    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\MasterSimpananSukarelaFactory::new();
    }
}
