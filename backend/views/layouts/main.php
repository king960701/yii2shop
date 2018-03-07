<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems[] = [
        'label' => '品牌管理' ,'url' => ['/brand/index']
    ];
    $menuItems[] = [
        'label' => '文章管理' ,'url' => ['/article/index']
    ];
    $menuItems[] = [
        'label' => '商品管理' ,'url' => ['/goods/index']
    ];
    $menuItems[] = [
        'label' => 'RBAC','url' => ['#'],
        'items' => [
            ['label' => '权限列表', 'url' => ['/rbac/permission-index']],
            ['label'=> '角色列表','url' => ['/rbac/role-index']],
        ],
    ];
    $menuItems[] = [
        'label' => '用户管理' ,'url' => ['/admin/index']
    ];
    $menuItems[] = [
        'label' => '菜单管理' ,'url' => ['/menu/index']
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
    } else {
        $menuItems[] = [
            'label' => '用户 ' . Yii::$app->user->identity->username . '',
            'items' => [
                ['label' => '修改密码', 'url' => ['/admin/edit']],
                ['label'=> '退出登录','url' => ['admin/logout']],
            ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
