<?php

namespace frontend\controllers;

use app\models\Address;

class AddressController extends \yii\web\Controller
{
    /**
     * 添加
     */
    public function actionAdd(){
        $member_id=\Yii::$app->user->id;
        $address=Address::find()->where(['member_id'=>$member_id])->all();
        //判断有没有默认地址
        $result=Address::find()->where(['member_id'=>$member_id,'status'=>1])->all();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model=new Address();
            //var_dump($model->status);die;
            $model->load($request->post(),'');
            if ($model->validate()){
                if($model->status) {
                    \Yii::$app->db->createCommand("update `address` set `status`=0 where `member_id`={$member_id}")->execute();
                }
                $model->member_id=$member_id;
                //var_dump($model->status);die;
                if($result){
                    $model->status=1;
                }
                $model->status=$model->status?1:0;
                $model->save();

                return $this->redirect('add.html');
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('index',['model'=>$address]);
    }
    /**
     * 修改
     */
    public function actionEdit($id){
        $member_id=\Yii::$app->user->id;
        $address=Address::find()->where(['member_id'=>$member_id])->all();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model=Address::findOne(['id'=>$id]);
            $status=$model->status;
            //var_dump($status);die;
            $model->load($request->post(),'');
            if ($model->validate()){
                //var_dump($model->status);die;
                if($model->status){
                    $this->actionDefault($id);
                }else{
                    $model->status=0;
                }
                /*if($status && $model->status==0){
                    \Yii::$app->db->createCommand("update `address` set `status`=1 where `member_id`={$model->member_id} limit 1")->execute();
                }*/
                $model->save();
                return $this->redirect('add.html');
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('index',['model'=>$address]);
    }
    /**
     * 获取一条数据
     */
    public function actionGet($id){
        $address=Address::find()->where(['id'=>$id])->asArray()->one();
        if($address){
            return json_encode($address);
        }
        return false;
    }
    /**
     * 删除
     */
    public function actionDelete($id){
        $model=Address::findOne(['id'=>$id]);
        if($model){
            if($model->delete()){
                \Yii::$app->db->createCommand("update `address` set `status`=1 where `member_id`={$model->member_id} limit 1")->execute();
                return 'success';
            }
        }
        return 'fail';
    }
    /**
     * 默认收货地址
     */
    public function actionDefault($id){
        $model=Address::findOne(['id'=>$id]);
        $result=\Yii::$app->db->createCommand("update `address` set `status`=0 where `member_id`={$model->member_id}")->execute();
        if ($result){
            $model->status=1;
            $model->save();
            return 'success';
        }
        return 'fail';

    }
}
