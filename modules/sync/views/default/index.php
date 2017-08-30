<div class="sync-default-index">
    <h3>รายการคำสั่ง</h3>

    <?php

    use yii\helpers\Html;
    use kartik\grid\GridView;
    use yii\data\ArrayDataProvider;

$dataProvider = new ArrayDataProvider([
        'allModels' => $data
    ]);

    echo GridView::widget([
        'responsiveWrap' => FALSE,
        'options' => [ 'style' => 'table-layout: fixed; width: 100%' ],
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute'=>'title',
                'width'=>'40%',
            ],
            ['attribute'=>'table'],
            ['attribute'=>'note'],
            [
                'attribute' => 'sql',
                'width'=>'50%',
                //'contentOptions' => ['style' => 'min-width:180px;word-wrap: break-word;'],
            ],
            [
                'attribute'=>'update',
                'width'=>'60px',
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'width'=>'10%',
                'value' => function($model) {
                    $table = $model['table'];
                    $sql = $model['sql'];
                    if ($model['active'] == 1) {
                        return Html::a("ส่งข้อมูล", ['post', 'table' => $table, 'sql' => $sql], ['target' => '_blank', 'class' => 'btn btn-default']);
                    } else {
                        return 'close';
                    }
                }
                    ]
                ]
            ]);
            ?>
</div>
