<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model modules\sync\models\Sync */

$this->title = 'Create Sync';
$this->params['breadcrumbs'][] = ['label' => 'Syncs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sync-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
