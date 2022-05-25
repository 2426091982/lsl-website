<?php

namespace app\articles\validate;

use think\Validate;

class Articles extends Validate
{
    protected $rule = [
        'title' => ['require', 'max' => 20],
        'cover' => ['require', 'max' => 100],
        'description' => ['require', 'max' => 200],
        'category' => ['require', 'number', 'max' => 100],
        'visibility' => ['require', 'boolean'],
        'content' => ['require'],
        'date' => ['require', 'date'],
        'nominate' => ['require', 'boolean'],
    ];
}
