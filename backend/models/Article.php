<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $intro 简介
 * @property int $article_category_id 文章分类id
 * @property int $sort 排序
 * @property int $is_deleted 状态
 * @property int $create_time 创建时间
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'article_category_id', 'sort'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'is_deleted', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
        ];
    }
    //获取选项
    public static function getCategory(){
        $category=ArticleCategory::find()->asArray()->all();
        return ArrayHelper::map($category,'id','name');
    }
    //关联表查询
    public function getCategoryName(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}
