<?php

namespace app\adminapi\controller;

use think\Controller;

class Upload extends Base
{
    //单图片上传
    public  function logo()
    {
        //接收参数
        $type = input('type');
        if(empty($type)){
            $this->fail('缺少参数');
        }
        //获取文件
        $file = request()->file('logo');
        if(empty($file)){
            $this->fail('必须上传文件');
        }
        //图片移动 /publlic/uploads/goods/  /publlic/uploads/category  /publlic/uploads/brand
        $info =  $file->validate(['size'=>10*1024*1024,'ext'=>'jpg,gif,png,jpeg'])->move(ROOT_PATH.'public'.DS.'uploads'.DS.$type);
        if($info){
            //返回图片路径
            $logo =DS.'uploads'.DS.$type.DS.$info->getSaveName();
            $this->ok($logo);
        }else{
            //报错
            $msg = $file->getError();
            $this->fail($msg);
        }
        //返回图片路径
    }

    /**
     * 多图片上传
     */
    public function images(){
        //接收type参数
        $type = input('type','goods');
        //获取上传文件 数组
        $files = request()->file('images');
        //遍历数组 逐个上传
        $data = ['success'=>[],'error'=>[]];
        foreach ($files as $file){
            //移动文件到指定位置 /public/uploads/goods 目录下
            $dir = ROOT_PATH.'public'.DS.'uploads'.DS.$type;
            if(is_dir($dir)){
                mk_dir($dir);
            }
            $info = $file->validate(['size'=>10*1024*1024,'ext'=>'jpg,gif,png,jpeg'])->move($dir);
            if($info){
                //成功返回图片路径
                $path = DS.'uploads'.DS.$type.DS.$info->$info->getSaveName();
                $data['success'][]=$path;
            }else{
                //失败获取错误 $files->getInfo获取文件原始信息
                $data['error'][]=[
                    'name'=>$files->getInfo('name'),
                    'msg'=>$file->getError()
                ];
            }
        }
        $this->ok($data);
    }
}
