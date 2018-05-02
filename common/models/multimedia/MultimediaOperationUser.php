<?php

namespace common\models\multimedia;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_operation_user}}".
 *
 * @property integer $operation_id                  操作记录ID
 * @property string $u_id                           用户ID
 * @property integer $brace_mark                    支撑标识
 *
 * @property MultimediaOperation $operation         获取操作记录
 */
class MultimediaOperationUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_operation_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_id', 'brace_mark'], 'integer'],
            [['u_id'], 'string', 'max' => 36],
            [['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaOperation::className(), 'targetAttribute' => ['operation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'operation_id' => Yii::t('rcoa/multimedia', 'Operation ID'),
            'u_id' => Yii::t('rcoa/multimedia', 'U ID'),
            'brace_mark' => Yii::t('rcoa/multimedia', 'Brace Mark'),
        ];
    }

    /**
     * 获取操作记录
     * @return ActiveQuery
     */
    public function getOperation()
    {
        return $this->hasOne(MultimediaOperation::className(), ['id' => 'operation_id']);
    }
}
