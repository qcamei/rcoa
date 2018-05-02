<?php

namespace common\models\multimedia;

use common\models\team\TeamMember;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_producer}}".
 *
 * @property integer $task_id                   任务ID
 * @property integer $producer                  制作人
 *  
 * @property TeamMember $multimediaProducer     获取团队制作人
 * @property MultimediaTask $task               获取多媒体任务
 */
class MultimediaProducer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_producer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'producer'], 'required'],
            [['task_id', 'producer'], 'integer'],
            [['producer'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['producer' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => Yii::t('rcoa/multimedia', 'Task ID'),
            'producer' => Yii::t('rcoa/multimedia', 'Producer'),
        ];
    }

    /**
     * 获取团队制作人
     * @return ActiveQuery
     */
    public function getMultimediaProducer()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'producer']);
    }

    /**
     * 获取多媒体任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(MultimediaTask::className(), ['id' => 'task_id']);
    }
}
