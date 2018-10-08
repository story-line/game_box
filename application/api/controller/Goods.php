<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 11:54
 */

namespace app\api\controller;

use app\api\model\BoxGoods;
use app\api\model\BoxGoodsImgs;
use app\api\model\BoxGoodsMarque;
use app\api\model\BoxMember;
use app\api\model\BoxOrder;
use think\Controller;
use think\Request;

class Goods extends Controller
{

    public function goods_list(Request $request)
    {
        if (!$request->isPost()) {
            return response_error('请求方式错误');
        }
        $goods = new BoxGoods();
        $goods_list['goods_list'] = $goods->field('goods_id,goods_name,true_price,conversion_price')
            ->where(['status' => 1])->where('inventory', '>', 0)->where('begin_time', '<=', date('Y-m-d H:i:s'))
            ->where('end_time', '>=', date('Y-m-d H:i:s'))->select();
        foreach ($goods_list['goods_list'] as $key => $value) {
            $goods_list['goods_list'][$key]['goods_imgs'] = (new BoxGoodsImgs())->field('goods_imgs')->where('goods_id', $value['goods_id'])
                ->where('status', 1)->order('img_weight', 'desc')->find();
        }

        return response_data(1, '成功', $goods_list);
    }

    public function goods_detail(Request $request)
    {
        if (!$request->isPost()) {
            return response_error('请求方式错误');
        }
        $goods_id = $request->param('goods_id');
        $member_id = $request->param('member_id');
        $data['goods'] = (new BoxGoods())->field('goods_id,goods_name,conversion_price,inventory,
        begin_time,end_time,one_day_conversion,member_conversion,merchandise_type')->find($goods_id);
        $data['goods']['goods_marque'] = (new BoxGoodsMarque())->field('marque_name')->where('goods_id', $goods_id)->where('status', 1)->select();
        $data['goods']['goods_imgs'] = (new BoxGoodsImgs())->field('goods_imgs')->where('goods_id', $goods_id)
            ->where('status', 1)->order('img_weight', 'desc')->select();
        $data['gold_num'] = (new BoxMember())->field('gold_num')->find($member_id);//当前用户剩余金币数量
        $begin_time = strtotime($data['goods']['begin_time']);      //有效期（开始时间）
        $end_time = strtotime($data['goods']['end_time']);          //有效期（结束时间）
        $everyday = date('Y-m-d',time());                   //当天日期 例如：2018-10-08
        if (intval($data['goods']['one_day_conversion']) > 0) {
            $order_count = (new BoxOrder())->where('created_time', 'like', "$everyday%")
                ->where('goods_id', $goods_id)->count();
            if (intval($order_count) > intval($data['goods']['one_day_conversion'])) {
                return response_data(0, '今日兑换量，已达上限！', $data);
            }
        }
        if (intval($data['goods']['member_conversion']) > 0) {
            $member_order_count = (new BoxOrder())->where('member_id', $member_id)->where('goods_id', $goods_id)->count();
            if (intval($member_order_count) >= intval($data['goods']['member_conversion'])) {
                return response_data(0, '您的兑换次数不足！', $data);
            }
        }
        if (!(time() >= $begin_time && time() <= $end_time)) {
            return response_error('失败，当前商品不在有效期内！');
        }
        if (intval($data['gold_num']['gold_num']) < intval($data['goods']['conversion_price'])) {
            return response_data(0, '失败，您的金币不足！', $data);
        }
        if (intval($data['goods']['inventory']) <= 0) {
            return response_data(0, '失败，当前商品已售罄！', $data);
        }


        return response_data(1, '成功', $data);
    }
}