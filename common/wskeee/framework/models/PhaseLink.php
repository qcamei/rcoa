<?php

namespace wskeee\framework\models;

use Yii;

/**
 * This is the model class for table "{{%framework_phase_link}}".
 *
 * @property integer $phases_id
 * @property integer $link_id
 * @property string $total
 * @property string $completed
 * @property integer $progress
 * @property string $create_by
 *
 * @property FrameworkLink $link
 * @property User $createBy
 * @property FrameworkPhase $phases
 */
class PhaseLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_phase_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phases_id', 'link_id'], 'required'],
            [['phases_id', 'link_id', 'progress'], 'integer'],
            [['total', 'completed'], 'number'],
            [['create_by'], 'string', 'max' => 36]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phases_id' => Yii::t('rcoa/framework', 'Phases ID'),
            'link_id' => Yii::t('rcoa/framework', 'Link ID'),
            'total' => Yii::t('rcoa/framework', 'Total'),
            'completed' => Yii::t('rcoa/framework', 'Completed'),
            'progress' => Yii::t('rcoa/framework', 'Progress'),
            'create_by' => Yii::t('rcoa/framework', 'Create By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(FrameworkLink::className(), ['id' => 'link_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhases()
    {
        return $this->hasOne(FrameworkPhase::className(), ['id' => 'phases_id']);
    }
}
