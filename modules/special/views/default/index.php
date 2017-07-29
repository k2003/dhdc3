<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

$this->params['breadcrumbs'][] = 'ตรวจสอบประวัติ SpecialPP';
?>
<div class="vaccine-default-index">
    <div class="panel panel-danger">
        <div class="panel-heading">ตรวจสอบ</div>
        <div class="panel-body">
            <?php
            $cid = trim(\Yii::$app->request->post('cid'));
            
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::textInput('cid', $cid, ['placeholder' => 'เลข 13 หลัก']) ?>
                    <?= Html::submitButton('ค้นหา'); ?>
                    <?php ActiveForm::end(); ?>
                </div>                
            </div>
            <br>
            <?php if ($cid): ?>    
                ผลการค้นหาด้วย 13 หลัก
                <?php
                $sql = "SELECT p.CID,t.* from specialpp t
LEFT JOIN t_person_cid p on p.HOSPCODE = t.HOSPCODE AND p.PID = t.PID
WHERE trim(t.HOSPCODE) <> '' AND trim(t.PID) <> ''
AND p.CID = '$cid'
ORDER BY t.DATE_SERV";
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
