<?php
//使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
if($model->getIsNewRecord()){
    echo $form->field($model,'password_hash')->passwordInput();
}
echo $form->field($model,'email')->textInput();
echo $form->field($model,'role',['inline'=>1])->checkboxList(\backend\models\Admin::getRoles());
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-info">添加</button>';
}else{
    echo '<button type="submit" class="btn btn-info">更新</button>';
}
\yii\bootstrap\ActiveForm::end();