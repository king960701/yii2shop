<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/4
 * Time: 16:15
 */

namespace backend\models;


use yii\base\Model;

class PasswordForm extends Model
{
    public $password_old;
    public $password;
    public $password2;
    //修改验证
    public function check($id){
        $admin=Admin::findOne(['id'=>$id]);
        if($this->password==$this->password2){
            //两次输入密码相同
//            var_dump($admin->password_hash);
//            var_dump($this->password_old);die;
            if (\Yii::$app->security->validatePassword($this->password_old,$admin->password_hash)){
                //登录成功保存登录时间 ip
                $admin->updated_at=time();
                $admin->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
                //$admin->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $admin->save();
                return true;
            }else{
                //密码错误 设置错误信息
                $this->addError('password_old','旧密码错误');
            }
        }else{
            $this->addError('password2','再次输入密码不相同');
        }
        return false;
    }
    public function attributeLabels(){
        return [
            'password'=>'新密码',
            'password2'=>'确认密码',
            'password_old'=>'旧密码',
        ];
    }
    public function rules(){
        return [
            [['password','password2','password_old'],'required'],
        ];
    }
}