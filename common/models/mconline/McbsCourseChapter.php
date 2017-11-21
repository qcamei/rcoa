<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_course_chapter}}".
 *
 * @property string $id                                 id
 * @property string $block_id                           区块id
 * @property string $name                               名称
 * @property string $des                                描述
 * @property integer $sort_order                        排序
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourseBlock $block                     课程框架区块
 */
class McbsCourseChapter extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_chapter}}';
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
            [['id', 'block_id'], 'required'],
            [['sort_order', 'created_at', 'updated_at'], 'integer'],
            [['id', 'block_id'], 'string', 'max' => 32],
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
            'block_id' => Yii::t('app', 'Block ID'),
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
                /* @var $model McbsCourseChapter */
                $model = $this->find()->select(['sort_order'])
                        ->where(['block_id' => $this->block_id])
                        ->orderBy('sort_order')->one();
        
                if($model != null)
                    $this->sort_order = $model->sort_order + 1;
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取课程框架区块
     * @return ActiveQuery
     */
    public function getBlock()
    {
        return $this->hasOne(McbsCourseBlock::className(), ['id' => 'block_id']);
    }
}
