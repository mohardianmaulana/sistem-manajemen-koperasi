<?php

namespace Modules\Simpanan\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimpananPokok extends Model
{
    use HasFactory;
    protected $table = 'tabungan';
    protected $fillable = [
        'nilai',
        'tanggal',
        'bukti',
        'status',
        'id_anggota',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\TabunganFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_anggota');
    }
}
