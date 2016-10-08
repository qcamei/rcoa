<?php

namespace common\models\multimedia;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_check}}".
 *
 * @property integer $id                            ID
 * @property integer $task_id                       任务ID
 * @property string $title                          标题
 * @property string $remark                         备注
 * @property string $create_by                      创建者
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 * @property string $real_carry_out                 完成时间
 * @property integer $status                        状态
 *      
 * @property User $createBy                         获取创建者
 * @property MultimediaTask $task                   获取任务
 */
class MultimediaCheck extends ActiveRecord
{
    /** 未完成 */
    const STATUS_NOTCOMPLETE = 0;
    /** 已完成 */
    const STATUS_COMPLETE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_check}}';
    }

    public function behaviors() {
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
            [['task_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['remark'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['real_carry_out'], 'string', 'max' => 60],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->remark = htmlentities($this->remark);
            return true;
        }
    }
    
    public function afterFind() {
        
        $this->remark = html_entity_decode($this->remark);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'task_id' => Yii::t('rcoa/multimedia', 'Task Name'),
            'title' => Yii::t('rcoa/multimedia', 'Title'),
            'remark' => Yii::t('rcoa', 'Remark'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
            'real_carry_out' => Yii::t('rcoa/multimedia', 'Complete Time'),
            'status' => Yii::t('rcoa/multimedia', 'Status'),
        ];
    }

    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * 获取任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(MultimediaTask::className(), ['id' => 'task_id']);
    }
}
