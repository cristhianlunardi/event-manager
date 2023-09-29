<?php

namespace App\Models;

use App\Notifications\MailResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_number', 'rif', 'use_type', 'email', 'password', 'full_name', 'birthdate', 'dependency', 'role', 'isActive'];
    protected $dates = ['birthdate'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dateFormat = 'd/m/Y';

    public function setPasswordAttribute($password)
    {
        if (Hash::needsRehash($password)) {
            $password = Hash::make($password);
        }

        $this->attributes['password'] = $password;
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    public function getEmailAttribute($email): string
    {
        return mb_strtolower($email);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    function hasPermission($permission_string): bool
    {
        $permissions = Role::getRolesPermissions($this);

        if ($permissions)
        {
            if (in_array($permission_string, $permissions))
            {
                return True;
            }
        }

        return False;
    }
}
