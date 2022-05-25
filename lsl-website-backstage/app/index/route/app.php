<?php

/**
 * 文章应用路由
 */

namespace app\index\route;

use think\facade\Route;

Route::get('/', function () {
    return json(['docs' => 'https://www.apipark.cn/s/41b297f0-7cdc-43bb-b2c1-5fa22270c634']);
});
