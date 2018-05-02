<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;



/**
 * This is the model class for table "{{%worksystem_attributes_template}}".
 *
 * @property integer $id                                id
 * @property integer $worksystem_task_type_id           引用工作系统任务类别
 * @property integer $worksystem_attributes_id          引用工作系统基础附加属性id
 * @property integer $index                             索引
 * @property integer $is_delete                         是否删除
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * 
 * @property WorksystemAttributes $worksystemAttributes 获取工作系统任务类型
 * @property WorksystemTaskType $worksystemTaskType     获取工作系统任务类型
 */
class WorksystemAttributesTemplate extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_attributes_template}}';
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
            [['worksystem_task_type_id', 'worksystem_attributes_id'], 'required'],
            [['worksystem_task_type_id', 'worksystem_attributes_id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_task_type_id' => Yii::t('rcoa/worksystem', 'Worksystem Task Type ID'),
            'worksystem_attributes_id' => Yii::t('rcoa/worksystem', 'Worksystem Attributes ID'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }
    
    /**
     * 获取基础附加属性
     * @return ActiveQuery
     */
    public function getWorksystemAttributes()
    {
        return $this->hasOne(WorksystemAttributes::className(), ['id' => 'worksystem_attributes_id']);
    }
    
    /**
     * 获取工作系统任务类别
     * @return ActiveQuery
     */
    public function getWorksystemTaskType()
    {
        return $this->hasOne(WorksystemTaskType::className(), ['id' => 'worksystem_task_type_id']);
    }
}
