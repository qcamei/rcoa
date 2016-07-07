<?php

namespace common\models\teamwork;

use common\models\User;
use common\models\teamwork\Link;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_phase_template}}".
 *
 * @property integer $id            ID
 * @property string $name           名称
 * @property string $weights        权重
 * @property string $create_by      创建者
 * @property integer $index         索引
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
        return '{{%teamwork_phase_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weights'], 'number'],
            [['index'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['is_delete'], 'string', 'max' => 4]
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
            'create_by' => Yii::t('rcoa', 'Create By'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa', 'Is Delete'),
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
