<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\sync\models\Sync */

$this->title = 'Update Sync: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Syncs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sync-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
