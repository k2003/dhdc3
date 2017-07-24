<?php

namespace frontend\modules\sqlquery\controllers;

use yii\web\Controller;
use components\MyHelper;

/**
 * Default controller for the `sqlquery` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function behaviors() {
        return[
            'access' => [
                'class' => \yii\filters\AccessControl::className(), 
                'only'=>['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => MyHelper::modIsOn(),
                        'roles' => ['@'],
                    ],
                    
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->redirect(['runquery/index']);
    }
}
