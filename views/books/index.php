<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Borrow;
use app\models\Reserve;

$this->title = 'Seznam knih';

?>

    <div class="books-index ">

    <h1><?= Html::encode($this->title) ?></h1>



<?php

if (isset($_GET['action'])) {
    switch($_GET['action']) {
        case 'borrowed':
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => 'Kniha byla úspěšně půjčena.',
            ]);
            break;
        case 'reserved':
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => 'Kniha byla úspěšně rezervována. Jakmile ji uživatel před vámi vrátí, bude vám automaticky zapůjčena.',
            ]);
            break;
        case 'deleted':
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => 'Kniha byla úspěšně smazána.',
            ]);
            break;
    }
}

if(Yii::$app->user->isGuest) {

    echo GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'author',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{borrow}',
                'header' => 'Dostupnost',
                'buttons' => [
                    'borrow' => function ($model, $key) {
                        if ($key->borrowed == 1) {
                            return '<i class="bi bi-x-circle-fill" style="color: red"></i>';
                        } else {
                            return '<i class="bi bi-check-circle-fill" style="color: green"></i>';
                        }
                    }
                ],
            ]
        ]
    ]);

} else {

    ?>
    <p>
        <?= Html::a('Přidat knihu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php

    echo GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'author',
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Dostupnost',
                'template' => '{borrow}',
                'buttons' => [
                    'borrow' => function ($model, $key) {
                        if(Borrow::find()->where(['user_id' => Yii::$app->user->identity->id, 'book_id' => $key->id])->exists()) {
                            return 'Již máte vypůjčeno.';
                        } elseif (Reserve::find()->where(['user_id' => Yii::$app->user->identity->id, 'book_id' => $key->id])->exists()) {
                            return 'Rezervováno.';
                        } elseif ($key->borrowed == 1) {
                             if ($key->reserved == 1) {
                                return '<i class="bi bi-x-circle-fill" style="color: red"></i>';
                             } else {
                                return Html::beginForm(['/books/reserve', 'id' => $key->id], 'post') .
                                    Html::submitButton(
                                        'Rezervovat',
                                        ['class' => 'submit']
                                    ) .
                                    Html::endForm();
                            }
                        } else {
                            return Html::beginForm(['/books/borrow', 'id' => $key->id], 'post') .
                                Html::submitButton(
                                    'Půjčit',
                                    ['class' => 'submit']
                                ) .
                                Html::endForm();
                        }
                    }
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Akce',
            ]
        ]
    ]);


}