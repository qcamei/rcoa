<?php

namespace common\wskeee\job\models;

use common\models\System;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%job}}".
 *
 * @property integer $id
 * @property integer $system_id
 * @property integer $relate_id
 * @property string $subject
 * @property string $content
 * @property string $link
 * @property integer $progress
 * @property string $status
 *
 * @property Systme $system
 */
class Job extends ActiveRecord
{
    const STATUS_DEFAULT = '未预约';
    const STATUS_BOOKING = '预约中';
    const STATUS_ASSIGN = '待指派';
    const STATUS_SHOOTING = '待评价';
    const STATUS_APPRAISE = '评价中';
    const STATUS_COMPLETED = '已完成';
    const STATUS_BREAK_PROMISE = '已失约';
    const STATUS_CANCEL = '已取消';
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%job}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_id', 'relate_id'], 'required'],
            [['relate_id', 'progress'], 'integer'],
            [['content'], 'string'],
            [['system_id'], 'integer'],
            [['status'], 'string', 'max' => 64],
            [['subject', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'system_id' => Yii::t('rcoa', 'System ID'),
            'relate_id' => Yii::t('rcoa', 'Relate ID'),
            'subject' => Yii::t('rcoa', 'Subject'),
            'content' => Yii::t('rcoa', 'Content'),
            'link' => Yii::t('rcoa', 'Link'),
            'progress' => Yii::t('rcoa', 'Progress'),
            'status' => Yii::t('v', 'Status'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSystem()
    {
        return $this->hasOne(System::className(), ['id' => 'system_id']);
    }
}
