<?php

namespace app\images\controller;

use app\BaseController;
use app\common\utils\Image as UtilImage;
use app\images\model\Images as ModelImages;
use RuntimeException;
use think\exception\ErrorException;
use think\facade\Env;
use \think\facade\Filesystem;

class Index extends BaseController
{
    // 验证失败是否抛出异常
    protected $failException = true;

    /**
     * 读取所有图片链接
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
        $images = $model->where('recycle', 0)->field('id, name, create_time');

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

        foreach ($images as $key => $value) {
            $value['url'] = UtilImage::handleUrl($value['name']);
            $value['date'] = $value['create_time'];

            unset($value['create_time']);

            $images[$key] = $value;
        }

        // 返回结果
        return json($images);
    }

    /**
     * 读取图片方法
     * @param string $name 图片名字
     */
    public function read($name)
    {
        $isDownload = $this->request->get('download');

        if (empty($isDownload) || $isDownload == "false") {
            $isDownload = false;
        } else if ($isDownload == "true") {
            $isDownload = true;
        }

        $DownloadFileName = $this->request->get('filename') ?: $name;

        $imagePath = uploadImagesPath() . '/' . $name;

        return download($imagePath)->name($DownloadFileName)->force($isDownload);
    }

    /**
     * 新增一张图片
     *
     * @return json
     */
    public function create()
    {
        $file = $this->request->file('image');

        if (empty($file)) {
            return json([
                'error' => '图片上传失败',
                'detail' => '参数错误',
            ]);
        }

        // 验证文件
        validate(['image' => 'fileSize:83886080|fileExt:jpg,jpg,png'])
            ->check([$file]);

        $fileName = Filesystem::disk('images')->putFile('', $file, 'uniqid');

        $image = new ModelImages([
            'name' => $fileName,
        ]);

        $image->save();

        // 图片地址
        $imagePath = Env::get('host.domain') . '/images/' . $fileName;

        // 返回
        return json([
            'id' => (int) $image->id,
            'name' => $image->name,
            'url' => $imagePath,
            'date' => $image['create_time'],
        ]);
    }

    /**
     * 图片删除方法
     */
    public static function delete($id)
    {

        $model = new ModelImages;

        $image = $model->where('id', $id)->find();

        if (empty($image)) {
            return json([
                'error' => '删除失败',
                'detail' => '图片不存在',
            ])->code(400);
        }

        $imagePath = uploadImagesPath() . '/' . $image['name'];

        try {
            // 删除图片
            unlink($imagePath);
        } catch (RuntimeException $e) {
            return response()->code(204);
        } catch (ErrorException $e) {
            return response()->code(204);
        }

        // 删除数据库内图片
        $image->delete();

        // 返回
        return response()->code(204);
    }
}
