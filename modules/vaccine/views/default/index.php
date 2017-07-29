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
            $bdate = trim(\Yii::$app->request->post('bdate'));
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::textInput('cid', $cid, ['placeholder' => 'เลข 13 หลัก']) ?>
                    <?= Html::submitButton('ค้นหา'); ?>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin(); ?>
                    เกิดระหว่าง:
                    <?= Html::textInput('bdate_begin', '', ['placeholder' => 'วดป.เกิด']) ?>
                    -<?= Html::textInput('bdate_end', '', ['placeholder' => 'วดป.เกิด']) ?>
                    <?= Html::hiddenInput('bdate', 'yes') ?>
                    <?= Html::submitButton('ค้นหา'); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <br>
            <?php if ($cid): ?>    
                ผลการค้นหาด้วย 13 หลัก
                <?php
                $sql = "SELECT p.`NAME`,p.LNAME,p.BIRTH,p.HOSPCODE,p.CID,p.TYPEAREA,p.DISCHARGE
,t.* from t_person_epi t LEFT JOIN t_person_cid p on p.CID = t.cid  where p.CID ='$cid'";
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

            <?php if ($bdate): ?>
                ผลการค้นหาด้วย วดป.เกิด:
            <?php endif; ?>
        </div>
    </div>
</div>
