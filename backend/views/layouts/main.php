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
        'brandLabel' => 'Администратор',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        $menuItems = [
            ['label' => 'Журнал звонков', 'url' => ['/site/index']],
            [
                'label' => 'Очереди', 
                'url' => ['/queue/index'],
                'items' => [
                    [
                        'label' => 'Список очередей',
                        'url' => ['/queue/index'],
                    ],
                    [
                        'label' => 'Добавить новую очередь',
                        'url' => ['/queue/form'],
                    ],
                ]
            ],
            [
                'label' => 'Операторы', 
                'url' => ['/operator/index'],

                'items' => [
                    [
                        'label' => 'Список операторов',
                        'url' => ['/operator/index'],
                    ],
                    [
                        'label' => 'Добавить нового оператора',
                        'url' => ['/operator/form'],
                    ],
                ],
            ],
        ];
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    
    echo Nav::widget([
        'activateItems' => true,
        'activateParents' => true,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        
    ]);
    NavBar::end();
    ?>

    <div class="container">
        
        <?php
            if (!Yii::$app->user->isGuest) {
                echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
            }
        ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Центр Звонков <?= date('Y')?></p>

        <p class="pull-right"><?= 'Разработка и сопровождение ПО <a href="http://itwww.ru">itwww.ru</a>' ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
