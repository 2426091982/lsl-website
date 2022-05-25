<?php

namespace app\login\validate;

use think\Validate;

class Users extends Validate
{
    protected $rule = [
        'username' => ['require', 'max' => 30],
        'password' => ['require', 'max' => 50],
        'rank' => ['require', 'number', 'min' => 1, 'max' => 2],
        'notes' => ['max' => 100],
    ];

    public function sceneUpdate()
    {
        return $this->only(['username', 'password', 'rank', 'notes'])
            ->remove(['password' => 'require']);
    }

    public function sceneLogin()
    {
        return $this->only(['username', 'password']);
    }
}
