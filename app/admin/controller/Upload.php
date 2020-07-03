<?php

namespace app\admin\controller;

use app\BaseController;
use think\Request;
use think\File;

class Upload extends BaseController
{

    public function upload3d(){
        if(empty($_FILES)){
            return json(['status' => 0, 'msg' => '请上传文件']);
        }
        //获取upload配置信息
        $path   = config('common.app_path');
        $config = config('upload.');
        $size   = $config['file_size'];
        $savePath = $config['save_path'] . DIRECTORY_SEPARATOR . 'files';

        // 获取表单上传文件
        $file = request()->file('files');
        try {
            validate(['file'=>'filesize:104857600|fileExt:stl'])
                ->check(['files' => $file]);
            $savename = \think\facade\Filesystem::disk('public')->putFile('topic', $file);

            $path = $path . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $savename;
            $path = str_replace('\\', '/', $path);
            return json(['status' => 1, 'msg' => $path]);
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
        }
    }
    
    /**
    * 上传文件
    */
    public function fileUpload()
    {
        if(empty($_FILES)){
            return json(['status' => 0, 'msg' => '请上传文件']);
        }
        $file = Request()->file("file");
        //获取upload配置信息
        $path   = config('common.app_path');
        $config = config('upload.');
        $size   = $config['file_size'];
        $savePath = $config['save_path'] . DIRECTORY_SEPARATOR . 'files';
        $info = $file->validate(['size'=>$size])
            ->move($savePath);

        if($info){
            $path = $path. DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $info->getSaveName();
            $path = str_replace('\\', '/', $path);
            return json(['status' => 1, 'msg' => $path]);
        }else{
            return json(['status' => 0, 'msg' => $file->getError()]);
        }
    }
   
}
