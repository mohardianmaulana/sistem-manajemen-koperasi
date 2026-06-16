<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = ['nama'];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\RoleFactory::new();
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_role');
    }
}
