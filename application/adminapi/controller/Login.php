<?php

namespace app\adminapi\controller;

use app\common\model\Admin;
use think\Controller;
use tools\jwt\Token;

class Login extends Base
{
    /**
     * 验证码接口
     */
    public function  captcha()
    {
        //生成验证码唯一标识
        $uniqid = uniqid(mt_rand(100000,999999));
        //生成验证码地址
        $src = captcha_src($uniqid);
        //返回数据
        $res = [
            'src'=>$src,
            'uniqid'=>$uniqid
        ];
        $this->ok($res);
    }

    /**
     * 登录接口
     */
    public function login()
    {
        //接收参数
        $params  = input();
        //参数检查表单验证
        $validate = $this->validate($params,[
            'username|用户名'=>'require',
            'password|密码'=>'require',
            'code|验证码'=>'require',
//            'code|验证码'=>'require|captcha',//验证码自动校验
            'uniqid|验证码标识'=>'require'
        ]);
        if($validate !==true){
            //参数验证失败
            $this->fail($validate,401);
        }
        //校验验证码 手动校验
        //从缓存中根据uniqid获取session_id 设置session_id 用于校验
        $session_id =cache('session_id_'.$params['uniqid']);
        if($session_id){
            session_id($session_id);
        }

        if(!captcha_check($params['code'],$params['uniqid']))
        {
            //验证码错误  暂时关闭
//            $this->fail('验证码错误',402);
        }
        //查询用户表认证
        $password = encrypt_password($params['password']);
        $info = Admin::where('username',$params['username'])->where('password',$password)->find();
        if(empty($info)){
            $this->fail('用户名或者密码错误',403);
        }
        //生成token令牌
        $token = Token::getToken($info['id']);
        //返回数据
        $data=[
            'token'=>$token,
            'user_id'=>$info['id'],
            'username'=>$info['username'],
            'nickname'=>$info['nickname'],
            'email'=>$info['email']
        ];
        $this->ok($data);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        //记录token为退出
        //获取传过来的token
        $token = Token::getRequestToken();
        //从缓存中取出 注销的token数组
        $delete_token = cache('delete_token') ?: [];
        //将当前token加入到数组中
        $delete_token[]=$token;
        //新的数组重新存储到缓存中 缓存一天
        cache('delete_token',$delete_token,86400);
        //返回数据
        $this->ok();
    }
}
