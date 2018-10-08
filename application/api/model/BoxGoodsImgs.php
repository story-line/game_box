<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:09
 */

namespace app\api\model;

use think\Model;

class BoxGoodsImgs extends Model
{
    protected $created_time = 'created_time';
    protected $updated_time = 'updated_time';
    protected $autoWriteTimestamp = 'datetime';
}