<?php

namespace common\models\mconline;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_course_user}}".
 *
 * @property string $id
 * @property string $course_id                              课程id  
 * @property string $user_id                                用户id
 * @property integer $privilege                             权限
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property User $user                                     关联用户
 * @property McbsCourse $course                             关联板书课程
 */
class McbsCourseUser extends ActiveRecord
{
    /** 只读权限 */
    const READONLY = 1;
    /** 编辑权限 */
    const EDIT = 2;
    /** 全部权限 */
    const OWNERSHIP = 10;
    
    /**
     * 权限名称
     * @var  array
     */
    public static $privilegeName = [
        self::READONLY => '只读',
        self::EDIT => '编辑',
        self::OWNERSHIP => '全部',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_user}}';
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
            [['privilege', 'created_at', 'updated_at'], 'integer'],
            [['course_id'], 'string', 'max' => 32],
            //[['user_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'privilege' => Yii::t('app', 'Privilege'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(McbsCourse::className(), ['id' => 'course_id']);
    }
}
