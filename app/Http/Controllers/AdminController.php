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

    public function change_permission(Request $request)
    {
        $user_id =  $request->input('user_id');
        $user_perm =  $request->input('user_perm');

        $perm = Role::where('slug',$user_perm)->get();
        $list_users = User::where('id', $user_id)->get();
//        $list_users[0]->roles()->sync(
//            [1,3]
//        );
        $list_users[0]->roles()->attach(
            [2]
        );

    }

    public function add_user_view(Request $request)
    {
        return view('admin.add_user');
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

         User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return back()->with('success','Новый пользователь успешно добавлен!');
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
            //если у юзера не будет статуса по умолчанию то добавить проверку
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
