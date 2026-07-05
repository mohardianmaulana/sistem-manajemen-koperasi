<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'id_role',
        'nip',
        'username',
        'password',
        'id_unit',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\AnggotaFactory::new();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function simpananPokok()
    {
        return $this->hasMany(SimpananPokok::class, 'id_anggota');
    }

    public function simpananSukarela()
    {
        return $this->hasMany(SimpananSukarela::class, 'id_anggota');
    }

    public function masterSimpananSukarela()
    {
        return $this->hasMany(MasterSimpananSukarela::class, 'id_anggota');
    }

    public function simpananWajib()
    {
        return $this->hasMany(SimpananWajib::class, 'id_anggota');
    }

    public function masterSimpananWajib()
    {
        return $this->hasMany( MasterSimpananSukarela::class, 'id_anggota');
    }
}
