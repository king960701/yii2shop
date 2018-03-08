<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id 上级菜单
 * @property string $url 地址/路由
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
        ];
    }
    public static function getPermissionNames(){
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        $arr=[];
        foreach($permissions as $v){
            $arr[$v->name]=$v->name;
        }
        //var_dump($arr);die;
        array_unshift($arr,'--请选择路由--');
        //var_dump($arr);die;
        return $arr;
    }
    public static function getParent(){
        $menus=self::find()->where(['parent_id'=>0])->all();
        $arr[]='--顶级菜单--';
        foreach ($menus as $menu){
            $arr[$menu->id]=$menu->name;
        }
        //array_unshift($result,'--顶级菜单--');
        //var_dump($result);die;
        return $arr;
    }
    public static function getAllMenus($menuItems){
        $menus=self::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $items=[];
            $children=self::find()->where(['parent_id'=>$menu->id])->all();
            foreach ($children as $child) {
                //只添加有权限的二级菜单
                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            //只显示有子菜单的一级菜单
            if($items){
                $menuItems[]=['label'=>$menu->name,'items'=>$items];
            }
        }
        //var_dump($menuItems);die;
        return $menuItems;

    }
}
