<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 11:54
 */

namespace app\api\model;

use think\Model;

class BoxGoods extends Model
{
    protected $created_time = 'created_time';
    protected $autoWriteTimestamp = 'datetime';

    public function getTruePriceAttr($value)
    {
        return sprintf("%0.2f", $value/100);
    }

    public function setTruePriceAttr($value)
    {
        return $value * 100;
    }
}