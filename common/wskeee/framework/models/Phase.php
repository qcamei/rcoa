<?php

namespace wskeee\framework\models;

use common\models\User;
use wskeee\framework\models\Link;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%framework_phase}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $weights
 * @property integer $progress
 * @property string $create_by
 *
 * @property FrameworkLink[] $links
 * @property User $createBy
 */
class Phase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_phase}}';
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
            'id' => Yii::t('rcoa/framework', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'weights' => Yii::t('rcoa/framework', 'Weights'),
            'progress' => Yii::t('rcoa/framework', 'Progress'),
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
