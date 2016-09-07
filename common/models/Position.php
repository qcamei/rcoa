<?php

namespace common\models;

use common\models\team\TeamMember;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%position}}".
 *
 * @property integer $id            ID
 * @property string $name           职位名称
 * @property string $des            描述
 *
 * @property TeamMember[] $teamMembers      获取所有团队成员
 */
class Position extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/position', 'ID'),
            'name' => Yii::t('rcoa/position', 'Name'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * 获取所有团队成员
     * @return ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['position_id' => 'id']);
    }
}
