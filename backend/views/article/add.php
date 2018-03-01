<?php
//使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Article::getCategory());
echo $form->field($detail,'content')->textarea();
echo $form->field($model,'sort')->textInput();
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-info">添加</button>';
}else{
    echo '<button type="submit" class="btn btn-info">更新</button>';
}
\yii\bootstrap\ActiveForm::end();