<?php

namespace wskeee\filemanage\models;

use Yii;

/**
 * This is the model class for table "{{%filemanage_detail}}".
 *
 * @property integer $fm_id
 * @property string $content
 *
 * @property Filemanage $fm
 */
class FileManageDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanage_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fm_id'], 'integer'],
            [['content'], 'string']
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
            'content' => Yii::t('rcoa/fileManage', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFm()
    {
        return $this->hasOne(Filemanage::className(), ['id' => 'fm_id']);
    }
}
