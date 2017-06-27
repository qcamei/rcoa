<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_contentinfo}}".
 *
 * @property integer $id
 * @property integer $worksystem_task_id
 * @property integer $worksystem_content_id
 * @property string $price
 * @property integer $budget_number
 * @property string $budget_cost
 * @property integer $reality_number
 * @property string $reality_cost
 * @property integer $index
 * @property integer $is_delete
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WorksystemContent $worksystemContent
 * @property WorksystemTask $worksystemTask
 */
class WorksystemContentinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_contentinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_task_id', 'worksystem_content_id', 'budget_number', 'reality_number', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemContent()
    {
        return $this->hasOne(WorksystemContent::className(), ['id' => 'worksystem_content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
    }
}
