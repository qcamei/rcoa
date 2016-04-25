<?php

namespace common\wskeee\job\models;

use Yii;

/**
 * This is the model class for table "{{%job_notification}}".
 *
 * @property integer $job_id
 * @property integer $u_id
 * @property integer $status
 *
 * @property User $u
 * @property Job $mes
 */
class JobNotification extends \yii\db\ActiveRecord
{
    /**
     * 初始、未读状态
     */
    const STATUS_INIT = 0;
    
    /**
     * 正常状态（初始状态下读取后状态，该状态直到消息移除）
     */
    const STATUS_NORMAL = 5;
    
    /**
     * 结束状态（用户不再收到该消息）
     */
    const STATUS_END = 99;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%job_notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'u_id'], 'required'],
            [['job_id', 'u_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_id' => Yii::t('rcoa', 'JOB ID'),
            'u_id' => Yii::t('rcoa', 'U ID'),
            'status' => Yii::t('rcoa', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::className(), ['id' => 'job_id']);
    }
}
