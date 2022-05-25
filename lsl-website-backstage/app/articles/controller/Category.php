<?php

namespace app\articles\controller;

use app\articles\model\Articles as ModelArticles;
use app\articles\model\Category as ModelCategory;
use app\BaseController;

class Category extends BaseController
{
    /**
     * 读取所有分类
     */
    public function index()
    {
        // 实例化模型
        $model = new ModelCategory;

        $result = $model->select();

        return json($result);
    }

    /**
     * 添加一个分类
     */
    public function create()
    {
        // 获取参数
        $data = $this->request->post();

        if (empty($data['name'])) {
            return json([
                'error' => '参数错误',
                'detail' => 'name参数不能为空',
            ])->code(400);
        }

        $category = new ModelCategory([
            'name' => $data['name'],
        ]);

        $category->save();

        $category['id'] = (int) $category['id'];

        return json($category)->code(201);
    }

    /**
     * 更新一个分类
     */
    public function update($id)
    {
        // 获取参数
        $data = $this->request->post();

        if (empty($data['name'])) {
            return json([
                'error' => '参数错误',
                'detail' => 'name参数不能为空',
            ])->code(400);
        }

        $model = new ModelCategory;

        $category = $model->where('id', $id)->find();

        $category['name'] = $data['name'];

        $category->save();

        return json($category);
    }

    /**
     * 删除一个分类
     */

    public function delete($id)
    {
        $modelArticles = new ModelArticles;

        $categoryArticles = $modelArticles->where('c_id', $id)->select();

        if (count($categoryArticles->toArray()) != 0) {
            return json([
                'error' => '删除失败',
                'detail' => '分类下还有文章',
            ])->code(400);
        }

        $model = new ModelCategory;

        $result = $model->where('id', $id)->find();

        if (empty($result)) {
            return json([
                'error' => '删除失败',
                'detail' => '分类不存在',
            ])->code(400);
        }

        $result->delete();

        return response()->code(204);
    }
}
