<?php

namespace common\models\team;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\User;

/**
 * This is the model class for table "{{%team}}".
 *
 * @property integer $id    id
 * @property string $name   名称
 * @property integer $type  类型
 * @property string $des    描述
 *
 * @property FrameworkCourseManage[] $frameworkCourseManages    获取课程管理
 * @property TeamType $type     获取团队类型    
 * @property TeamMember[] $teamMembers     获取团队成员
 * @property User[] $us     获取用户
 */
class Team extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['name', 'des'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/team', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'type' => Yii::t('rcoa', 'Type'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getFrameworkCourseManages()
    {
        return $this->hasMany(FrameworkCourseManage::className(), ['resource_people' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TeamType::className(), ['id' => 'type']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUs()
    {
        return $this->hasMany(User::className(), ['id' => 'u_id'])->viaTable('{{%team_member}}', ['team_id' => 'id']);
    }
}
