<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Page;
/**
 * 行为控制器
 * @author huajie <banhuajie@163.com>
 */
class DistributionController extends AdminController {
    //红包三级分销
    public function index() {
        $bonus = M("bonus_distribution");
        $where = ['key'=>'deduct'];
        $list = $bonus ->where($where)-> find();

        //添加、修改地址
        if (IS_AJAX) {

            $data = json_encode(I('data'));
            $back = $bonus->where($where)->save(['data'=>$data]);
            if ($back===false){
                $this->error('保存失败');
            }

            $this->success('保存成功');

            exit();

        }

        $data = json_decode($list['data'],true);
        $this -> assign("data", $data);
        $this -> display();
    }

    /**
     * 管理津贴
     */
    function subsidy(){

        $bonus = M("bonus_distribution");

        $where = ['key'=>'subsidy'];

        $list = $bonus ->where($where)-> find();

        //添加、修改地址
        if (IS_AJAX) {

            $data = (I('data'));

            for ($i=0;$i<count($data);$i++){

                for ($a=0;$a<count($data);$a++){

                    if ($data[$a]['numpeople'] > $data[$a+1]['numpeople'] && $a+1<count($data)){

                        $v = $data[$a];

                        $data[$a] = $data[$a+1];

                        $data[$a+1] = $v;

                    }
                }

            }

            $back = $bonus->where($where)->save(['data'=>json_encode($data)]);
            if ($back===false){
                $this->error('保存失败');
            }

            $this->success('保存成功');

            exit();

        }

        $data = json_decode($list['data'],true);


        $this -> assign("data", $data);

        $this -> display();

    }

    //点击删除人民币收款地址
    public function delete() {
        $id = I("id");

        if (M("bonus_distribution") -> where("id = ". $id) -> delete()) {
            $this -> success("删除成功");
        } else {
            $this -> error("删除失败");
        }
    }

    //修改
    public function edit() {
        $id = $this -> strFilter(I("id")) ? $this -> strFilter(I("id")) : "";
        //查询要修改的地址信息
        if ($id != "") {
            $bankedit = M("bonus_distribution") -> where(array("id" => $id)) -> field("id, name, numpeople, percentage") -> find();
            echo json_encode($bankedit);
        }
    }

    public function paper(){

        $repeat_cfg_m = M('repeat_cfg');

        $where = ['key'=>'date_back'];

        if (IS_AJAX) {

            $data = (I('data'));

            for ($i=0;$i<count($data);$i++){

                for ($a=0;$a<count($data);$a++){

                    if ($data[$a]['numpeople'] > $data[$a+1]['numpeople'] && $a+1<count($data)){

                        $v = $data[$a];

                        $data[$a] = $data[$a+1];

                        $data[$a+1] = $v;

                    }
                }

            }

            $back = $repeat_cfg_m->where($where)->save(['data'=>json_encode($data)]);
            if ($back===false){
                $this->error('保存失败');
            }

            $this->success('保存成功');

            exit();

        }

        $data = $repeat_cfg_m->where($where)->find();

        $this->assign('data',json_decode($data['data'],true));

        $this -> display();
    }

}
