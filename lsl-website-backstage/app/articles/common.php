<?php
// 这是系统自动生成的公共文件

use app\common\utils\Image as UtilImage;

/**
 * 处理返回值
 *@param array $result
 * @return Object
 */
function handleResult($result)
{
    // 判断图片链接。
    $result['cover'] = UtilImage::handleUrl($result['cover']);

    $result['nominate'] = $result['nominate'] == 1 ? true : false;

    if (isset($result['visibility'])) {
        $result['visibility'] = $result['visibility'] == 1 ? true : false;
    }

    if (isset($result['c_id'])) {
        $result['category'] = $result['c_id'];
        unset($result['c_id']);
    }

    // 返回
    return $result;
}

/**
 * 处理数组返回值
 *
 * @return Array
 */
function handleArrayResult($result)
{
    foreach ($result as $key => $value) {
        $result[$key] = handleResult($value);
    }

    return $result;
}
