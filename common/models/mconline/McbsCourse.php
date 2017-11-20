<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mconline_course}}".
 *
 * @property string $id
 * @property string $item_type_id
 * @property string $item_id
 * @property string $item_child_id
 * @property string $course_id
 * @property string $create_by
 * @property integer $status
 * @property integer $is_publish
 * @property string $publish_time
 * @property string $close_time
 * @property string $des
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCourse extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course}}';
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
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'course_id'], 'required'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'status', 'is_publish', 'publish_time', 'close_time', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['id'], 'string', 'max' => 32],
            [['create_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_type_id' => Yii::t('app', 'Item Type ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'item_child_id' => Yii::t('app', 'Item Child ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'create_by' => Yii::t('app', 'Create By'),
            'status' => Yii::t('app', 'Status'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'publish_time' => Yii::t('app', 'Publish Time'),
            'close_time' => Yii::t('app', 'Close Time'),
            'des' => Yii::t('app', 'Des'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord){
                //$this->id = md5(rand(1,10000) + time());    //自动生成用户ID
                $this->create_by = Yii::$app->user->id;    //创建者
            }
            
            return true;
        }else
            return false;
    }
}
