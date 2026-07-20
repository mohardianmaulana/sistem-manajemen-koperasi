<?php

namespace Modules\Simpanan\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(User::class, 'id_anggota');
    }
}
