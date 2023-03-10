<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $role;
    protected $user;
    public function __construct(Role $role, User $user)
    {
        $this->user = $user;
        $this->role = $role;
    }

    //получаем список ролей
    public function getRole()
    {
        $user = User::find(Auth::id());

        $roles=$this->role->getRoles($user->roles[0]['id']);
        $permissions_arr=[];
        foreach ($roles->permissions as $permission) {
            array_push($permissions_arr, $permission['id']);
        }
        $role_perm=['role'=>$user->roles[0]['id'],'permissions'=>$permissions_arr];
        $user['role_perm']=$role_perm;
        return $user;
    }
    public function getUserById($id)
    {
        return $this->user->getUserByIdInModel($id);
    }
    public function setNameToUser($log_name)
    {
        if (!$log_name->isEmpty())
        {
            return $log_name[0]['last_name'].' '.$log_name[0]['first_name'].' '.$log_name[0]['patronymic'];
        }
        else
        {
            return 'Логист не выбран';
        }
    }
    public function getAllAdminsId()
    {
        return $this->user->getAdminsId();
    }
}
