<?php

use think\response\Json;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * api 通用响应
 * @param int $code
 * @param string $msg
 * @param array $data
 * @return \Illuminate\Http\JsonResponse
 */
function response_data($code = 0, $msg = '', $data = [])
{
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ];
    return Json::create($result);
}

/**
 * api 成功响应
 * @return \Illuminate\Http\JsonResponse
 */
function response_success($result=[])
{
    return response_data(1, '成功', $result);
}

/**
 * api 失败响应
 * @return \Illuminate\Http\JsonResponse
 */
function response_error($msg='失败')
{
    return response_data(0, $msg);
}


/**
 * 创建订单号
 * @return string
 */
function create_orderno()
{
    return strtoupper(MD5(substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . time()));
}