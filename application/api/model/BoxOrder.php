<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 19:12
 */
namespace app\api\model;

use think\Model;

class BoxOrder extends Model
{
    protected $created_time = 'created_time';
    protected $updated_time = 'updated_time';
    protected $autoWriteTimestamp = 'datetime';
}