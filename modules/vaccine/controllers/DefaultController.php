<?php

namespace modules\vaccine\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Default controller for the `vaccine` module
 */
class DefaultController extends Controller
{
    public function behaviors() {
        return [
            'acess'=>[
                'class'=>  AccessControl::className(),
                'rules' => [
                    [                        
                        'allow' => true,
                        'roles' => ['User'],
                    ],
                ],
                
            ]
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
}
