<?php

namespace common\models\worksystem;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_operation}}".
 *
 * @property integer $id                                id
 * @property integer $worksystem_task_id                引用工作系统任务id
 * @property integer $worksystem_task_status            工作系统任务状态
 * @property string $controller_action                  控制器/行为
 * @property string $title                              标题
 * @property string $content                            内容
 * @property string $des                                备注
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property WorksystemTask $worksystemTask                         获取工作系统任务
 * @property User $createBy                                         获取创建者
 * @property WorksystemOperationUser[] $worksystemOperationUsers    获取所有操作用户
 */
class WorksystemOperation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_operation}}';
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
            [['worksystem_task_id', 'worksystem_task_status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'des'], 'string'],
            [['controller_action', 'title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
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
            'worksystem_task_status' => Yii::t('rcoa/worksystem', 'Worksystem Task Status'),
            'controller_action' => Yii::t('rcoa/worksystem', 'Controller Action'),
            'title' => Yii::t('rcoa/worksystem', 'Title'),
            'content' => Yii::t('rcoa/worksystem', 'Content'),
            'des' => Yii::t('rcoa/worksystem', 'Des'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * 获取工作系统任务
     * @return ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
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
     * 获取所有操作用户
     * @return ActiveQuery
     */
    public function getWorksystemOperationUsers()
    {
        return $this->hasMany(WorksystemOperationUser::className(), ['worksystem_operation_id' => 'id']);
    }
}
