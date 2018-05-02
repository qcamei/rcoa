<?php

namespace common\models\worksystem;

use common\models\team\TeamMember;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_task_producer}}".
 *
 * @property integer $id                                id
 * @property integer $worksystem_task_id                引用工作系统任务id
 * @property integer $team_member_id                    引用团队成员id：制作人员
 * @property integer $index                             索引
 * @property integer $is_delete                         是否删除：0为否，1为是
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property TeamMember $teamMember                     获取团队成员
 * @property WorksystemTask $worksystemTask             获取工作系统任务
 */
class WorksystemTaskProducer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_task_producer}}';
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
            [['worksystem_task_id', 'team_member_id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
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
            'index' => Yii::t('rcoa/worksystem', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
        ];
    }

    /**
     * 获取团队成员
     * @return ActiveQuery
     */
    public function getTeamMember()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'team_member_id']);
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
