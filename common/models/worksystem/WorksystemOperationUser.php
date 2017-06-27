<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_operation_user}}".
 *
 * @property integer $id
 * @property integer $worksystem_operation_id
 * @property string $user_id
 * @property integer $brace_mark
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WorksystemOperation $worksystemOperation
 */
class WorksystemOperationUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_operation_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_operation_id', 'brace_mark', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 36],
            [['worksystem_operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemOperation::className(), 'targetAttribute' => ['worksystem_operation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_operation_id' => Yii::t('rcoa/worksystem', 'Worksystem Operation ID'),
            'user_id' => Yii::t('rcoa/worksystem', 'User ID'),
            'brace_mark' => Yii::t('rcoa/worksystem', 'Brace Mark'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemOperation()
    {
        return $this->hasOne(WorksystemOperation::className(), ['id' => 'worksystem_operation_id']);
    }
}
