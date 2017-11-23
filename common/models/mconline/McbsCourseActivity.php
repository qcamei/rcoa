<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_course_activity}}".
 *
 * @property string $id
 * @property string $section_id                             节id
 * @property integer $type_id                               类型id
 * @property string $name                                   名称
 * @property string $des                                    描述
 * @property integer $sort_order                            排序
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsActivityType $type                         获取活动类型
 * @property McbsCourseSection $section                     获取框架节
 */
class McbsCourseActivity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_activity}}';
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
            [['id', 'section_id'], 'required'],
            [['type_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['id', 'section_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
            [['des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
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
                /* @var $model McbsCourseActivity */
                $model = $this->find()->select(['sort_order'])
                        ->where(['section_id' => $this->section_id])
                        ->orderBy('sort_order')->one();
        
                if($model != null)
                    $this->sort_order = $model->sort_order + 1;
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取活动类型
     * @return ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(McbsActivityType::className(), ['id' => 'type_id']);
    }
    
    /**
     * 获取课程框架节
     * @return ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(McbsCourseSection::className(), ['id' => 'section_id']);
    }
}
