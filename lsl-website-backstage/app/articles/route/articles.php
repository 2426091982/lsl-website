<?php

/**
 * 文章应用路由
 */

namespace app\articles\route;

use think\facade\Route;

// 获取所有文章
Route::get('/', 'articles/Index/index');

// 获取单个文章
Route::get('/:id', 'articles/Index/read')->pattern(['id' => '\d+']);

// 添加一篇文章
Route::post('/', 'articles/Index/create');

// 更新文章
Route::put('/:id', 'articles/Index/update')->pattern(['id' => '\d+']);
// 更新文章部分信息
Route::patch('/:id', 'articles/Index/patch')->pattern(['id' => '\d+']);

// 删除一篇文章
Route::delete('/:id', 'articles/Index/delete')->pattern(['id' => '\d+']);
