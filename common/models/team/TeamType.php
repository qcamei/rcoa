<?php

namespace common\models\team;

use Yii;

/**
 * This is the model class for table "{{%team_type}}".
 *
 * @property integer $id    id
 * @property string $name   名称
 * @property string $des    描述
 *
 * @property Team[] $teams  获取所有团队
 */
class TeamType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::className(), ['type' => 'id']);
    }
}
