<?php

namespace common\models\team;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%team_category_map}}".
 *
 * @property string $category_id
 * @property integer $team_id
 * @property integer $index
 * @property string $is_delete
 * 
 * @property Team $team                     团队
 * @property TeamCategory $teamCategory     团队所属分类
 */
class TeamCategoryMap extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team_category_map}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'team_id'], 'required'],
            [['team_id', 'index'], 'integer'],
            [['category_id'], 'string', 'max' => 60],
            [['is_delete'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('rcoa/team', 'Category ID'),
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/team', 'Is Delete'),
        ];
    }
    
    /**
     * @return Team 团队
     */
    public function getTeam(){
        return $this->hasOne(Team::className(), ['id'=>'team_id']);
    }
    
    /**
     * @return TeamCategory 团队分类
     */
    public function getTeamCategory(){
        return $this->hasOne(TeamCategory::className(), ['id'=>'category_id']);
    }
}
