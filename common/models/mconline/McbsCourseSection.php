<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_course_section}}".
 *
 * @property string $id
 * @property string $chapter_id                             章id
 * @property string $name                                   名称
 * @property string $des                                    描述
 * @property integer $sort_order                            排序
 * @property integer $is_del                                是否已经删除标记：0未删除，1已删除
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourseChapter $chapter                     课程框架章
 */
class McbsCourseSection extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_section}}';
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
            [['id', 'chapter_id'], 'required'],
            [['sort_order', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['id', 'chapter_id'], 'string', 'max' => 32],
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
            'chapter_id' => Yii::t('app', 'Chapter ID'),
            'name' => Yii::t('app', 'Name'),
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
                /* @var $model McbsCourseSection */
                $model = $this->find()->select(['sort_order'])
                        ->where(['chapter_id' => $this->chapter_id])
                        ->orderBy(['sort_order'=>SORT_DESC])->one();
        
                if($model != null)
                    $this->sort_order = $model->sort_order + 1;
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取课程框架章
     * @return ActiveQuery
     */
    public function getChapter()
    {
        return $this->hasOne(McbsCourseChapter::className(), ['id' => 'chapter_id']);
    }
}
