<?php

namespace wskeee\filemanage\models;

use wskeee\rbac\models\AuthItem;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%filemanage_owner}}".
 *
 * @property integer $id
 * @property integer $fm_id
 * @property string $owner
 *
 * @property Filemanage $fm
 * @property AuthItem $owner0
 */
class FileManageOwner extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanage_owner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fm_id'], 'integer'],
            [['fm_id', 'owner'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'fm_id' => Yii::t('rcoa/fileManage', 'Fm ID'),
            'owner' => Yii::t('rcoa/fileManage', 'Owner'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFm()
    {
        return $this->hasOne(Filemanage::className(), ['id' => 'fm_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOwner0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'owner']);
    }
}
