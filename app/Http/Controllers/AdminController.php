<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AdminController extends Controller
{

    private $userService;
    public function __construct(
        UserService $userService,
    )
    {
        $this->userService = $userService;
    }

    public function users_list(Request $request)
    {
        return view('admin.users_list');
    }
    public function get_roles()
    {
        $roles_list = Role::all();
        return response()->json([
            'status' => 'success',
            'roles_list' => $roles_list,
        ], 201);

    }
    public function is_admin_settings()
    {
        if (auth()->user()->roles[0]->id == '1') {
            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }
    public function change_permission(Request $request)
    {
        $user_id =  $request->input('user_id');
        $user_perm =  $request->input('user_perm');

        $perm = Role::where('slug',$user_perm)->get();
        $list_users = User::where('id', $user_id)->get();
        $list_users[0]->roles()->sync(
            [$perm[0]['id']]
        );

    }


    public function add_user(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $patronymic = $request->input('patronymic');
        $dolznost = $request->input('dolznost');
        $telefon = $request->input('telefon');
        $role = $request->input('role');
        $data_rozdenia = $request->input('data_rozdenia');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'first_name' => [ 'max:255'],
            'last_name' => [ 'max:255'],
            'patronymic' => ['max:255'],
            'dolznost' => ['max:255'],
            'telefon' => ['max:255'],
            'data_rozdenia' => ['max:255'],

        ]);
        $password=Str::random(10);
        $pass=$password;
        $log=$email;
        Mail::to($email)->send(new RegisterMail($pass,$log));

        $user= User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'patronymic' => $patronymic,
            'dolznost' => $dolznost,
            'telefon' => $telefon,
            'data_rozdenia' => $data_rozdenia,

        ]);

        $user->roles()->attach([$role]);
        return response()->json([
            'status' => 'success',
        ], 201);
    }
    public function get_users_list(Request $request)
    {
        $offset =  $request->input('offset');
        $sort_by_item =  $request->input('sort_by_item');
        $asc_desc =  $request->input('asc_desc');

        if($sort_by_item=='')
        {
            $sort=false;
        }
        else
        {
            $sort=true;
        }

        //если сортировка по статусу(админ менеджер логист)
        if($sort_by_item=='status')
        {
            $list_users=User::orderBy(UserRole::select('role_id')
                ->whereColumn('user_id', 'users.id'),$asc_desc)
                ->offset($offset)
                ->limit(12)
                ->get();
        }
        //сортировка по всем остальным столбцам
        else
        {
            $list_users = User::where('id', '>', '0')
                ->offset($offset)
                ->limit(12)
                ->when($sort, function($q)use ($sort_by_item,$asc_desc){
                    return $q->orderBy($sort_by_item,$asc_desc);
                })
                ->get();
        }

        foreach ($list_users as $key=>$one_user)
        {
            $perm=$one_user->roles()->get();
            if ($perm->isEmpty()) {
                $one_user->roles()->attach(
                    [2]
                );
            }
        }

        foreach ($list_users as $key=>$one_user)
        {
            $perm=$one_user->roles()->get();
            $one_user->perm=$perm[0]['slug'];
        }

        $count = User::where('id', '>', '0')
            ->count();
        return response()->json([
            'status' => 'success',
            'list_users' => $list_users,
            'tipes_count' => $count,
            'asc_desc' => $asc_desc,
        ], 201);
    }
    public function getRoleUser()
    {
         $user=$this->userService->getRole();
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 201);
    }

}
