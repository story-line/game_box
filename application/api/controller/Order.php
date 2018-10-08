<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 15:09
 */

namespace app\api\controller;

use app\api\model\BoxGoods;
use app\api\model\BoxOrder;
use think\Controller;
use think\Request;

class Order extends Controller
{
    /**
     * 创建订单
     * @param Request $request
     * @return think\response\Json
     */
    public function create(Request $request)
    {
        if (!$request->isPost()) {
            return response_error('请求方式错误');
        }
        $data = $request->param();
        $goods = new BoxGoods();
        $goods_detail = $goods->field('conversion_price,merchandise_type')->find($data['goods_id']);//商品信息
        if ($goods_detail['merchandise_type'] == 1){
            $order_data['status'] = 0;
            if (!$data['area'] && !$data['address'] && !$data['consignee'] && !$data['c_tel']) {
                return response_data(0,'请填写收货地址！');
            }
        } else {
            $order_data['status'] = 1;
        }
        $order_data['goods_id'] = $data['goods_id'];
        $order_data['member_id'] = $data['member_id'];
        $order_data['total_price'] = $goods_detail['conversion_price'];
        $order_data['order_no'] = create_orderno();
        if ((new BoxOrder())->insert($order_data)) {
            return response_data(1, '订单创建成功');
        } else {
            return response_error('信息提交失败，请重试');
        }
    }
}