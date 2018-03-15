<?php

namespace frontend\controllers;

use Codeception\Module\Redis;
use frontend\aliyun\SmsHandler;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;

class MemberController extends \yii\web\Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
    /**
     * 注册用户
     * @return string
     */
    public function actionRegist()
    {
        $request=\Yii::$app->request;
        $model=new Member();
        if ($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->created_at=time();
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key=\Yii::$app->security->generateRandomString();
                $model->save(0);
                return $this->redirect(['member/login']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('regist');
    }
    /**
     * ajax验证用户名是否存在
     */
    public function actionValidateUsername(){
        $result=Member::findOne(['username'=>\Yii::$app->request->get('username')]);
        if ($result){
            return 'false';
        }
        return 'true';
    }
    /**
     * 登录
     */
    public function actionLogin(){
        //登录表单
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收表单数据
            $model->load($request->post(),'');
            if($model->validate()){
                if($model->login()){
                   return $this->redirect(['goods/index']);
                    //var_dump(\Yii::$app->user->isGuest);
                }
            }
        }
        return $this->render('login');
    }
    /**
     * ajax验证用户名密码登录
     */
    public function actionLoginUsername(){
        $result=Member::findOne(['username'=>\Yii::$app->request->get('username')]);
        if ($result){
            return 'true';
        }
        return 'false';
        /*if($username){
            $result=\Yii::$app->security->validatePassword(\Yii::$app->request->get('password_hash'),$username->password_hash);
            if($result){
                return 'false';
            }
        }
        return 'true';*/
    }
    /**
     * ajax验证密码登录
     */
    public function actionLoginPassword(){
        $username=Member::findOne(['username'=>\Yii::$app->request->post('username')]);
        if($username){
            $result=\Yii::$app->security->validatePassword(\Yii::$app->request->post('password_hash'),$username->password_hash);
            if($result){
                return 'true';
            }
        }
        return 'false';
    }
    //测试
    public function actionTest(){
        $r=\Yii::$app->sms->setTel('18683478526')->setParams(['code'=>mt_rand(100000,999999)])->send();
        var_dump($r);
    }
    /**
     * 发短信
     */
    public function actionSms($tel){
        //保存验证码 mysql session redis
        $code=mt_rand(100000,999999);
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$tel,$code,30*60);
        $r=\Yii::$app->sms->setTel($tel)->setParams(['code'=>$code])->send();
//        var_dump($r);
        if($r){
            return 'success';
        }
        return 'fail';
    }
    /**
     * 验证短信验证码
     */
    public function actionValidateSms($tel,$code){
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $getCode=$redis->get('code_'.$tel);
        if($getCode==$code){
            return 'true';
        }
        return 'false';
    }
    /**
     * 退出登录
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->render('login');

    }
}
