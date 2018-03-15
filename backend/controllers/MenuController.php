<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    /**
     * 列表
     * @return string
     */
    public function actionIndex()
    {
        $menus=Menu::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['menus'=>$menus]);
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $request=\Yii::$app->request;
        $model=new Menu();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['menu/add']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    /**
     * 删除
     */
    public function actionDelete($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model){
            if($model->delete()){
                return 'success';
            }
        }
        return 'fail';
    }
    /**
     * 修改
     */
    public function actionEdit($id){
        $request=\Yii::$app->request;
        $model=Menu::findOne(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //配置过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}
