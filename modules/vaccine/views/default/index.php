<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

$this->params['breadcrumbs'][] = 'ตรวจสอบประวัติวัคซีน';
?>
<div class="vaccine-default-index">
    <div class="panel panel-danger">
        <div class="panel-heading">ตรวจสอบ</div>
        <div class="panel-body">
            <?php
            $cid = trim(\Yii::$app->request->post('cid'));
            ?>
            <?php $form = ActiveForm::begin(); ?>
            <?= Html::textInput('cid', $cid, ['placeholder' => 'กรุณาใส่เลข 13 หลัก']) ?>
            <?= Html::submitButton('ค้นหา'); ?>

            <?php ActiveForm::end(); ?>
            <br>
            <?php if ($cid): ?>
                <?php
                $sql = "select * from t_person_epi where cid = '$cid'";
                try {
                    $raw = \Yii::$app->db->createCommand($sql)->queryAll();
                } catch (\yii\db\Exception $e) {
                    throw new yii\web\ForbiddenHttpException('sql error');
                }
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $raw
                ]);
                echo GridView::widget([
                    'responsiveWrap' => false,
                    'dataProvider' => $dataProvider
                ]);
                ?>

            <?php endif; ?>
        </div>
    </div>
</div>
