<?php

namespace common\models\demand;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_task_annex}}".
 *
 * @property integer $id                        ID
 * @property integer $task_id                   任务ID
 * @property string $name                       附件名称
 * @property string $path                       附件路径
 * @property string $is_delete                  是否删除
 *
 * @property DemandTask $task                   获取需求任务
 */
class DemandTaskAnnex extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_task_annex}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['is_delete'], 'string', 'max' => 4],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'task_id' => Yii::t('rcoa/demand', 'Task ID'),
            'name' => Yii::t('rcoa/demand', 'Name'),
            'path' => Yii::t('rcoa/demand', 'Path'),
            'is_delete' => Yii::t('rcoa/demand', 'Is Delete'),
        ];
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'task_id']);
    }
}
