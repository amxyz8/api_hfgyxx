<?php
namespace app\api\controller;

use app\common\lib\Show;
use think\exception\ValidateException;
use think\facade\Filesystem;

class Image extends AuthBase
{
    public function upload()
    {
        //接收文件上传类型
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $type = input('type', 'image', 'trim');
        $file = $this->request->file("file");
    
        try {
            $suffixConfig = config('upload.suffix_arr');
            $sizeConfig = config('upload.size_arr');
            $size = $sizeConfig[$type];
            $suffix = $suffixConfig[$type];
            validate(['file'=>[
                //限制文件大小
                'fileSize'      =>  $size * 1024 * 1024,
                //限制文件后缀
                'fileExt'       =>  $suffix
            ]], [
                'file.fileSize' =>  '上传的文件大小不能超过'.$size.'M',
                'file.fileExt'  =>  '请上传后缀为:'.$suffix.'的文件'
        
            ])->check(['file'=>$file]);
            $filename = Filesystem::disk('public')->putFile('images', $file);
            if (!$filename) {
                return Show::error('上传失败');
            }
        } catch (ValidateException $e) {
            return Show::error($e->getMessage());
        }
    
        $imageUrl = [
            "image"  =>  "/upload/".$filename
        ];
        return Show::success($imageUrl, '上传成功');
    }
}
