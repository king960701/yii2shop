<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();

echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'admin/captcha',
    'template'=>'<div class="row"><div class="col-xs-1">{input}</div><div class="col-xs-1">{image}</div></div> '
]);
echo $form->field($model, 'rememberMe')->checkbox();
echo '<button type="submit" class="btn btn-primary">登录</button>';

\yii\bootstrap\ActiveForm::end();