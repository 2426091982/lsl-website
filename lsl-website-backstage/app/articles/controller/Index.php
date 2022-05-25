<?php

namespace app\articles\controller;

use app\articles\model\Articles as ModelArticles;
use app\BaseController;

class Index extends BaseController
{

    // 验证失败是否抛出异常
    protected $failException = true;

    public function index()
    {
        // get 参数
        $query = $this->request->get();

        $querySchema = ['limit', 'offset', 'page', 'per_page', 'category', 'search', 'nominate'];

        // 判断参数是否存在
        foreach ($querySchema as $key) {
            if (array_key_exists($key, $query) == false) {
                $query[$key] = 0;
            }
        }

        // 实例化模型
        $model = new ModelArticles;

        // 读取数据
        $articles = $model->field('hsl_articles.id, title, description, nominate, c_id, cover, date');

        // 判断是否是后台管理请求，如果不是，只返回可见值
        if (empty($this->request->user)) {
            $articles->where('visibility', '=', 1);
        } else {
            $articles->field('hsl_articles.id, title, description, nominate, c_id, cover, visibility, date');
        }

        // 查询推荐
        if ($query['nominate']) {
            $articles->where('nominate', $query['nominate'] == "true" ? 1 : 0);
        }

        // 搜索
        if ($query['search']) {
            $articles->where('title', 'like', '%' . $query['search'] . '%');
        }

        // 限制读取行数
        if ($query['limit']) {
            $articles->limit($query['limit'] + $query['offset']);
        }

        // 处理页数
        $articles->page($query['page'], $query['per_page']);

        // 查询分类
        if (!empty($query['category'])) {
            $articles->hasWhere('category', function ($q) use ($query) {
                $q->where('id', $query['category']);
            }, 'title, description, nominate, c_id, cover, date');
        }

        // 读取所有条件符合的数据
        $articles = $articles->select();

        // 偏移值
        if ($query['offset']) {
            $offset = (int) $query['offset'];
            $result = [];
            $articles = $articles->toArray();

            for ($i = 0; $i < count($articles); $i++) {
                if ($offset > $i) {
                    continue;
                }

                $result[] = $articles[$i];
            }

            $articles = $result;
        }

        // 返回结果
        return json(handleArrayResult($articles));
    }

    /**
     * 文章读取方法
     *
     * @return
     */
    public function read($id)
    {
        // 实例化模型
        $model = new ModelArticles;

        // 使用主键读取数据
        $article = $model->where('id', '=', $id)->field('title, description, cover, c_id, content, date, nominate, visibility');

        // 判断是否是后台管理请求，如果不是，只返回可见值
        if (empty($this->request->user)) {
            $article->where('visibility', '=', 1);
        }

        $article = $article->find();

        // 如果数据不存在
        if (empty($article)) {
            return json(['error' => '资源不存在'])->code(404);
        }

        return json(handleResult($article));
    }

    /**
     * 文章创建方法
     *
     * @return json
     */
    public function create()
    {
        // 获取参数
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\articles\validate\Articles');

        // 实例化模型
        $article = new ModelArticles([
            'title' => $data['title'],
            'description' => $data['description'],
            'cover' => $data['cover'],
            'c_id' => $data['category'],
            'nominate' => $data['nominate'],
            'visibility' => $data['visibility'],
            'content' => $data['content'],
            'date' => $data['date'],
        ]);

        // 保存至数据库
        $article->save();

        // 返回值
        return json(handleResult($article));
    }

    /**
     * 文章更新方法
     *
     * @return json
     */
    public function update($id)
    {
        // 获取需要更新的数据
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\articles\validate\Articles');

        // 文章内容图片链接处理

        // 实例化模型
        $model = new ModelArticles;

        // 使用主键读取数据
        $article = $model->where('id', $id)->find();

        if (empty($article)) {
            return json(['error' => '资源不存在'])->code(404);
        }

        $article->title = $data['title'];
        $article->description = $data['description'];
        $article->cover = $data['cover'];
        $article->c_id = $data['category'];
        $article->visibility = $data['visibility'];
        $article->nominate = $data['nominate'];
        $article->content = $data['content'];
        $article->date = $data['date'];

        // 保存至数据库
        $article->save();

        return json(handleResult($article));
    }

    /**
     * 文章部分更新方法
     *
     * @return json
     */
    public function patch($id)
    {
        // 获取需要更新的数据
        $data = $this->request->post();

        // 验证数据
        $this->validate($data, 'app\articles\validate\Patch');

        // 使用主键读取数据
        $articles = ModelArticles::get($id);

        // 遍历然后赋值
        foreach ($data as $key => $value) {
            if (in_array($key, $articles->toArray())) {
                $articles[$key] = $value;
            }
        }

        // 保存至数据库
        $articles->save();

        return json(handleResult($articles));
    }

    /**
     * 文章删除方法
     *
     * @param [Number] $id
     * @return void
     */
    public function delete($id)
    {
        // 实例化模型
        $model = new ModelArticles;
        // 用主键查找数据
        $crticles = $model->where('id', $id);

        // 判断数据有没有内容
        if (!$crticles) {
            // 如果没有就直接返回结果
            return response()->code(204);
        }

        // 删除数据库里面的内容
        $crticles->delete();

        // 结束并返回
        return response()->code(204);
    }
}
