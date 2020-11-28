<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use \think\Route;

//后台接口域名路由 adminapi

Route::domain('adminapi',function (){
    //首页路由
    Route::get('/','adminapi/index/index');
    //定义 域名下的其他路由
    //比如以后定义路由 http://adminapi.pyg.cn/goods  get请求  访问到adminapi下面的goods控制器的index方法comm
//    Route::resoute('goods','adminapi/goods');
    //验证码图片
    Route::get('captcha/:id', "\\think\\captcha\\CaptchaController@index");//访问图片需要
    Route::get('captcha','adminapi/login/captcha');
    //登录接口
    Route::post('login','adminapi/login/login');
    //退出登录
    Route::get('logout','adminapi/login/logout');
    //权限接口
    Route::resource('auths','adminapi/auth',[],['id'=>'\d+']);
    //查询菜单权限的接口
    Route::get('nav','adminapi/auth/nav');
    //角色路由
    Route::resource('roles','adminapi/role',[],['id'=>'\d+']);
    //管理员路由
    Route::resource('admins','adminapi/admin',[],['id'=>'\d+']);
    //商品分类路由
    Route::resource('categorys','adminapi/category',[],['id'=>'\d+']);
    //单图片上传接口
    Route::post('logo','adminapi/upload/logo');
    //多图片上传接口
    Route::post('images','adminapi/upload/images');
    //商品品牌路由
    Route::resource('brands','adminapi/brands',[],['id'=>'\d+']);

});