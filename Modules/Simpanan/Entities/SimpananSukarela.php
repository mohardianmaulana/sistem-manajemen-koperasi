<?php

namespace Modules\Simpanan\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SimpananSukarela extends Model
{
    use HasFactory;

    protected $table = 'simpanan_sukarela';
    protected $fillable = [
        'nilai',
        'periode',
        'id_anggota',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\SimpananSukarelaFactory::new();
    }

    public function anggota()
    {
        return $this->belongsTo(User::class, 'id_anggota');
    }
}
