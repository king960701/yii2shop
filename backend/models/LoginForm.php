<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/4
 * Time: 11:31
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $rememberMe;
    public function attributeLabels(){
        return [
          'username'=>'用户名',
          'password_hash'=>'密码',
          'code'=>'验证码',
          'rememberMe'=>'记住我',
        ];
    }
    public function rules(){
        return [
            [['username','password_hash','code'],'required'],
            ['code','captcha','captchaAction'=>'admin/captcha'],
            ['rememberMe','safe'],
        ];
    }
    //登录方法
    public function login(){
        $admin=Admin::findOne(['username'=>$this->username]);
        if($admin){
            //账号存在
            if (\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                //登录成功保存登录时间 ip
                $admin->last_login_time=time();
                $admin->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $admin->save();
                $duration=$this->rememberMe?3600*24*7:0;
                //密码正确 保存session
                return \Yii::$app->user->login($admin,$duration);
            }else{
                //密码错误 设置错误信息
                $this->addError('password_hash','密码错误');
            }
        }else{
            //账号不存在
            $this->addError('username','账号不存在');
        }
        return false;
    }

}