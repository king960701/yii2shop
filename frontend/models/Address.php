<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $member_id 当前登录人
 * @property string $name 姓名
 * @property int $tel 电话
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县
 * @property string $address 详细地址
 * @property int $status 状态
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'province', 'city', 'county', 'address'], 'required'],
            [['member_id', 'tel'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'county', 'address'], 'string', 'max' => 255],
            ['status','safe'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '当前登录人',
            'name' => '姓名',
            'tel' => '电话',
            'province' => '省',
            'city' => '市',
            'county' => '县',
            'address' => '详细地址',
            'status' => '状态',
        ];
    }
}
