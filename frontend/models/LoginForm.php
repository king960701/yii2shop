<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/9
 * Time: 14:46
 */

namespace frontend\models;


use app\models\Cart;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $captcha;
    public $rememberMe;
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            ['rememberMe','safe'],
            ['captcha','captcha','captchaAction'=>'site/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
        ];
    }
    //登录方法
    public function login(){
        $member=Member::findOne(['username'=>$this->username]);
        if($member){
            //账号存在
            if (\Yii::$app->security->validatePassword($this->password_hash,$member->password_hash)){
                //登录成功保存登录时间 ip
                $member->save(0);
                $duration=$this->rememberMe?3600*24*7:0;
                //获取cookie中的购物车
                $cookies=\Yii::$app->request->cookies;
                $carts=unserialize($cookies->getValue('carts'));
                //var_dump($carts);die;
                $model=new Cart();
                if($carts){
                    foreach ($carts as $goods_id=>$amount){
                        $result=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member->id]);
                        if($result){
                            $result->amount+=$amount;
                            $result->save();
                        }else{
                            $model->amount=$amount;
                            $model->goods_id=$goods_id;
                            $model->member_id=$member->id;
                            $model->save();
                        }
                    }
                    $cookie=\Yii::$app->response->cookies;
                    $cookie->remove('carts',true);
                }

                //密码正确 保存session
                return \Yii::$app->user->login($member,$duration);
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
    public function beforeSave($insert){
        if($insert){
            $this->created_at=time();
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at=time();
        }

        return parent::beforeSave($insert);
    }
}