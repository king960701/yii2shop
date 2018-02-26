<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
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
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                //保存上传文件
                $file='/upload/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                $model->logo=$file;
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
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                //保存上传文件
                $file='/upload/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                $model->logo=$file;
                $model->save();
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
}
