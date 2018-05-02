<?php

namespace common\models\worksystem;

use common\models\team\Team;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_assign_team}}".
 *
 * @property integer $id                            id
 * @property integer $team_id                       引用团队id
 * @property string $user_id                        引用用户id：指派人
 * @property string $des                            描述
 * @property integer $index                         索引
 * @property integer $is_delete                     是否删除：0为否，1为是
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 *
 * @property User $user                             获取用户
 * @property Team $team                             获取团队
 */
class WorksystemAssignTeam extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_assign_team}}';
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
            [['team_id', 'user_id'], 'required'],
            [['team_id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['user_id'], 'string', 'max' => 36],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'team_id' => Yii::t('rcoa/worksystem', 'Team ID'),
            'user_id' => Yii::t('rcoa/worksystem', 'User ID'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取用户
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
