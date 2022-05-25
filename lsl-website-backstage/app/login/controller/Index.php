<?php

namespace app\login\controller;

use app\BaseController;
use app\login\model\Users as ModelUsers;
use app\login\utils\Password as UtilsPassword;
use Firebase\JWT\JWT;

use think\facade\Env;

class Index extends BaseController
{

    // 验证失败是否抛出异常
    protected $failException = true;

    /**
     * 登录
     *
     * @return json
     */
    public function index()
    {
        // 获取参数
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\login\validate\Users.login');

        $model = new ModelUsers;

        $user = $model->where('username', '=', $data['username'])->find();

        if (!empty($user)) {
            // 验证密码
            $result = UtilsPassword::verifyPassword($data['password'], $user->password);

            if ($result) {
                $payload = [
                    'iat' => time(),
                    'nbf' => time(),
                    'exp' => time() + 7200,
                    'data' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'rank' => $user->rank,
                    ],
                ];

                $token = JWT::encode($payload, Env::get('token.secret'));

                return json(['message' => '登录成功', 'token' => $token]);
            }
        }

        return json(['error' => '登录失败', 'detail' => '用户名或密码错误'], 400);
    }
}
