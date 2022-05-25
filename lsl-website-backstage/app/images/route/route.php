<?php

/**
 * 图片应用路由
 */

namespace app\images\route;

use think\facade\Route;

// 获取回收站所有内容
Route::get('/recycle', 'images/Recycle/index');
// 清空回收站
Route::delete('/recycle', 'images/Recycle/delete');

// 添加图片至回收站
Route::post('/:id/recycle', 'images/Recycle/create');
// 从回收站中移除图片
Route::delete('/:id/recycle', 'images/Recycle/update');


// 获取所有图片链接
Route::get('/', 'images/Index/index');

// 获取单个图片
Route::get('/:name', 'images/Index/read');

// 添加一张图片
Route::post('/', 'images/Index/create');

// 删除一张图片
Route::delete('/:id', 'images/Index/delete');
