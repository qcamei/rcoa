<?php

namespace wskeee\framework\models;

use Yii;

/**
 * This is the model class for table "{{%framework_phase}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $weights
 * @property integer $progress
 * @property string $create_by
 *
 * @property FrameworkCourseLink[] $frameworkCourseLinks
 * @property FrameworkLink[] $frameworkLinks
 * @property User $createBy
 */
class Phase extends \yii\db\ActiveRecord
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
            'name' => Yii::t('rcoa/framework', 'Name'),
            'weights' => Yii::t('rcoa/framework', 'Weights'),
            'progress' => Yii::t('rcoa/framework', 'Progress'),
            'create_by' => Yii::t('rcoa/framework', 'Create By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrameworkCourseLinks()
    {
        return $this->hasMany(FrameworkCourseLink::className(), ['phase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrameworkLinks()
    {
        return $this->hasMany(FrameworkLink::className(), ['phase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
