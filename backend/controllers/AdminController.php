<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\PasswordForm;
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
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->created_at=time();
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key = \Yii::$app->security->generateRandomString();
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
        $model->status = 0;
        $model->save();
        return $this->redirect(['admin/index']);
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
    //登录才能访问的页面
    public function actionInfo(){
        //查看当前登录用户的信息
        echo '欢迎'.\Yii::$app->user->identity->username.'访问用户中心';
    }

    //未登录可以访问
    public function actionAbout(){
        echo '关于我们';
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
    public function actionEdit($id){
        $request=\Yii::$app->request;
        $model=new PasswordForm();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->check($id)){
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['admin/index']);
                }
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
}
