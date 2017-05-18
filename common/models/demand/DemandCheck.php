<?php

namespace common\models\demand;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_check}}".
 *
 * @property integer $id                               ID
 * @property integer $demand_task_id                   引用需求任务ID
 * @property string $title                             标题
 * @property string $content                           内容
 * @property string $des                               备注
 * @property string $create_by                         创建者
 * @property integer $created_at                       创建于
 * @property integer $updated_at                       更新于
 *
 * @property User $createBy                            获取创建者
 * @property DemandTask $demandTask                    获取需求任务
 * @property DemandCheckReply $demandCheckReply        获取所有需求审核回复
 */
class DemandCheck extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_check}}';
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
            [['content', 'des'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'content' => Yii::t('rcoa/demand', 'Content'),
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
    }

    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
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
     * 获取需求审核回复
     * @return ActiveQuery
     */
    public function getDemandCheckReply()
    {
        return $this->hasOne(DemandCheckReply::className(), ['demand_check_id' => 'id']);
    }
}
