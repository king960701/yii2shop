<?php
//使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getParent());
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getPermissionNames());
echo $form->field($model,'sort')->textInput(['value'=>1]);
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-info">添加</button>';
}else{
    echo '<button type="submit" class="btn btn-info">更新</button>';
}
\yii\bootstrap\ActiveForm::end();