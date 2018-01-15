<?php

namespace common\models\scene;

use common\models\question\Question;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_appraise}}".
 *
 * @property string $id
 * @property string $book_id        预定ID
 * @property integer $role          题目评价的角色：1接洽人，2摄影师
 * @property string $q_id           评价题目id
 * @property integer $q_value       评价题目分值
 * @property integer $index         索引
 * @property string $user_id        用户id
 * @property integer $user_value    用户得分值
 * @property string $user_data      用户详细数据
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneBook $book                    获取预约任务
 * @property SceneAppraiseTemplate $template    获取场景评价题目模版
 * @property Question $question                 获取评价题目
 * @property SceneAppraiseResult[] $results     获取场景评价结果
 */
class SceneAppraise extends ActiveRecord
{
    /**
     * 用户id
     */
    public $user_id;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_appraise}}';
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
            [['role', 'q_id', 'q_value', 'index', 'user_value', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'required'],
            [['book_id'], 'string', 'max' => 32],
            [['user_id'], 'string', 'max' => 36],
            [['user_data'], 'string', 'max' => 255],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => SceneBook::className(), 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'book_id' => Yii::t('app', 'Book ID'),
            'role' => Yii::t('app', 'Role'),
            'q_id' => Yii::t('app', 'Q ID'),
            'q_value' => Yii::t('app', 'Q Value'),
            'index' => Yii::t('app', 'Index'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_value' => Yii::t('app', 'User Value'),
            'user_data' => Yii::t('app', 'User Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(SceneBook::className(), ['id' => 'book_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(SceneAppraiseTemplate::className(), ['q_id' => 'q_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'q_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(SceneAppraiseResult::className(), ['appraise_id' => 'id']);
    }
}
