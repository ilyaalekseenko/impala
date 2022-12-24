<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function users_list(Request $request)
    {
        return view('admin.users_list');
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
        $password=Str::random(10);
        $pass=$password;
        $log=$email;
        Mail::to($email)->send(new RegisterMail($pass,$log));

        $user= User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->roles()->attach([3]);
        return response()->json([
            'status' => 'success',
        ], 201);
    }
    public function get_users_list(Request $request)
    {
        $offset =  $request->input('offset');

        $list_users = User::where('id', '>', '0')
            ->offset($offset)
            ->limit(12)
            ->get();
        foreach ($list_users as $key=>$one_user)
        {
            $perm=$one_user->roles()->get();
            if ($perm->isEmpty()) {
                $one_user->roles()->attach(
                    [2]
                );
            }
        }
        $list_users = User::where('id', '>', '0')
            ->offset($offset)
            ->limit(12)
            ->get();
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
        ], 201);
    }

}
