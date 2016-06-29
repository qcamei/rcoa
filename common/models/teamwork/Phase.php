<?php

namespace common\models\teamwork;

use common\models\User;
use common\models\teamwork\Link;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_phase}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $weights
 * @property integer $progress
 * @property string $create_by
 *
 * @property Link[] $links
 * @property User $createBy
 */
class Phase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_phase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weights'], 'number'],
            [['progress'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'weights' => Yii::t('rcoa/teamwork', 'Weights'),
            'progress' => Yii::t('rcoa/teamwork', 'Progress'),
            'create_by' => Yii::t('rcoa', 'Create By'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::className(), ['phase_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
