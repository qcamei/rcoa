<?php

namespace common\models\mconline;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%mcbs_file_action_result}}".
 *
 * @property string $id
 * @property string $activity_id
 * @property string $file_id
 * @property string $user_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class McbsFileActionResult extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_file_action_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['activity_id', 'file_id'], 'string', 'max' => 32],
            [['user_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'file_id' => Yii::t('app', 'File ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 查看活动下该用户有关联文件
     * @param string $activity_id                   活动id
     * @return array
     */
    public static function getFileRelation($activity_id)
    {
        //获取当前用户相关活动文件通知
        $results = self::findAll(['activity_id'=>$activity_id,'user_id'=>Yii::$app->user->id]);
        //文件id=>状态
        $fileStatus = ArrayHelper::map($results, 'file_id', 'status');
        
        return $fileStatus;
    }
    
    /**
     * 查看活动下该用户是否所有有关联文件
     * @param string $activity_id                   活动id
     * @return boolean
     */
    public static function getIsFileRelations($activity_id)
    {
        //获取所有未读文件关联
        $results = self::findAll(['activity_id'=>$activity_id,'status'=>0]);
        //获取所有用户id
        $userIds = ArrayHelper::getColumn($results, 'user_id');
        //如果当前用户在数组里面则返回true，默认返回false
        if(in_array(\Yii::$app->user->id, $userIds))
            return true;
        
        return false;
    }
}
