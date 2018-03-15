<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuery;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=GoodsCategory::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $request=\Yii::$app->request;
        $model=new GoodsCategory();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id){
                    //子节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //根节点
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','分类添加成功!');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());
                die;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }
    public function actionTest(){
      /*  $countries = new GoodsCategory();
        $countries->name='电视';
        $countries->parent_id=0;
        $countries->makeRoot();*/
        $countries=GoodsCategory::findOne(['id'=>1]);
        $russia = new GoodsCategory();
        $russia->name='4k电视';
        $russia->parent_id=$countries->id;
        $russia->prependTo($countries);
        echo '保存成功';
    }
    public function actionDelete($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        //var_dump(GoodsCategory::find()->where(['parent_id'=>$id])->all());die;
        if(GoodsCategory::find()->where(['parent_id'=>$id])->all()==[]){
            $model->deleteWithChildren();
            \Yii::$app->session->setFlash('success','分类删除成功!');
            return $this->redirect(['goods-category/index']);
        }else{
            \Yii::$app->session->setFlash('warning','下面还有子分类不能删除!');
            return $this->redirect(['goods-category/index']);
        }

    }
    public function actionEdit($id){
        $request=\Yii::$app->request;
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id){
                    //子节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        //根节点
                        $model->makeRoot();
                    }

                }
                \Yii::$app->session->setFlash('success','分类修改成功!');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());
                die;
            }
        }
        $nodes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }
    //配置过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //'except'=>['logo-upload'],
            ]
        ];
    }
}
