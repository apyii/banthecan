<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

if ($model->hasDecorations()) {
    echo $this->render('@frontend/views/ticket/partials/single/_ticketSingleDecorations', [
            'model' => $model,
        ]
    );
}

echo Html::a('Move', '/ticket/move/' . $model->id, [
        'class' => 'btn btn-danger hidden-sm hidden-md- hidden-lg',
        'style' => 'width: 100%;'
    ]);

?>