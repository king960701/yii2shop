<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/7
 * Time: 14:37
 */
namespace backend\filters;
use yii\web\HttpException;

class RbacFilter extends \yii\base\ActionFilter
{
    //控制器动作执行前
    public function beforeAction($action){
        /*return true; //放行
        return false;   //拦截*/
        if(!\Yii::$app->user->can($action->uniqueId)){//如果没有
            //如果没有登录显示错误信息
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException(403,'您没有权限操作!');
        }
        return true;
    }
}