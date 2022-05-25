<?php
declare (strict_types = 1);

namespace app\middleware;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Env;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $authorization = $request->header('authorization');

        // 判断authorization是否为空
        if (!empty($authorization)) {
            // 取出 token
            $token = substr($authorization, 7);
        } else {
            $token = '';
        }

        // 判断如果是 GET 请求并且没有token
        if (empty($token) && $request->isGet()) {
            // 允许通过中间件
            return $next($request);
        }

        // 排除登录模块
        if ($request->baseUrl() == '/login') {
            // 允许通过中间件
            return $next($request);
        }

        // 排除OPTIONS请求
        if ($request->method() == 'OPTIONS') {
            // 允许通过中间件
            return $next($request);
        }

        try {
            // 验证token
            $result = JWT::decode($token, Env::get('token.secret'), array('HS256'));
        } catch (SignatureInvalidException $e) {
            return json(['error' => '权限验证失败', 'detail' => '密钥不正确'], 401);
        } catch (ExpiredException $e) { // token过期
            return json(['error' => '权限验证失败', 'detail' => '密钥过期'], 401);
        } catch (Exception $e) { //其他错误
            return json(['error' => '权限验证失败', 'detail' => '密钥格式错误'], 401);
        }

        $request->user = $result->data;

        return $next($request);
    }
}
