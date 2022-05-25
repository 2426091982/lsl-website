<?php
use app\common\utils\Image as UtilImage;

function handleResult($result)
{
    // 处理轮播图链接
    if (!empty($result['carousel'])) {
        // 转换为数组
        $data = json_decode(json_encode($result['carousel']), true);
        // 新的空数组
        $carousel = [];
        // 遍历处理图片链接
        foreach ($data as $value) {
            $value['url'] = UtilImage::handleUrl($value['url']);
            array_push($carousel, $value);
        }

        $result['carousel'] = $carousel;
    }

    // 处理keyword
    if (!empty($result['keywords'])) {
        $result['keywords'] = json_decode(json_encode($result['keywords']), true);
    }

    // 处理logo链接
    if (!empty($result['logo'])) {
        $result['logo'] = UtilImage::handleUrl($result['logo']);
    }

    // 处理favicon链接
    if (!empty($result['favicon'])) {
        $result['favicon'] = UtilImage::handleUrl($result['favicon']);
    }

    return $result;
}
