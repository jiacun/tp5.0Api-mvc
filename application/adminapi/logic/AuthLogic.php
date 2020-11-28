<?php
namespace app\adminapi\logic;


use app\common\model\Admin;
use app\common\model\Auth;
use app\common\model\Role;

class AuthLogic{
    public static function check()
    {
        //判断是否是特殊页面 首页不需要
        $controller = request()->controller();//获取控制器方法返回首字母大小的
        $action = request()->action();//获取操作名称
        if($controller =='Index' && $action=='index'){
            //不需要检测 可以访问
            return true;
        }
        //获取到管理员的角色id
        $user_id = input('user_id');
        $info = Admin::find($user_id);
        $role_id=$info['role_id'];
        //判断是否是超级管理员 不需要检测
        if($role_id==1){
            //不需要检测 有权限
            return true;
        }

        //查询当前管理员所有的权限ids 从角色吧查询出role_auth_ids
        $role = Role::find($role_id);
        //取出权限ids分割为数组
        $role_auth_ids =explode(',',$role['role_auth_ids']);
        //根据当前访问的控制器 方法查询到具体的权限id
        $auth =Auth::where('auth_c',$controller)->where('auth_a',$action)->find();
        $auth_id =$auth['id'];
        //判断当前权限id是否在role_auth_ids范围中
        if(in_array($auth_id,$role_auth_ids)){
            //有权限
            return true;
        }else{
            //没有权限访问
            return false;
        }
    }
}