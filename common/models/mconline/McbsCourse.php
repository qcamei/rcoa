<?php

namespace common\models\mconline;

use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mconline_course}}".
 *
 * @property string $id
 * @property integer $item_type_id                      行业ID
 * @property integer $item_id                           层次/类型ID
 * @property integer $item_child_id                     专业/工种ID
 * @property integer $course_id                         课程ID
 * @property string $create_by                          创建者
 * @property integer $status                            状态：1正常、10关闭
 * @property integer $is_publish                        是否已发布：0未发布、1已发布
 * @property string $publish_time                       发布时间
 * @property string $close_time                         关闭时间
 * @property string $des                                课程简介
 * @property integer $created_at                        
 * @property integer $updated_at                        
 * 
 * @property ItemType $itemType                         获取行业
 * @property Item $item                                 获取层次/类型
 * @property Item $itemChild                            获取专业/工种
 * @property Item $course                               获取课程
 * @property User $createBy                             获取创建者
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
        $course = $this->findOne(['course_id'=> $this->course_id]);
        if($course == null){
            if (parent::beforeSave($insert)) {
                if ($this->isNewRecord) {
                    //$this->id = md5(rand(1,10000) + time());      //自动生成用户ID
                    //$this->create_by = Yii::$app->user->id;       //创建者
                    $courUser = new McbsCourseUser([
                        'course_id' => $this->id, 'user_id' => $this->create_by,
                        'privilege' => McbsCourseUser::OWNERSHIP
                    ]);
                    $courUser->save();
                }
                return true;
            }
        }
        Yii::$app->getSession()->setFlash('error', '该课程已存在！');
        return false;
    }
    
    
    /**
     * 获取行业
     * @return ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }
    
    /**
     * 获取层次/类型
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
    
    /**
     * 获取专业/工种
     * @return ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_child_id']);
    }
    
    /**
     * 获取课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::className(), ['id' => 'course_id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
