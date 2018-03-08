<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/7
 * Time: 16:18
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;
    //场景
    const SCENARIO_ADD='add';
    const SCENARIO_EDIT='edit';
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','changName','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名(路由)',
            'description'=>'权限描述',
        ];
    }
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        //创建权限 使用路由名称
        $permission=$authManager->createPermission($this->name);
        $permission->description=$this->description;
        //保存到数据表
        return $authManager->add($permission);
    }
    public function validateName(){
        $authManager=\Yii::$app->authManager;
        //获取权限
        if($authManager->getPermission($this->name)){
            //权限已经存在
            $this->addError('name','权限已经存在不能添加!');
        }
    }
    public function changName(){
        if(\Yii::$app->request->get('name') != $this->name){
            $this->validateName();
        }
    }
}