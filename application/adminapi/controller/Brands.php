<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Brands extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数 cate_id  keyword page
        $params = input();
        $where=[];
        if(isset($params['cate_id']) && !empty($params['cate_id'])){
            //分类下的品牌列表
            $where['cate_id']=$params['cate_id'];
            //查询数据
            //原始squel语句  select t1.*,t2.cate_name from pyg_brand t1 left join pyg_category t2 on t1.cate_id = t2.id where cate_id =72;
            $list = \app\common\model\Brand::where($where)->field('id,name')->select();
//            $list = \app\common\model\Brands::alias('t1')
//                ->join('pyg_category t2','t1.cate_id=t2.id','left')
//                ->field('t1.*,t2.cate_name')
//                ->where($where)
//                ->select();
        }else{
            //分类+加搜索
            if(isset($params['keyword'])&& !empty($params['keyword'])){
                $keyword = $params['keyword'];
                $where['t1.name']=['like',"%$keyword%"];
            }
            //分页查询数据 alias('t1')是给表起别名
            //原始squel语句 select t1.*,t2.cate_name from pyg_brand t1 left join pyg_category t2 on t1.cate_id = t2.id where name like '%亚%' limit 0,10;
//            $list=\app\common\model\Brands::where($where)->paginate(10);
            $list = \app\common\model\Brand::alias('t1')
                ->join('pyg_category t2','t1.cate_id=t2.id','left')
                ->field('t1.*,t2.cate_name')
                ->where($where)
                ->paginate(10);
        }
        $this->ok($list);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
