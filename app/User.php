<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public static $statusLabels = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_DELETED => 'Eliminado',
    ];
    
    const ROLE_ADMIN = 1;
    const ROLE_AFFILIATE = 2;
    public static $roleLabels = [
        self::ROLE_ADMIN => 'Administrador',
        self::ROLE_AFFILIATE => 'Afiliado',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','affiliateId','role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function affiliate()
    {
        return $this->hasOne('App\Affiliate', 'id', 'affiliateId');
    }
}
