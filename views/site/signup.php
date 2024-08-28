<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <div class="row">
        <div class="container-signup container-txt">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
				'method'=>'post',
            ]); ?>

            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
			
		
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
			
			
			
        </div>
    </div>
</div>
