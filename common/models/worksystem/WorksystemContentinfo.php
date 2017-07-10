<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_contentinfo}}".
 *
 * @property integer $id                                    id
 * @property integer $worksystem_task_id                    引用工作系统任务id
 * @property integer $worksystem_content_id                 引用基础内容信息id
 * @property integer $is_new                                新建/改造：1为新建，0为改造
 * @property string $price                                  单价
 * @property integer $budget_number                         预计数量
 * @property string $budget_cost                            预计成本
 * @property integer $reality_number                        实际数量
 * @property string $reality_cost                           实际成本
 * @property integer $index                                 索引
 * @property integer $is_delete                             是否删除：0为否，1为是
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 *
 * @property WorksystemContent $worksystemContent           获取基础内容信息
 * @property WorksystemTask $worksystemTask                 获取工作系统任务
 */
class WorksystemContentinfo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_contentinfo}}';
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
            [['worksystem_task_id', 'worksystem_content_id', 'is_new', 'budget_number', 'reality_number', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['price', 'budget_cost', 'reality_cost'], 'number'],
            [['worksystem_content_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemContent::className(), 'targetAttribute' => ['worksystem_content_id' => 'id']],
            [['worksystem_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTask::className(), 'targetAttribute' => ['worksystem_task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_task_id' => Yii::t('rcoa/worksystem', 'Worksystem Task ID'),
            'worksystem_content_id' => Yii::t('rcoa/worksystem', 'Worksystem Content ID'),
            'is_new' => Yii::t('rcoa/worksystem', 'Is New'),
            'price' => Yii::t('rcoa/worksystem', 'Price'),
            'budget_number' => Yii::t('rcoa/worksystem', 'Budget Number'),
            'budget_cost' => Yii::t('rcoa/worksystem', 'Budget Cost'),
            'reality_number' => Yii::t('rcoa/worksystem', 'Reality Number'),
            'reality_cost' => Yii::t('rcoa/worksystem', 'Reality Cost'),
            'index' => Yii::t('rcoa/worksystem', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * 获取基础内容信息
     * @return ActiveQuery
     */
    public function getWorksystemContent()
    {
        return $this->hasOne(WorksystemContent::className(), ['id' => 'worksystem_content_id']);
    }

    /**
     * 获取工作系统任务
     * @return ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
    }
}
