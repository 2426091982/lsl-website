<?php

namespace app\settings\validate;

use think\Validate;

class Patch extends Validate
{
    protected $rule = [
        'title' => ['max' => 40],
        'logo' => ['max' => 100],
        'favicon' => ['max' => 100],
        'copyright' => ['max' => 50],
        'icp_license' => ['max' => 30],
        'public_ecurity_license' => ['max' => 30],
        'keywords' => ['array'],
        'description' => ['max' => 200],
        'carousel' => ['array'],
    ];

}
