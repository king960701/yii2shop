<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    /**
     * 列表
     * @return string
     */
    public function actionIndex()
    {
        //查询所有
        $model=ArticleCategory::find()->where(['is_deleted'=>0])->all();
        return $this->render('index',['model'=>$model]);
    }
    /**
     * 添加
     */
    public function actionAdd(){
        //创建request对象
        $request=\Yii::$app->request;
        //创建模型对象
        $model=new ArticleCategory();
        if($request->isPost){
            //接收保存数据
            $model->load($request->post());
            //验证
            if($model->validate()){
                $model->is_deleted=0;
                $model->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
                die;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    /**
     * 删除
     */
    public function actionDelete($id){
        //找到这条数据
        $article=ArticleCategory::findOne(['id'=>$id]);
        $article->is_deleted=1;
        $article->save();
        //跳转
        \Yii::$app->session->setFlash('success','删除成功!');
        return $this->redirect(['article-category/index']);
    }
    /**
     * 修改更新
     */
    public function actionEdit($id){
        //创建request对象
        $request=\Yii::$app->request;
        //创建模型对象
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            //验证
            if($model->validate()){
                $model->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
                die;
            }
        }
        //显示回显页面
        return $this->render('add',['model'=>$model]);
    }
    //配置过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //默认情况下对所有操作生效
                //排除不需要授权的操作
            ]
        ];
    }
}
