<?php

namespace common\models\scene;

use common\models\question\Question;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_appraise_template}}".
 *
 * @property string $id
 * @property integer $role      角色：1接洽人，2摄影师
 * @property string $q_id       问题滴
 * @property integer $value     得分
 * @property integer $index     索引
 * 
 * @property Question $question         获取评价题目
 * @property SceneAppraise[] $appraises 获取所有场景评价
 */
class SceneAppraiseTemplate extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_appraise_template}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::className()
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'q_id', 'value', 'index'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role' => Yii::t('app', 'Role'),
            'q_id' => Yii::t('app', 'Q ID'),
            'value' => Yii::t('app', 'Value'),
            'index' => Yii::t('app', 'Index'),
        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'q_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAppraises()
    {
        return $this->hasMany(SceneAppraise::className(), ['q_id' => 'q_id']);
    }
}
