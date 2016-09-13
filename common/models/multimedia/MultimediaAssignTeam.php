<?php

namespace common\models\multimedia;

use common\models\team\Team;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "{{%multimedia_assign_team}}".
 *
 * @property integer $team_id                   团队ID
 * @property string $u_id                       团队指派人
 *
 * @property User $assignUser                   获取指派人
 * @property Team $team                         获取团队
 */
class MultimediaAssignTeam extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_assign_team}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'u_id'], 'required'],
            [['team_id'], 'integer'],
            [['u_id'], 'string', 'max' => 36],
            [['team_id'], 'unique'],
            [['u_id'], 'unique'],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['u_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'team_id' => Yii::t('rcoa/multimedia', 'Team ID'),
            'u_id' => Yii::t('rcoa/multimedia', 'U ID'),
        ];
    }

    /**
     * 获取指派人
     * @return ActiveQuery
     */
    public function getAssignUser()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }

    /**
     * 获取团队
     * @return ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
