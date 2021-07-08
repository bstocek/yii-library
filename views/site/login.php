<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<div class="site-login container">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>Prosím vyplňte následující údaje k přihlášení:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Uživatelské jméno') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Heslo') ?>


        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Přihlásit se', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        Můžete se přihlásit s <strong>Adam/adam</strong>, <strong>Eva/eva</strong> nebo <strong>Jan/jan</strong>.<br>
        <!--To modify the username/password, please check out the code <code>app\models\User::$users</code>.-->
    </div>
</div>
