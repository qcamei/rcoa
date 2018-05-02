<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "rms_sys_data".
 *
 * @property string $SYS_DATA_ID
 * @property string $TYPE_NAME
 * @property string $TYPE_CODE
 * @property string $NAME
 * @property string $CODE
 * @property string $DATA_DESC
 * @property string $ORD_NO
 * @property string $REMARK
 * @property string $CREATE_DATE
 * @property string $DELETE_DATE
 * @property string $IS_DEFAULT
 * @property string $IS_READONLY
 * @property string $IS_DELETED
 * @property string $VERSION_NO
 */
class RmsSysData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rms_sys_data';
    }

    /**
     * @return Connection the database connection used by this AR class.
     */
    /*public static function getDb()
    {
        return Yii::$app->get('rmsdb');
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SYS_DATA_ID', 'TYPE_NAME', 'TYPE_CODE', 'NAME', 'CODE'], 'required'],
            [['DATA_DESC', 'REMARK'], 'string'],
            [['ORD_NO'], 'number'],
            [['CREATE_DATE', 'DELETE_DATE'], 'safe'],
            [['SYS_DATA_ID', 'TYPE_CODE', 'CODE'], 'string', 'max' => 32],
            [['TYPE_NAME', 'NAME'], 'string', 'max' => 128],
            [['IS_DEFAULT', 'IS_READONLY', 'IS_DELETED', 'VERSION_NO'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SYS_DATA_ID' => Yii::t('app', 'Sys  Data  ID'),
            'TYPE_NAME' => Yii::t('app', 'Type  Name'),
            'TYPE_CODE' => Yii::t('app', 'Type  Code'),
            'NAME' => Yii::t('app', 'Name'),
            'CODE' => Yii::t('app', 'Code'),
            'DATA_DESC' => Yii::t('app', 'Data  Desc'),
            'ORD_NO' => Yii::t('app', 'Ord  No'),
            'REMARK' => Yii::t('app', 'Remark'),
            'CREATE_DATE' => Yii::t('app', 'Create  Date'),
            'DELETE_DATE' => Yii::t('app', 'Delete  Date'),
            'IS_DEFAULT' => Yii::t('app', 'Is  Default'),
            'IS_READONLY' => Yii::t('app', 'Is  Readonly'),
            'IS_DELETED' => Yii::t('app', 'Is  Deleted'),
            'VERSION_NO' => Yii::t('app', 'Version  No'),
        ];
    }
    
}
