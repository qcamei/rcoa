<?php

namespace common\models\demand;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "{{%demand_appeal}}".
 *
 * @property integer $id                                id
 * @property integer $demand_task_id                    引用任务id
 * @property string $title                              申诉标题
 * @property string $reason                             申诉原因
 * @property string $des                                备注
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property DemandTask $demandTask                     获取需求任务
 * @property DemandReply $demandReplie                  获取回复信息
 * @property User $createBy                             获取创建者
 */
class DemandAppeal extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_appeal}}';
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
            [['demand_task_id', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['reason'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['demand_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['demand_task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_task_id' => Yii::t('rcoa/demand', 'Demand Task ID'),
            'title' => Yii::t('rcoa/demand', 'Title'),
            'reason' => Yii::t('rcoa/demand', 'Reason'),
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getDemandTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'demand_task_id']);
    }

    /**
     * 获取回复信息
     * @return ActiveQuery
     */
    public function getDemandReplie()
    {
        return $this->hasOne(DemandReply::className(), ['demand_appeal_id' => 'id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
