<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:57
 */

namespace app\api\controller;

use app\api\model\BoxMember;
use think\Controller;
use think\Request;

class Member extends Controller
{
    public function index(Request $request)
    {
        $member_id = $request->param('member_id', 1);
        $member = new BoxMember();
        $member_list = $member->field('member_id,nick_name,photo,gold_num,red_money')->where('member_id',$member_id)->find()->toArray();

        return response_data(1, '成功', $member_list);
    }
}