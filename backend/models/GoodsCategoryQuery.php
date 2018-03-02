<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/1
 * Time: 11:39
 */

namespace backend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class GoodsCategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}