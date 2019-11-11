<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store']
        ]);//except 方法来设定 指定动作 不使用 Auth 中间件进行过滤
    }
    //注册页面
    public function create()
    {
        return view('users.create');
    }

    //展示用户
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    //用户注册逻辑
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    //用户编辑
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    
    //编辑操作
    public function update(User $user, Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'required|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        //如果有密码就去更新
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
//        $user->update([
//            'name'=>$request->name,
//            'password'=>bcrypt($request->password)
//        ]);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }
}
