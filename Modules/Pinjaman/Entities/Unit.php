<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'unit';

    protected $fillable = ['nama'];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\UnitFactory::new();
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_unit');
    }
}
