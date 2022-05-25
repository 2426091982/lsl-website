<?php

namespace app\settings\validate;

use think\Validate;

class Update extends Validate
{
    protected $rule = [
        'title' => ['require', 'max' => 40],
        'logo' => ['require', 'max' => 100],
        'favicon' => ['require', 'max' => 100],
        'copyright' => ['require', 'max' => 50],
        'icp_license' => ['require', 'max' => 30],
        'public_ecurity_license' => ['require', 'max' => 30],
        'keywords' => ['require', 'array'],
        'description' => ['require', 'max' => 200],
        'carousel' => ['require', 'array'],
    ];

}
