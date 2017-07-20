<?php

namespace common\models\worksystem;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_annex}}".
 *
 * @property integer $id                                id
 * @property integer $worksystem_task_id                引用工作系统任务id
 * @property string $name                               附件名称
 * @property string $path                               附件路径
 * @property string $create_by                          创建者
 * @property integer $index                             索引
 * @property integer $is_delete                         是否删除：0为否，1为是
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *  
 * @property User $createBy                             获取创建者
 * @property WorksystemTask $worksystemTask             获取工作系统任务
 */
class WorksystemAnnex extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_annex}}';
    }

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
            [['worksystem_task_id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['worksystem_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTask::className(), 'targetAttribute' => ['worksystem_task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_task_id' => Yii::t('rcoa/worksystem', 'Worksystem Task ID'),
            'name' => Yii::t('rcoa/worksystem', 'Name'),
            'path' => Yii::t('rcoa/worksystem', 'Path'),
            'create_by' => Yii::t('rcoa/worksystem', 'Create By'),
            'index' => Yii::t('rcoa/worksystem', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
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
     * 获取工作系统任务
     * @return ActiveQuery
     */
    public function getWorksystemTask()
    {
        return $this->hasOne(WorksystemTask::className(), ['id' => 'worksystem_task_id']);
    }
}
