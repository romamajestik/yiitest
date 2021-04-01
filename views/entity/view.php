<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="entity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'file',
            [
                'format' => 'html',
                'attribute' => 'user_id',
                'value' => function($model) {
                    /** @var $model \app\models\Entity */
                    return $model->user->username;
                }
            ],
            [
                'format' => 'html',
                'attribute' => 'tasks_arr',
                'value' => function($model) {
                    /** @var $model \app\models\Entity */
                    return Html::tag('pre', implode("\n", \yii\helpers\ArrayHelper::map($model->tasks, 'id', 'name')));
                }
            ],
            'created_at',
        ],
    ]) ?>

</div>
