<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_task_producer}}".
 *
 * @property integer $id
 * @property integer $worksystem_task_id
 * @property integer $team_member_id
 *
 * @property TeamMember $teamMember
 * @property WorksystemTask $worksystemTask
 */
class WorksystemTaskProducer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_task_producer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worksystem_task_id', 'team_member_id'], 'integer'],
            [['team_member_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['team_member_id' => 'id']],
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
            'team_member_id' => Yii::t('rcoa/worksystem', 'Team Member ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMember()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'team_member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
    }
}
