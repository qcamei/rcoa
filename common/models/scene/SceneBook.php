<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_book}}".
 *
 * @property string $id             ID=MD5(site_id+Ymd+time_index)
 * @property string $site_id        场地ID
 * @property string $date           预定日期
 * @property integer $time_index    时间段0、1、2
 * @property string $status         状态
 * @property string $business_id    行业ID
 * @property string $level_id       层次/类型ID
 * @property string $profession_id  专业/工种
 * @property string $course_id      课程ID
 * @property integer $lession_time  课时
 * @property integer $content_type  内容类型：1板书、2蓝箱、3外拍、4白布、5书架
 * @property integer $shoot_mode    摄影模式：1标清 2高清
 * @property integer $is_photograph 是否拍照：0否，1是
 * @property integer $camera_count  机位数
 * @property string $start_time     开始时间
 * @property string $remark         备注
 * @property string $teacher_id     老师ID
 * @property string $booker_id      预定人ID
 * @property string $created_by     创建人ID
 * @property string $created_at 
 * @property string $updated_at
 * @property string $ver            乐观锁,内容版本控制
 *
 * @property SceneActionLog[] $sceneActionLogs
 * @property SceneAppraise[] $sceneAppraises
 * @property SceneBookUser[] $sceneBookUsers
 * @property SceneMessage[] $sceneMessages
 */
class SceneBook extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_book}}';
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
            [['id', 'booker_id', 'created_by'], 'required'],
            [['site_id', 'time_index', 'status', 'business_id', 'level_id', 'profession_id', 'course_id', 'lession_time', 'content_type', 'shoot_mode', 'is_photograph', 'camera_count', 'created_at', 'updated_at', 'ver'], 'integer'],
            [['date'], 'safe'],
            [['id'], 'string', 'max' => 32],
            [['start_time'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['teacher_id', 'booker_id', 'created_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'site_id' => Yii::t('app', 'Site ID'),
            'date' => Yii::t('app', 'Date'),
            'time_index' => Yii::t('app', 'Time Index'),
            'status' => Yii::t('app', 'Status'),
            'business_id' => Yii::t('app', 'Business ID'),
            'level_id' => Yii::t('app', 'Level ID'),
            'profession_id' => Yii::t('app', 'Profession ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'lession_time' => Yii::t('app', 'Lession Time'),
            'content_type' => Yii::t('app', 'Content Type'),
            'shoot_mode' => Yii::t('app', 'Shoot Mode'),
            'is_photograph' => Yii::t('app', 'Is Photograph'),
            'camera_count' => Yii::t('app', 'Camera Count'),
            'start_time' => Yii::t('app', 'Start Time'),
            'remark' => Yii::t('app', 'Remark'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'booker_id' => Yii::t('app', 'Booker ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'ver' => Yii::t('app', 'Ver'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneActionLogs()
    {
        return $this->hasMany(SceneActionLog::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneAppraises()
    {
        return $this->hasMany(SceneAppraise::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneBookUsers()
    {
        return $this->hasMany(SceneBookUser::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneMessages()
    {
        return $this->hasMany(SceneMessage::className(), ['book_id' => 'id']);
    }
}
