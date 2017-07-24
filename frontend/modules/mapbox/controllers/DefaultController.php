<?php

namespace frontend\modules\mapbox\controllers;

use yii\web\Controller;
use frontend\modules\mapbox\models\GisDhdcTambon;
use components\MyHelper;

/**
 * Default controller for the `mapbox` module
 */
class DefaultController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionPointHome() {
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $home_point[] = [
            'type' => 'Feature',
            'properties' => [
                'NAME' => 'นาย ก.',
                'marker-color' => "#00ff00", //สี
                "marker-size" => "large", //ขนาด
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [99.9124456, 16.14523]
            ]
        ];
        $home_point[] = [
            'type' => 'Feature',
            'properties' => [
                'NAME' => 'นาย ข.',
                'marker-color' => "#3399ff", //สี
                "marker-size" => "large", //ขนาด
                //'title' => 'ไตเติ้ล',
                'description' => 'รายละเอียด....',
                "marker-symbol" => "h"//สัญลักษณ์
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [100.1124456, 16.04523]
            ]
        ];

        return json_encode($home_point);
    }

    public function actionMap() {


        $peron_point[] = [
            'type' => 'Feature',
            'properties' => [
                'NAME' => 'นาย ก.',
                'title' => 'ไตเติ้ล',
                'description' => 'รายละเอียด....',
                'marker-color' => "#fc4353", //สี
                "marker-size" => "large", //ขนาด
                "marker-symbol" => "p"//สัญลักษณ์
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [100.0124456, 16.14523]
            ]
        ];
        $peron_point = json_encode($peron_point);
        return $this->renderPartial('map', [
                    'person_point' => $peron_point,
        ]);
    }

    public function actionTambon() {
        MyHelper::overclock();
        $sys = MyHelper::getSysConfig();
        if ($sys) {
            $amp = $sys->district_code;
            $model = GisDhdcTambon::find()->where(['=', 'concat(PROV_CODE,AMP_CODE)', $amp])->all();
        } else {
            $model = GisDhdcTambon::find()->where(['=', 'PROV_CODE', '10'])->all();
        }

        $tambon_pol = [];
        foreach ($model as $value) {
            $tambon_pol[] = [
                'type' => 'Feature',
                'properties' => [
                    'fill' => call_user_func(function()use($value) {
                                if ($value->TAM_CODE % 2 == 0)
                                    return '#4169e1';
                                if ($value->TAM_CODE % 3 == 0)
                                    return '#ffd700';
                                return '#00ff7f';
                            }),
                    'title' => "ต." . $value['TAM_NAMT'],
                ],
                'geometry' => [
                    'type' => 'MultiPolygon',
                    'coordinates' => json_decode($value['COORDINATES']),
                ]
            ];
        }
        $tambon_pol = json_encode($tambon_pol);

        return $this->renderPartial('tambon', [
                    'tambon_pol' => $tambon_pol
        ]);
    }

}
