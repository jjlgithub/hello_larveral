<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SessionsController extends Controller
{
    //显示登录页面
    public function create()
    {
        return view('sessions.create');
    }

    //创建会话登录,验证
    public function store(Request $request)
    {
        //验证邮箱密码
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials,$request->has('remember'))) {
            // 登录成功后的相关操作
            session()->flash('success', '欢迎回来！');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }
}
