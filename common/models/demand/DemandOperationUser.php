<?php

namespace common\models\demand;

use Yii;

/**
 * This is the model class for table "{{%demand_operation_user}}".
 *
 * @property integer $operation_id
 * @property integer $u_id
 *
 * @property DemandOperation $operation
 */
class DemandOperationUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_operation_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_id', 'u_id'], 'required'],
            [['operation_id', 'u_id'], 'integer'],
            [['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandOperation::className(), 'targetAttribute' => ['operation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'operation_id' => Yii::t('rcoa/demand', 'Operation ID'),
            'u_id' => Yii::t('rcoa/demand', 'U ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperation()
    {
        return $this->hasOne(DemandOperation::className(), ['id' => 'operation_id']);
    }
}
