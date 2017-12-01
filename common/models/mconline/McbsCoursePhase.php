<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_course_phase}}".
 *  
 * @property string $id                     
 * @property string $course_id                              板书课程id
 * @property string $name                                   阶段名称
 * @property double $value_percent                          阶段占比值
 * @property string $des                                    描述
 * @property integer $sort_order                            排序
 * @property integer $is_del                                是否已经删除标记：0未删除，1已删除
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourse $course                             板书课程
 */
class McbsCoursePhase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_phase}}';
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
            [['id', 'course_id', 'name', 'value_percent'], 'required'],
            [['value_percent'], 'number'],
            [['des'], 'string'],
            [['sort_order', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['id', 'course_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
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
            'name' => Yii::t('app', 'Name'),
            'value_percent' => Yii::t('app', 'Value Percent'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_del' => Yii::t('app', 'Is Del'),
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
                /* @var $model McbsCoursePhase */
                $model = $this->find()->select(['sort_order'])
                        ->where(['course_id' => $this->course_id, 'is_del' => 0])
                        ->orderBy(['sort_order'=>SORT_DESC])->one();
                
                if($model != null)
                    $this->sort_order = $model->sort_order + 1;
                
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取板书课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(McbsCourse::className(), ['id' => 'course_id']);
    }
}
