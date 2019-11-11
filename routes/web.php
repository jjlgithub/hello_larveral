<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'StaticPagesController@home')->name('home');//首页
Route::get('/help','StaticPagesController@help')->name('help');//帮助
Route::get('/about','StaticPagesController@about')->name('about');//关于我们

Route::get('signup','UsersController@create')->name('signup');//注册页面
Route::resource('users', 'UsersController');//注册方法
//会话
Route::get('login', 'SessionsController@create')->name('login');//显示登录页面
Route::post('login', 'SessionsController@store')->name('login');//创建新会话（登录）
Route::delete('logout', 'SessionsController@destroy')->name('logout');//销毁会话（退出登录）