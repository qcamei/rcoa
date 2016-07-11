<?php

namespace common\models\team;

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%team}}".
 *
 * @property integer $id    id
 * @property string $name   名称
 * @property integer $type  类型
 * @property string $des    描述
 *
 * @property TeamType $teamType             获取团队类型    
 * @property TeamMember[] $teamMembers      获取团队成员
 * @property User[] $us                     获取用户
 * @property CourseManage[] $courseManages  获取课程
 * @property ItemManage[] $itemManages      获取项目
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
            [['name', 'des'], 'string', 'max' => 255],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => TeamType::className(), 'targetAttribute' => ['type' => 'id']],
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
    public function getTeamType()
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
    
    /**
     * @return ActiveQuery
     */
    public function getCourseManages()
    {
        return $this->hasMany(CourseManage::className(), ['team_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getItemManages()
    {
        return $this->hasMany(ItemManage::className(), ['team_id' => 'id']);
    }
}
