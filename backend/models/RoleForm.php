<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/7
 * Time: 16:48
 */

namespace backend\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permission;
    //场景
    const SCENARIO_ADD='add';
    const SCENARIO_EDIT='edit';
    public function rules()
    {
        return [
            [['name','description','permission'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','changName','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'permission'=>'权限',
            'description'=>'描述',
        ];
    }
    public function validateName(){
        $authManager=\Yii::$app->authManager;
        if($authManager->getRole($this->name)){
            //橘色已经存在
            $this->addError('name','角色已经存在!');
        }
    }
    public function changName(){
        $authManager=\Yii::$app->authManager;

        if(\Yii::$app->request->get('name') != $this->name){
            $this->validateName();
        }
    }
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //创建角色
        $role=$authManager->createRole($this->name);
        $role->description=$this->description;
//        var_dump($role);
//        var_dump($this->permission);
//        die;\
        $permission=$this->permission;
        $result=$authManager->add($role);
        if($result){
          //成功添加
            foreach ($permission as $value){
                //遍历保存
                $chilid=$authManager->getPermission($value);
                $authManager->addChild($role,$chilid);
            }
            return true;
        }
        return false;
    }
    /**
     * 获取权限选项
     */
    public static function getPermissionNames(){
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        $arr=[];
        foreach($permissions as $v){
            $arr[$v->name]=$v->description;
        }
        //var_dump($arr);die;
        return $arr;
    }
    /**
     * 修改角色
     */
    public function editRole($role){
        $authManager=\Yii::$app->authManager;
        //先删除原来所有的权限关联
        $authManager->removeChildren($role);
        $permission=$this->permission;
        if (!$permission==null){
            foreach ($permission as $value){
                //遍历保存
                $chilid=$authManager->getPermission($value);
                $authManager->addChild($role,$chilid);
            }
        }
        return true;
    }
}