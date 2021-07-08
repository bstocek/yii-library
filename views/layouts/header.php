<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Seznam knih', 'url' => Url::to(['/books/'])],
        Yii::$app->user->isGuest ? (
        ['label' => 'Přihlásit se', 'url' => Url::to(['/site/login'])]
        ) : (
            ['label' => Yii::$app->user->identity->username,
                'items' => [
                    ['label' => 'Moje výpůjčky', 'url' => Url::to(['/books/borrowed?id='.Yii::$app->user->identity->id])],
                    ['label' => 'Moje rezervace', 'url' => Url::to(['/books/reserved?id='.Yii::$app->user->identity->id])],
                    '<li><hr class="dropdown-divider"></li>
                    <li>' . Html::a('Odhlásit se', ['/site/logout'], ['data-method' => 'post']).'</li>'
                ],
            ]
        )
    ],
]);
NavBar::end();
?>