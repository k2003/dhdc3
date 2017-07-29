<?php

namespace components;

use yii\base\Component;
use common\models\config\SysConfigMain;
use backend\modules\pluginsetup\models\SysDhdcPlugin;
use dektrium\user\models\User;
use common\models\config\SysOnoffUpload;

class MyHelper extends Component {

    public static function setFlash($key = 'danger', $msg) {
        return \Yii::$app->session->setFlash($key, $msg);
    }

    public static function user_can($permissionName = 'User') {
        return \Yii::$app->user->can($permissionName);
    }

    public static function user_can_own($data_hoscode = NULL) {
        return \Yii::$app->user->can('OnlyOwnHos', ['data_hoscode' => $data_hoscode]);
    }

    public static function exec_sql($sql = NULL) {
        return \Yii::$app->db->createCommand($sql)->execute();
    }

    public static function query_all($sql = NULL) {
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function query_one($sql = NULL) {
        return \Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function getSysConfig() {
        return SysConfigMain::find()->one();
    }

    public static function overclock($memory = '2048M') {
        ini_set('memory_limit', $memory);
    }

    protected function getModName() {
        return \Yii::$app->controller->module->id;
    }

    public static function modIsOn() {
        $mod_name = self::getModName();
        $m = SysDhdcPlugin::find()->where(['mod_name' => $mod_name, 'status' => 'on'])->one();
        if ($m) {
            return TRUE;
        }
        return FALSE;
    }

    public static function getUserHoscode($user_id) {
        $user = User::findOne($user_id);
        if ($user) {
            return $user->profile->location;
        }
    }

    public static function uploadOn() {
        $m = SysOnoffUpload::find()->one();
        return $m->status == 'on';
    }

}
