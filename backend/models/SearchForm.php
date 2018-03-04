<?php

namespace backend\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $name;
    public $sn;
    //验证规则
    public function rules()
    {
        return [
            [['name','sn'],'safe'],
        ];
    }
}