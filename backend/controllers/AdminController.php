<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\RePasswordForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //var_dump(\Yii::$app->user->id);
        $admin=Admin::find()->where(['status'=>1])->all();
        return $this->render('index',['admin'=>$admin]);
    }
    public function actionAdd(){
        $request=\Yii::$app->request;
        $model=new Admin();
//        指定场景
        $model->scenario=Admin::SCENARIO_ADD;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                //提示错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionLogin(){
        //登录表单
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            if($model->validate()){
                if($model->login()){
                    //登录成功
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['admin/index']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionDelete($id)
    {
        $model = Admin::findOne(['id' => $id]);
        if($model){
            $model->status = 0;
            if(!$model->save()){
                return 'fail';
            }
        }
        return 'success';
    }
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    //配置过滤器
    public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['index'],
                'rules'=>[
                    [//允许登录用户访问index
                        'allow'=>true,//是否允许
                        'actions'=>['index'],//针对哪些操作
                        'roles'=>['@'],//?未认证 @已认证

                    ],
                ]
            ]
        ];
    }
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }
    public function actionEdit(){
        $request=\Yii::$app->request;
        $model=new PasswordForm();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->check()){
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','修改密码成功,请重新登录!');
                    //修改成功退出登录
                    return $this->redirect(['admin/login']);
                }
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionRePassword($id)
    {
        $model=Admin::findOne(['id'=>$id]);
        $model->password_hash=\Yii::$app->security->generatePasswordHash(123);
        $model->save();
        \Yii::$app->session->setFlash('success','重置密码成功密码为 123');
        return $this->redirect(['admin/index']);
    }
    public function actionUpdate($id){
        $model=Admin::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        //坑:这里指定的场景,验证规则中必须存在该场景
        $model->scenario=Admin::SCENARIO_EDIT;
        //检查用户是否存在
        if(!$model){
            throw new HttpException(404,'该用户不存在或已被删除');
        }
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功!');
                //跳转到当前页面
                return $this->refresh();
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}
