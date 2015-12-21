<?php

namespace common\models\expert;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%expert_project}}".
 *
 * @property integer $id
 * @property integer $expert_id
 * @property integer $project_id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $cost
 * @property integer $compatibility
 *
 * @property Expert $expert
 */
class ExpertProject extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%expert_project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expert_id', 'project_id', 'start_time'], 'required'],
            [['expert_id', 'project_id', 'start_time', 'end_time', 'cost', 'compatibility'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'expert_id' => Yii::t('rcoa', 'Expert ID'),
            'project_id' => Yii::t('rcoa', 'Project ID'),
            'start_time' => Yii::t('rcoa', 'Start Time'),
            'end_time' => Yii::t('rcoa', 'End Time'),
            'cost' => Yii::t('rcoa', 'Cost'),
            'compatibility' => Yii::t('rcoa', 'Compatibility'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(Expert::className(), ['u_id' => 'expert_id']);
    }
}
