<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化模型
        $article=Article::find()->where(['is_deleted'=>0])->all();
        return $this->render('index',['article'=>$article]);
    }
    public function actionAdd(){
        //创建request对象
        $request=\Yii::$app->request;
        //创建模型对象
        $model=new Article();
        $detail=new ArticleDetail();
        if($request->isPost){
            //接收保存数据
            $model->load($request->post(
            ));
            $detail->load($request->post());
            if($model->validate() && $detail->validate()){
                $model->is_deleted=0;
                $model->create_time=time();
                $model->save();
                $detail->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                //提示错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }
    /**
     * 删除
     */
    public function actionDelete($id){
        \Yii::$app->db->createCommand("update `article` set `is_deleted`=1 where `id`={$id}")->execute();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }
    /**
     * 回显更新
     */
    public function actionEdit($id){
        //创建request对象
        $request=\Yii::$app->request;
        //创建模型对象
        $model=Article::findOne(['id'=>$id]);
        $detail=ArticleDetail::findOne(['article_id'=>$id]);
        if($request->isPost){
            //接收保存数据
            $model->load($request->post(
            ));
            $detail->load($request->post());
            if($model->validate() && $detail->validate()){
                $model->is_deleted=0;
                $model->create_time=time();
                $model->save();
                $detail->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                //提示错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }
    /**
     * 内容
     */
    public function actionDetail($id){
        $model=Article::findOne(['id'=>$id]);
        $detail=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('detail',['detail'=>$detail,'model'=>$model]);
    }

    public function actions()
    {
        $time=time().uniqid();
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.cn",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{$time}",//上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot")
            ]
        ]
    ];
}
}
