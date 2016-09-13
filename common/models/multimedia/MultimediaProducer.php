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
 * @property string $u_id                       制作人
 *
 * @property TeamMember $producer               获取团队制作人
 * @property MultimediaManage $task             获取制作任务
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
            [['task_id', 'u_id'], 'required'],
            [['task_id'], 'integer'],
            [['u_id'], 'string', 'max' => 36],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['u_id' => 'u_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaManage::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => Yii::t('rcoa/multimedia', 'Task ID'),
            'u_id' => Yii::t('rcoa/multimedia', 'U ID'),
        ];
    }

    /**
     * 获取团队制作人
     * @return ActiveQuery
     */
    public function getProducer()
    {
        return $this->hasOne(TeamMember::className(), ['u_id' => 'u_id']);
    }

    /**
     * 获取制作任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(MultimediaManage::className(), ['id' => 'task_id']);
    }
}
