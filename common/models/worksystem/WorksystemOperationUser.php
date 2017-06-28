<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_operation_user}}".
 *
 * @property integer $id                                    id
 * @property integer $worksystem_operation_id               引用工作系统操作记录id
 * @property string $user_id                                引用用户id：操作用户
 * @property integer $brace_mark                            支撑标识：0标识不支撑，1表示支撑
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 *
 * @property WorksystemOperation $worksystemOperation       获取工作系统操作记录
 */
class WorksystemOperationUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_operation_user}}';
    }
    
    public function behaviors() 
    {
        return [
            TimestampBehavior::className()
        ];
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
     * 获取工作系统操作记录
     * @return ActiveQuery
     */
    public function getWorksystemOperation()
    {
        return $this->hasOne(WorksystemOperation::className(), ['id' => 'worksystem_operation_id']);
    }
}
