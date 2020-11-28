<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    //定义管理员-档案的关联一对一 一个管理员有一个档案
    public function profile()
    {
        return $this->hasOne('Profile','uid','id');
//        return $this->hasOne('Profile','uid','id')->bind('idnum');
    }
}
