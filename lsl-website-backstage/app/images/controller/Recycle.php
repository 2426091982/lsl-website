<?php

namespace app\images\controller;

use app\BaseController;
use app\common\utils\Image as UtilImage;
use app\images\model\Images as ModelImages;

class Recycle extends BaseController
{
    /**
     * 读取回收站所有内容
     */
    public function index()
    {
        // get 参数
        $query = $this->request->get();

        $querySchema = ['limit', 'offset', 'page', 'per_page'];

        // 判断参数是否存在
        foreach ($querySchema as $key) {
            if (array_key_exists($key, $query) == false) {
                $query[$key] = 0;
            }
        }

        // 实例化模型
        $model = new ModelImages;

        // 读取数据
        $images = $model->where('recycle', 1)->field('id, name, create_time');

        // 限制读取行数
        if ($query['limit']) {
            $images->limit($query['limit'] + $query['offset']);
        }

        // 处理页数
        $images->page($query['page'], $query['per_page']);

        // 读取所有条件符合的数据
        $images = $images->select();

        // 偏移值
        if ($query['offset']) {
            $offset = (int) $query['offset'];
            $result = [];
            $images = $images->toArray();

            for ($i = 0; $i < count($images); $i++) {
                if ($offset > $i) {
                    continue;
                }

                $result[] = $images[$i];
            }

            $images = $result;
        }

        $result = handleArrayResult($images);

        // 返回结果
        return json($result);
    }

    public function delete()
    {
        $model = new ModelImages;

        $images = $model->where('recycle', 1)->select();

        foreach ($images as $value) {
            $imagePath = uploadImagesPath() . '/' . $value['name'];
            UtilImage::delete($imagePath);
        }

        $images->delete();

        return json(['message' => '删除成功'], 200);

    }

    /**
     * 将图片添加至回收站
     */
    public function create($id)
    {
        $model = new ModelImages;

        $image = $model->where('id', $id)->find();

        if (empty($image)) {
            return json([
                'error' => '添加失败',
                'detail' => '图片不存在',
            ])->code(400);
        }

        if ($image['recycle'] == 1) {
            return json([
                'error' => '添加失败',
                'detail' => '图片已经存在于回收站',
            ])->code(400);
        }

        $image['recycle'] = 1;
        $image['expired'] = time();

        $image->save();

        return json([
            'message' => '添加成功',
        ]);
    }

    /**
     * 将图片移出回收站
     */

    public function update($id)
    {
        $model = new ModelImages;

        $image = $model->where('id', $id)->find();

        if (empty($image)) {
            return json([
                'error' => '移动失败',
                'detail' => '图片不存在',
            ])->code(400);
        }

        if ($image['recycle'] == 0) {
            return json([
                'error' => '移动失败',
                'detail' => '图片不存在于回收站',
            ])->code(400);
        }

        $image['recycle'] = 0;
        $image['expired'] = 0;

        $image->save();

        return json([
            'message' => '移动成功',
        ]);

    }
}
