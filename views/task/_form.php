<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'repeater')->textarea(['rows' => 6]) ?>

    <?=$form->field($model, 'entities_arr')->widget(\app\widgets\Select2::class, [
        'items' => \app\models\Entity::getList(),
        'options' => [
            'class' => 'form-control m-r',
            'style' => 'width: 100%',
            'multiple' => true,
        ],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
