<?php

namespace common\models\multimedia;

use common\models\multimedia\MultimediaManage;
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
 * @property string $carry_out_time                 完成时间
 *      
 * @property User $createBy                 获取创建者
 * @property MultimediaManage $task                 获取任务
 */
class MultimediaCheck extends ActiveRecord
{
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
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->content = htmlentities($this->remark);
            return true;
        }
    }
    public function afterFind() {
        
        $this->content = html_entity_decode($this->remark);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'task_id', 'created_at', 'updated_at'], 'integer'],
            [['remark'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['carry_out_time'], 'string', 'max' => 60],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaManage::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'task_id' => Yii::t('rcoa/multimedia', 'Task ID'),
            'title' => Yii::t('rcoa/multimedia', 'Title'),
            'remark' => Yii::t('rcoa/multimedia', 'Remark'),
            'create_by' => Yii::t('rcoa/multimedia', 'Create By'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
            'carry_out_time' => Yii::t('rcoa/multimedia', 'Carry Out Time'),
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
        return $this->hasOne(MultimediaManage::className(), ['id' => 'task_id']);
    }
}
