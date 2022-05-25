<?php
namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class Http extends Handle
{
    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json([
                'error' => '参数错误',
                'detail' => $e->getError(),
            ], 400);
        }

        // 文件不存在判断
        if ($e instanceof Exception && strstr($e->getMessage(), 'file not exists')) {
            return json([
                'error' => '文件不存在',
                'detail' => '文件路径不存在',
            ], 400);
        }

        // 图片上传处理
        if ($e instanceof Exception && $e->getMessage() == 'no file to uploaded') {
            return json([
                'error' => '图片上传失败',
                'detail' => '没有要上传的图片',
            ], 400);
        }

        // 请求异常
        if ($e instanceof HttpException && $request->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

    // public function render(Exception $e)
    // {
    //     // 参数验证错误
    //     if ($e instanceof ValidateException) {
    //         return json([
    //             'error' => '参数错误',
    //             'detail' => $e->getError(),
    //         ], 400);
    //     }

    //     // 图片上传处理
    //     if ($e instanceof Exception && $e->getMessage() == 'no file to uploaded') {
    //         return json([
    //             'error' => '图片上传失败',
    //             'detail' => '没有要上传的图片',
    //         ], 400);
    //     }

    //     // 唯一值重复
    //     if ($e instanceof PDOException && $e->getCode() == 10501) {
    //         return json(['error' => '添加失败', 'detail' => '用户名已重复'], 400);
    //     }

    //     // 其他错误交给系统处理
    //     {
    //         return parent::render($e);
    //     }

    // }

}
