<?php

namespace app\common\utils;

use RuntimeException;
use think\exception\ErrorException;
use think\facade\Env;

class Image
{
    public static function handleUrl($image)
    {
        // 判断图片链接。
        if (strpos($image, 'http') !== 0) {
            return Env::get('host.domain') . '/images/' . $image;
        } else {
            return $image;
        }
    }

    public static function delete($path)
    {

        try {
            unlink($path);
        } catch (RuntimeException $e) {
            return true;
        } catch (ErrorException $e) {
            return true;
        }

        return true;
    }
}
