<?php
$form = \yii\bootstrap\ActiveForm::begin();
//echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_old')->passwordInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'password2')->passwordInput();
//echo $form->field($model,'email')->textInput();
    echo '<button type="submit" class="btn btn-info">确认修改</button>';

\yii\bootstrap\ActiveForm::end();