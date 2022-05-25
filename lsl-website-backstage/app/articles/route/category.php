<?php

/**
 * 文章分类路由
 */

namespace app\articles\route;

use think\facade\Route;

// 获取所有分类
Route::get('/category', 'articles/Category/index');

// 添加一个分类
Route::post('/category', 'articles/Category/create');

// 更新一个分类
Route::put('/category/:id', 'articles/Category/update');

// 删除一个分类
Route::delete('/category/:id', 'articles/Category/delete');
