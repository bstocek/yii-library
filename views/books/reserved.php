<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

$this->title = 'Rezervované knihy';


?>

<div class="container">
    <h2>Rezervované knihy</h2>

    <?php
    if (isset($_GET['action'])) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-info',
            ],
            'body' => 'Rezervace byla zrušena.',
        ]);
    }


    echo GridView::widget([
        'dataProvider' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'author',
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Akce',
                'template' => '{return}',
                'buttons' => [

                    'return' => function ($model, $key) {

                        if ($key->reserved != 1) {
                            return '<i class="bi bi-x-circle-fill" style="color: red"></i>';
                        } else {
                            return Html::beginForm(['/books/cancel-reservation', 'id' => $key->id], 'post') .
                                Html::submitButton(
                                    'Zrušit',
                                    ['class' => 'submit']
                                ) .
                                Html::endForm();
                        }

                    }

                ],

            ]
        ]
    ]);


    ?>
</div>