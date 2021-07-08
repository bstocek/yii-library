<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

$this->title = 'Vypůjčené knihy';


?>

<div class="container">
    <h2>Vypůjčené knihy</h2>

    <?php
    if (isset($_GET['action'])) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-info',
            ],
            'body' => 'Kniha byla úspěšně vrácena.',
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

                        if ($key->borrowed != 1) {
                            return '<i class="bi bi-x-circle-fill" style="color: red"></i>';
                        } else {
                            return Html::beginForm(['/books/return', 'id' => $key->id], 'post') .
                                Html::submitButton(
                                    'Vrátit',
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