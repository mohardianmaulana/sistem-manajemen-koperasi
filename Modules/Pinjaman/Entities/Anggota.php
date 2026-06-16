<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
