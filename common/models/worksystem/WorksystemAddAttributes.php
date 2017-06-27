<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_add_attributes}}".
 *
 * @property integer $id
 * @property integer $worksystem_task_id
 * @property integer $worksystem_task_type_id
 * @property integer $worksystem_attributes_id
 * @property string $value
 * @property integer $index
 * @property integer $is_delete
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WorksystemAttributes $worksystemAttributes
 * @property WorksystemTask $worksystemTask
 * @property WorksystemTaskType $worksystemTaskType
 */
class WorksystemAddAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_add_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_task_id', 'worksystem_task_type_id', 'worksystem_attributes_id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['worksystem_attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemAttributes::className(), 'targetAttribute' => ['worksystem_attributes_id' => 'id']],
            [['worksystem_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTask::className(), 'targetAttribute' => ['worksystem_task_id' => 'id']],
            [['worksystem_task_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTaskType::className(), 'targetAttribute' => ['worksystem_task_type_id' => 'id']],
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
            'worksystem_task_type_id' => Yii::t('rcoa/worksystem', 'Worksystem Task Type ID'),
            'worksystem_attributes_id' => Yii::t('rcoa/worksystem', 'Worksystem Attributes ID'),
            'value' => Yii::t('rcoa/worksystem', 'Value'),
            'index' => Yii::t('rcoa/worksystem', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemAttributes()
    {
        return $this->hasOne(WorksystemAttributes::className(), ['id' => 'worksystem_attributes_id']);
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
    public function getWorksystemTaskType()
    {
        return $this->hasOne(WorksystemTaskType::className(), ['id' => 'worksystem_task_type_id']);
    }
}
