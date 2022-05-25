<?php

namespace app\settings\controller;

use app\BaseController;
use app\settings\model\Settings as ModelSettings;

class Index extends BaseController
{

    // 验证失败是否抛出异常
    protected $failException = true;

    /**
     * 获取所有配置
     */
    public function index()
    {
        $model = new ModelSettings;

        // 查询所有数据
        $settings = $model->where('id', 1)->find(1);

        return json(handleResult($settings));
    }

    /**
     * 读取单个配置
     */
    public function read($name)
    {
        $model = new ModelSettings;

        // 查询所有数据
        $setting = $model->where('id', 1)->find(1)->toArray();

        // 判断是否为空
        if (empty($setting[$name])) {
            return json([
                'error' => '参数错误',
                'detail' => '没有' . $name . '配置选项',
            ]);
        }

        $data = $setting[$name];

        $result = handleResult([$name => $data], false);

        return json($result[$name]);
    }

    /**
     * 更新全部配置
     */
    public function update()
    {
        // 获取需要更新的数据
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\settings\validate\Update');

        // 实例化模型
        $model = new ModelSettings;

        // 保存至数据库
        $settings = $model->where('id', 1)->update($data);

        $settings = $model->where('id', 1)->find();

        return json(handleResult($settings));
    }

    /**
     * 更新部分配置
     */
    public function patch()
    {
        // 获取需要更新的数据
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\settings\validate\Patch');

        // 实例化模型
        $model = new ModelSettings;

        // 保存至数据库
        $model->where('id', 1)->update($data);

        // 从数据库中读取数据
        $settings = $model->where('id', 1)->find(1);

        $result = [];
        // 过滤返回的数据
        foreach ($data as $key => $value) {
            $result[$key] = $settings[$key];
        }

        return json(handleResult($result));
    }
}
