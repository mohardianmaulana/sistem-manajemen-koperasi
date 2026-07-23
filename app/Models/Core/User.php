<?php

namespace App\Models\Core;

use App\Models\Core\Staff;
use App\Models\Core\Unit;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Modules\SHU\Entities\ShuAnggota;
use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Modules\Simpanan\Entities\MasterSimpananWajib;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

	protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip',
        'email',
        'username',
        'password',
		'unit',
		'staff',
        'status',
		'role_aktif',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_rek',
        'no_hp',
        'file_sk',
        'tanda_tangan',
    ];

	protected static function newFactory()
	{
		return \Database\Factories\UserFactory::new();
	}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
    ];
	
	public function token()
    {
        return $this->hasOne(OauthToken::class);
    }
	
	public function adminlte_image()
	{
		if(!\Storage::exists('/path/to/your/directory')) {
			return asset('/assets/img/avatar.png');
		}else{
			return asset('storage/assets/img/avatar/'.$this->avatar);
		}
		
	}

	public function adminlte_desc()
	{
		return 'That\'s a nice guy';
	}

	public function adminlte_profile_url()
	{
		return 'users/profile';
	}
	
	public function avatar(){
		return 'avatar.jpg';
	}

	public function units()
    {
        return $this->belongsTo(Unit::class, 'unit');
    }
	
     public function getUnit()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }
	
	public function getStaff(){
		return $this->hasOne(Staff::class,'id','staff');
	}
	
	public function hasRoleAktif($roleCheck){
		$rol=$this->roles->pluck('name')->toArray();
		
		if(count($rol)<$this->role_aktif){
			$this->role_aktif=0;
			$this->save();
			return false;
		}
		if($rol[$this->role_aktif]==$roleCheck)return true;
		return false;
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
        return $this->hasMany( MasterSimpananWajib::class, 'id_anggota');
    }

    public function shuAnggota()
    {
        return $this->hasMany(ShuAnggota::class, 'id_anggota');
    }
}
