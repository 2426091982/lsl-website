<?php

/**
 * 用户登录路由
 */

namespace app\login\route;

use think\facade\Route;

Route::post('/', 'login/Index/index');
