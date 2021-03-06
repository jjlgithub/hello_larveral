<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store','index']
        ]);//except 方法来设定 指定动作 不使用 Auth 中间件进行过滤
        $this->middleware('guest', [
            'only' => ['create']
        ]);//只让未登录用户访问注册页面：
    }
    //用户列表
    public function index()
    {
        $users = User::paginate(10);;//获取所有用户
        return view('users.index',compact('users'));
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
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    //用户编辑
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    
    //编辑操作
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'nullable|confirmed|min:6'
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

    //删除
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
