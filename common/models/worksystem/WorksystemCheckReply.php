<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_check_reply}}".
 *
 * @property integer $id
 * @property integer $worksystem_check_id
 * @property string $title
 * @property integer $pass
 * @property string $des
 * @property string $create_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WorksystemCheck $worksystemCheck
 */
class WorksystemCheckReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_check_reply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_check_id', 'pass', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['worksystem_check_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemCheck::className(), 'targetAttribute' => ['worksystem_check_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_check_id' => Yii::t('rcoa/worksystem', 'Worksystem Check ID'),
            'title' => Yii::t('rcoa/worksystem', 'Title'),
            'pass' => Yii::t('rcoa/worksystem', 'Pass'),
            'des' => Yii::t('rcoa/worksystem', 'Des'),
            'create_by' => Yii::t('rcoa/worksystem', 'Create By'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemCheck()
    {
        return $this->hasOne(WorksystemCheck::className(), ['id' => 'worksystem_check_id']);
    }
}
