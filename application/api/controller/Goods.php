<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 11:54
 */

namespace app\api\controller;

use app\api\model\BoxGoods;
use app\api\model\BoxMember;
use think\Controller;
use think\Request;

class Goods extends Controller
{

    public function goods_list(Request $request)
    {
        $goods = new BoxGoods();
        $goods_list['goods_list'] = $goods->field('goods_id,goods_imgs,true_price,conversion_price')->where(['status' => 1])->where('inventory', '>', 0)->select();

        return response_data(1, '成功', $goods_list);
    }

    public function goods_detail(Request $request)
    {
        $goods_id = $request->param('goods_id');
        $member_id = $request->param('member_id');
        $data['goods'] = (new BoxGoods())->field('goods_id,conversion_price,inventory')->where('goods_id', $goods_id)->find();
        $data['gold_num'] = (new BoxMember())->field('gold_num')->where('member_id',$member_id)->find();
        return response_data(1, '成功', $data);
    }
}