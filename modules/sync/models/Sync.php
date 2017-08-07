<?php

namespace modules\sync\models;

use Yii;

/**
 * This is the model class for table "dhdc_sync_command".
 *
 * @property integer $id
 * @property string $title
 * @property string $ddl
 * @property string $sql
 * @property string $table_recieve
 * @property string $note1
 * @property string $note2
 * @property string $note3
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class Sync extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dhdc_sync_command';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['ddl', 'sql'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'table_recieve', 'note1', 'note2', 'note3', 'created_by', 'updated_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'ddl' => 'Ddl',
            'sql' => 'Sql',
            'table_recieve' => 'Table Recieve',
            'note1' => 'Note1',
            'note2' => 'Note2',
            'note3' => 'Note3',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
