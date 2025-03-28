<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'patronymic',
        'dolznost',
        'telefon',
        'data_rozdenia'
    ];
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
    ];

    protected $appends = ['fullname'];

//    public function getFullnameAttribute()
//    {
//        $parts = array_filter([$this->first_name, $this->patronymic, $this->last_name]);
//        return implode(' ', $parts);
//    }

    public function getFullnameAttribute()
    {
        $initials = '';

        if (!empty($this->first_name)) {
            $initials .= mb_substr($this->first_name, 0, 1) . '.';
        }

        if (!empty($this->patronymic)) {
            $initials .= mb_substr($this->patronymic, 0, 1) . '.';
        }

        // Собираем результат
        return trim(trim($this->last_name) . ($initials ? ' ' . $initials : ''));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
       // return $this->hasMany(Role::class,'users_roles');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'users_permissions');
    }

    public function hasRole( $roles ) {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermission($permission)
    {
        return (bool) $this->permissions->where('slug', $permission)->count();
    }
    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        return $this->hasPermission($permission);
    }
    public function getUserByIdInModel($id)
    {
        return User::where('id', $id)->get();
    }
    public function getAdminsId()
    {
       return User::whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->get();    }

    public function getUserStatusName()
    {
        $user=User::find(Auth::id())->roles;
        return $user[0]['slug'];
    }

}
