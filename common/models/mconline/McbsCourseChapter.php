<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%mcbs_course_chapter}}".
 *
 * @property string $id                                     
 * @property string $block_id                               区块id
 * @property string $name                                   名称
 * @property string $des                                    描述
 * @property integer $sort_order                            排序
 * @property integer $is_del                                是否已经删除标记：0未删除，1已删除
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourseBlock $block                         课程框架区块
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
            [['id', 'block_id', 'name'], 'required'],
            [['sort_order', 'is_del', 'created_at', 'updated_at'], 'integer'],
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
                /* @var $model McbsCourseChapter */
                $model = $this->find()->select(['sort_order'])
                        ->where(['block_id' => $this->block_id, 'is_del' => 0])
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
            ->select(['CoursePhase.name AS cp_name','CourseBlock.name AS cb_name'])
            ->from(['CourseChapter'=> self::tableName()])
            ->where(['CourseChapter.id' => $id,'CourseChapter.is_del'=>0]);
        $query->leftJoin(['CourseBlock'=> McbsCourseBlock::tableName()], 'CourseBlock.id = block_id');
        $query->leftJoin(['CoursePhase'=> McbsCoursePhase::tableName()], 'CoursePhase.id = CourseBlock.phase_id');
        $results = $query->asArray()->one();
        
        return [
            'cp_name' => $results['cp_name'],
            'cb_name' => $results['cb_name'],
        ];
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
