<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_operation}}".
 *
 * @property integer $id
 * @property integer $worksystem_task_id
 * @property integer $worksystem_task_status
 * @property string $title
 * @property string $content
 * @property string $des
 * @property string $create_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WorksystemTask $worksystemTask
 * @property WorksystemOperationUser[] $worksystemOperationUsers
 */
class WorksystemOperation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_operation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_task_id', 'worksystem_task_status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'des'], 'string'],
            [['title'], 'string', 'max' => 20],
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
            'title' => Yii::t('rcoa/worksystem', 'Title'),
            'content' => Yii::t('rcoa/worksystem', 'Content'),
            'des' => Yii::t('rcoa/worksystem', 'Des'),
            'create_by' => Yii::t('rcoa/worksystem', 'Create By'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemOperationUsers()
    {
        return $this->hasMany(WorksystemOperationUser::className(), ['worksystem_operation_id' => 'id']);
    }
}
