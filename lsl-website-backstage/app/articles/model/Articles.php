<?php

namespace app\articles\model;

use app\articles\model\Category;
use think\Model;

class Articles extends Model
{
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'c_id')->bind(['category' => 'id']);
    }
}
