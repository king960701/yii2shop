<?php
//使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission',['inline'=>1])->checkboxList(\backend\models\RoleForm::getPermissionNames());
echo '<button type="submit" class="btn btn-info">提交</button>';


\yii\bootstrap\ActiveForm::end();