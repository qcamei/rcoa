<?php

namespace common\models\expert;

use wskeee\framework\FrameworkManager;
use wskeee\framework\models\FWItem;
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
 * @property FWItem $project 项目数据
 */
class ExpertProject extends ActiveRecord
{
    /** 合作融洽度 好、一般、差 */
    const COMPATIBILITY_GOOD = 1;
    const COMPATIBILITY_GENERAL = 2;
    const COMPATIBILITY_BAD = 3;
    /** 合作融洽度 1好、2一般、3差 */
    public static $compatibilityMap = [
        self::COMPATIBILITY_GOOD => '好',
        self::COMPATIBILITY_GENERAL => '一般',
        self::COMPATIBILITY_BAD => '差',
    ];
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
            [['expert_id', 'project_id', 'cost', 'compatibility'], 'integer'],
            [['end_time'], 'checkEndTime'],
        ];
    }
    /**
     * 检查结束日期
     */
    public function checkEndTime()
    {
        if(isset($this->end_time) && $this->start_time>=$this->end_time)
            $this->addError('end_time','结束时间不可以小于开始时间');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'expert_id' => Yii::t('rcoa', 'Expert'),
            'project_id' => Yii::t('rcoa', 'ProjectName'),
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
    
    public function getProject()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        return $fwManager->getItemById($this->project_id);
    }
}
