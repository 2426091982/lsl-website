<?php

namespace app\articles\validate;

use think\Validate;

class Patch extends Validate
{
    protected $rule = [
        'title' => ['max' => 20],
        'cover' => ['max' => 100],
        'description' => ['max' => 200],
        'category' => ['number'],
        'visibility' => ['boolean'],
        'content' => [],
        'date' => ['date'],
        'nominate' => ['boolean'],
    ];
}
