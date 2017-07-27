<?php

namespace modules\gis\controllers;

use yii\web\Controller;
use modules\gis\models\GisDhdcTambon;
use components\MyHelper;
use yii\filters\AccessControl;

/**
 * Default controller for the `mapbox` module
 */
class DefaultController extends Controller {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['map'],
                'rules' => [

                    [
                        //'actions' => ['map',],
                        'allow' => true,
                        'roles' => ['User'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionPointHome() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sql = "SELECT t.HOSPCODE,t.HID
,concat(t.CHANGWAT,t.AMPUR,t.TAMBON) TAMBON
,concat(t.CHANGWAT,t.AMPUR,t.TAMBON,t.VILLAGE) VILLAGE
,t.HOUSE ,t.LATITUDE,t.LONGITUDE
FROM home t WHERE t.LATITUDE*1 > 0 AND t.LONGITUDE*1 > 0";

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

        return json_encode($home_point);
    }

    public function actionMap() {
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

        return $this->renderPartial('map', [
                    'tambon_pol' => $tambon_pol
        ]);
    }

}
