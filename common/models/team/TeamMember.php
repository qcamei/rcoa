<?php

namespace common\models\team;

use common\models\User;
use wskeee\rbac\models\AuthItem;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%team_member}}".
 *
 * @property integer $team_id   团队ID
 * @property string $u_id       用户ID
 * @property string $is_leader  是否为队长
 * @property array $is_leaders   队长 or 队员
 *
 * @property Team $team    获取团队
 * @property User $u       获取用户 
 */
class TeamMember extends ActiveRecord
{
    /** 队长 */
    const TEAMLEADER = 'Y';
    /** 队员 */
    const TEAMMEMBER = 'N';
    
    /** 队长 or 队员 */
    public $is_leaders = [
        self::TEAMLEADER => '队长',
        self::TEAMMEMBER => '队员'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'u_id'], 'required'],
            [['is_leader'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'u_id' => Yii::t('rcoa/team', 'U ID'),
            'is_leader' => Yii::t('rcoa/team', 'Is Leader'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }   
}
