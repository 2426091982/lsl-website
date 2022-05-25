<?php

/**
 * 网站配置路由
 */

namespace app\settings\route;

use think\facade\Route;

Route::get('/test', 'settings/Index/test');

// 获取所有文章路由
Route::get('/', 'settings/Index/index');
// 更新所有配置路由
Route::put('/', 'settings/Index/update');
// 更新部分配置路由
Route::patch('/', 'settings/Index/patch');
// 获取单个配置路由
Route::get('/:name', 'settings/Index/read');
