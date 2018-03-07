<?php

namespace backend\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $name;
    public $sn;
    public $min;
    public $max;

    //验证规则
    public function rules()
    {
        return [
            [['name','sn','min','max'],'safe'],
        ];
    }
}