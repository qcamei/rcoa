<?php

namespace common\models\need;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_task_user}}".
 *
 * @property string $id
 * @property string $user_id            用户ID
 * @property string $need_task_id
 * @property string $performance_percent
 * @property int $privilege             权限：0只读 1编辑 5全部权限
 * @property int $is_del                是否删除：0否 1是
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
 */
class NeedTaskUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_task_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::class
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['need_task_id'], 'required'],
            [['performance_percent'], 'number'],
            [['privilege', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 36],
            [['need_task_id'], 'string', 'max' => 32],
            [['user_id', 'need_task_id'], 'unique', 'targetAttribute' => ['user_id', 'need_task_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'need_task_id' => Yii::t('app', 'Need Task ID'),
            'performance_percent' => Yii::t('app', 'Performance Percent'),
            'privilege' => Yii::t('app', 'Privilege'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
