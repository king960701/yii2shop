<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/7
 * Time: 16:17
 */

namespace backend\controllers;


use backend\models\PermissForm;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\HttpException;

class RbacController extends  Controller
{
    /**
     * 权限列表
     */
    public function actionPermissionIndex(){
        $authManager=\Yii::$app->authManager;
        //获取所有权限
        $permissions=$authManager->getPermissions();
//        var_dump($permissions);die;
        return $this->render('permission-index',['permissions'=>$permissions]);
    }
    /**
     * 添加权限
     */
    public function actionAddPermission(){
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_ADD;
        //场景
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //如果保存成功
                if($model->addPermission()){
                    \Yii::$app->session->setFlash('success','添加权限成功!');
                    return $this->redirect(['rbac/permission-index']);
                }
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    /**
     * 修改权限
     */
    public function actionEditPermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        if($permission==null){
            throw new HttpException(404,'权限已存在!');
        }
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_EDIT;
        $model->name=$permission->name;
        $model->description=$permission->description;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permission->name=$model->name;
                $permission->description=$model->description;
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['rbac/permission-index']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    /**
     * 删除权限
     */
    public function actionDeletePermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        $result=$authManager->remove($permission);
        if(!$result){
            return 'fail';
        }
        return 'success';
    }
    /**
     * 权限列表
     */
    public function actionRoleIndex(){
        $authManager=\Yii::$app->authManager;
        //获取所有角色
        $roles=$authManager->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }
    /**
     * 添加角色
     */
    public function actionAddRole(){
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->addRole()){
                \Yii::$app->session->setFlash('success','添加权限成功!');
                return $this->redirect(['rbac/role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    /**
     * 修改角色
     */
    public function actionEditRole($name){
        $request=\Yii::$app->request;
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_EDIT;
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        //回显
        $model->name=$role->name;
        $model->description=$role->description;
        //获取这个角色所有的权限
        /*$permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        $arr=[];
        foreach ($permissions as $permission){
            //遍历角色所有的权限
            $arr[]=$permission->name;
        }*/
        // 获取这个角色所有的权限 回显
        $model->permission= array_keys($authManager->getPermissionsByRole($name));

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate() && $model->editRole($role)){
                $role->name=$model->name;
                $role->description=$model->description;
                $authManager->update($name,$role);
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect(['rbac/role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    /**
     * 删除角色
     */
    public function actionDeleteRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        $result=$authManager->remove($role);
        if(!$result){
            return 'fail';
        }
        return 'success';
    }
}