<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%mcbs_course_activity}}".
 *
 * @property string $id
 * @property string $section_id                             节id
 * @property integer $type_id                               类型id
 * @property string $name                                   名称
 * @property string $des                                    描述
 * @property integer $sort_order                            排序
 * @property integer $is_del                                是否已经删除标记：0未删除，1已删除
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
            [['id', 'section_id', 'name'], 'required'],
            [['type_id', 'is_del', 'sort_order', 'created_at', 'updated_at'], 'integer'],
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
                /* @var $model McbsCourseActivity */
                $model = $this->find()->select(['sort_order'])
                        ->where(['section_id' => $this->section_id, 'is_del' => 0])
                        ->orderBy(['sort_order'=>SORT_DESC])->one();
        
                if($model != null)
                    $this->sort_order = $model->sort_order + 1;
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取父级路径
     * @param array $params
     * @return array
     */
    public static function getParentPath($params = null)
    {
        $id = ArrayHelper::getValue($params, 'id');
        //查询数据表
        $query = self::find()
            ->select([
                'CoursePhase.name AS cp_name','CourseBlock.name AS cb_name',
                'CourseChapter.name AS cc_name','CourseSection.name AS cs_name'
            ])->from(['CourseActivity'=> self::tableName()])
            ->where(['CourseActivity.id' => $id,'CourseActivity.is_del'=>0]);
        $query->leftJoin(['CourseSection'=> McbsCourseSection::tableName()], 'CourseSection.id = section_id');
        $query->leftJoin(['CourseChapter'=> McbsCourseChapter::tableName()], 'CourseChapter.id = CourseSection.chapter_id');
        $query->leftJoin(['CourseBlock'=> McbsCourseBlock::tableName()], 'CourseBlock.id = CourseChapter.block_id');
        $query->leftJoin(['CoursePhase'=> McbsCoursePhase::tableName()], 'CoursePhase.id = CourseBlock.phase_id');
        $results = $query->asArray()->one();
        
        return [
            'cp_name' => $results['cp_name'],
            'cb_name' => $results['cb_name'],
            'cc_name' => $results['cc_name'],
            'cs_name' => $results['cs_name'],
        ];
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
