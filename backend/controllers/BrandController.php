<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    /**
     * 品牌表
     * @return string
     */
    public function actionIndex()
    {
        //查询所有
        $brands=Brand::find()->where(['is_deleted'=>0])->all();
        //加载视图
        return $this->render('index',['brands'=>$brands]);
    }
    /**
     *添加
     */
    public function actionAdd(){
        //创建request对象
        $request=\Yii::$app->request;
        //创建模型对象
        $model=new Brand();
        if($request->isPost){
            //接收保存数据
            $model->load($request->post());
            //在验证之前 需要实例化上传组件
            //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                //保存上传文件
                //$file='/upload/'.uniqid().'.'.$model->imgFile->extension;
                //$model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                //$model->logo=$file;
                //设置默认值
                $model->is_deleted=0;
                $model->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                //提示错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    /**
     * 删除
     */
    public function actionDelete($id){
        \Yii::$app->db->createCommand("update `brand` set `is_deleted`=1 where `id`={$id}")->execute();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
    }
    /**
     * 回显更新
     */
    public function actionEdit($id){
        //创建request对象
        $request=\Yii::$app->request;
        //找到该数据
        $model=Brand::findOne(['id'=>$id]);
        if($request->isPost){
            //保存数据
            $model->load($request->post());
            //后台验证
           // $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                //保存上传文件
               // $file='/upload/'.uniqid().'.'.$model->imgFile->extension;
               // $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
//                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转页面
                return $this->redirect(['brand/index']);
            }else{
                //提示错误信息
                var_dump($model->getErrors());
                die;
            }
        }
        //展示回显数据
        return $this->render('add',['model'=>$model]);
    }
    /**
     * 图片上传
     */
    public function actionLogoUpload(){
        //实例化上传文件
        $uploadedFile=UploadedFile::getInstanceByName('file');
        //保存 文件
        $fileName='/upload/'.uniqid().'.'.$uploadedFile->extension;
        $result=$uploadedFile->saveAs(\Yii::getAlias('@webroot').$fileName);
        if($result){//如果保存成功 返回json对象
            //将图片上传到七牛云
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="h1Ky68acfOL28m_eds9AaO_8lp-4NWBtH0Avc3j1";
            $secretKey = "JNoxpsRZ52eO1njDemvmxZaDMv_Ve8L12oouer1u";
            $bucket = "wangyj666";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if($err==null){
                //七牛云上传成功
                //访问七牛云图片的地址http://<domain>/<key>
                return json_encode([
                    'url'=>"http://p4ur0l0sj.bkt.clouddn.com/{$key}"
                ]);
            }
        }
    }
    //测试文件上传七牛云
    public function actionTest(){

        // 需要填写你的 Access Key 和 Secret Key
        $accessKey ="h1Ky68acfOL28m_eds9AaO_8lp-4NWBtH0Avc3j1";
        $secretKey = "JNoxpsRZ52eO1njDemvmxZaDMv_Ve8L12oouer1u";
        $bucket = "wangyj666";
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = \Yii::getAlias('@webroot').'/upload/1.jpg';
        // 上传到七牛后保存的文件名
        $key = '/upload/1.jpg';
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
        var_dump($err);
        } else {
            echo '上传成功';
        var_dump($ret);
        }

    }
}
