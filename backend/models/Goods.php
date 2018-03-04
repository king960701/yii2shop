<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 货号
 * @property string $logo LOGO图片
 * @property int $goods_category_id LOGO图片
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property string $stock 库存
 * @property int $is_on_sale 是否在售
 * @property int $status 状态
 * @property int $sort 排序
 * @property int $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sn', 'goods_category_id', 'brand_id', 'market_price', 'shop_price'], 'required'],
            [['goods_category_id', 'brand_id', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times', 'stock'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }

    //获取选项
    public static function getBrandName(){
        $brand=Brand::find()->asArray()->all();
        $result=ArrayHelper::map($brand,'id','name');
        array_unshift($result,'--请选择商品分类--');
        return $result;
    }
    //关联表查询
    public function getCategoryName(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //关联表查询
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
}
