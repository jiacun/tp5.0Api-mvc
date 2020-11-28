<?php

namespace app\adminapi\controller;

use app\common\model\Admin;
use think\Controller;
use think\Request;


class Role extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询数据 不需要查询超级管理员的
        $list  =\app\common\model\Role::where('id','>',1)->select();
        //对每条角色数据 查询对应的权限 增加role_auths下表数据 父子级的树状结构
        foreach ($list as $k=>$v){
//            $v['role_auth_ids']
            //查询权限表
            $auths = \app\common\model\Auth::where('id','in',$v['role_auth_ids'])->select();
            //转换为二维数组
            $auths  = (new \think\Collection($auths))->toArray();
            //在转换为父子级树状结构
            $auths = get_tree_list($auths);
            //添加
            $list[$k]['role_auths']=$auths;
        }
        unset($v);//特别是$v前面有&时 必须unset

        $this->ok($list);
    }



    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收数据
        $params = input();
        //参数检查
        $validate = $this->validate($params,[
            'role_name|角色名称'=>'require',
            'auth_ids'=>'require'
        ]);
        if($validate !==true){
            $this->fail($validate);
        }
        //添加数据
        $params['role_auth_ids'] =$params['auth_ids'];
        $role = \app\common\model\Role::create($params,true);
        $info = \app\common\model\Role::find($role['id']);
        //返回数据
        $this->ok($info);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询数据
        $info = \app\common\model\Role::field('id,role_name,desc,role_auth_ids')->find($id);
        //返回数据
        $this->ok($info);
    }



    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //接收数据
        $params = input();
        //参数检查
        $validate = $this->validate($params,[
            'role_name|角色名称'=>'require',
            'auth_ids'=>'require'
        ]);
        if($validate !==true){
            $this->fail($validate);
        }
        //修改数据
        $params['role_auth_ids'] =$params['auth_ids'];
         \app\common\model\Role::update($params,['id'=>$id],true);
        $info = \app\common\model\Role::find($id);
        //返回数据
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //超级管理员的角色 可以设置为不能删除
        if($id ==1){
            $this->fail('超级管理员 不允许删除');
        }
        //如果角色下面有管理员 有没有id
        $total = Admin::where('role_id',$id)->count();
        if($total>0){
            $this->fail('角色正在使用中 无法删除');
        }
        //删除数据
        \app\common\model\Role::destroy($id);
        //返回数据
        $this->ok();
    }
}
