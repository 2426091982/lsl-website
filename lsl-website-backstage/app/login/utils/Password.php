<?php

namespace app\login\utils;

class Password
{
    /**
     * 散列密码
     *
     * @param String $password
     * @return String | Boolean
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码是否匹配
     *
     * @param String $password
     * @param String $hash
     * @return Boolean
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
