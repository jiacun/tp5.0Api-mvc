<?php

namespace app\adminapi\controller;

use app\adminapi\logic\AuthLogic;
use app\common\model\Profile;
use think\Controller;
use think\Exception;
use tools\jwt\Token;

class Base extends Controller
{
    //不需要登录的请求数组
    protected  $no_login=['login/captcha','login/login'];
    //初始化方法
    public function _initialize()
    {
      //  $info =\app\common\model\Admin::with('profile')->find(1);
     //  $this->ok($info->profile->idnum);
     //   die;
        // 档案为主 档案到管理员
        $info = Profile::with('admin')->find(1);
        $this->ok($info);

        die;
        parent::_initialize();
        //允许的源域名
        header("Access-Control-Allow-Origin: *");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');


        try {
            //登录检查
            //获取当前请求控制器方法名称
            $path =strtolower( $this->request->controller()).'/'.$this->request->action();
            //判断是否在不需要请求数组中
            if(!in_array($path,$this->no_login)){
                //不在里面的需要做登录检测
//                $user_id =Token::getUserId();//暂时注释
                $user_id =1;//方便测试用
                if(empty($user_id)){
                    $this->fail('token验证失败',403);
                }
                //权限检测
//                 $this->auth_chek();
                 $auth_chck =  AuthLogic::check();
                if(!$auth_chck){
                    $this->fail('没有权限访问',402);
                }
                //将得到的用户id放到请求的信息中 方便后续使用
                $this->request->get('user_id',$user_id);
                $this->request->post('user_id',$user_id);
            }
        }catch (Exception $e){
            //解析token失败
            $this->fail('token解析失败',404);
        }

    }

    /**
     * 通用响应
     * @param int $code 错误码
     * @param string $msg 错误描述
     * @param array $data 返回数据
     */
    public function response($code=200, $msg='success', $data=[])
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        //以下两行二选一
        //echo json_encode($res, JSON_UNESCAPED_UNICODE);die;
        json($res)->send();die;
    }
    /**
     * 失败时响应
     * @param string $msg 错误描述
     * @param int $code 错误码
     */
    public function fail($msg='fail',$code=500)
    {
        return $this->response($code, $msg);
    }

    /**
     * 成功时响应
     * @param array $data 返回数据
     * @param int $code 错误码
     * @param string $msg 错误描述
     */
    public function ok($data=[], $code=200, $msg='success')
    {
        return $this->response($code, $msg, $data);
    }


}
