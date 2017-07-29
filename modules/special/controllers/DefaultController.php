<?php

namespace modules\special\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Default controller for the `special` module
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['User']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

}
