<?php
use think\facade\Config;
use app\common\utils\Image as UtilImage;

// 获取图片存储位置
function uploadImagesPath()
{
    return Config::get('filesystem.disks.images.root');
}

/**
 * 处理结果
 */
function handleResult($result)
{
    $result['url'] = UtilImage::handleUrl($result['name']);
    $result['date'] = $result['create_time'];

    unset($result['name']);
    unset($result['create_time']);

    return $result;
}

/**
 * 处理数组结果
 * @param Array $result 要处理的数组
 */
function handleArrayResult($result)
{
    // 遍历数组
    foreach ($result as $key => $value) {
        // 处理每一个值
        $result[$key] = handleResult($value);
    }

    return $result;
}
