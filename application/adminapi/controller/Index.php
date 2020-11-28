<?php
namespace app\adminapi\controller;

use think\Db;
use tools\jwt\Token;

class Index extends Base
{
    public function index()
    {
        echo encrypt_password(123456);die;
//     return $this->response();
        //测试token
        $token = Token::getToken(100);
        dump($token);

        //解析token
        $user_id= Token::getUserId($token);
        dump($user_id);
    }

}
