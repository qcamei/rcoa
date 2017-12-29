<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_appraise}}".
 *
 * @property string $id
 * @property string $book_id        预定ID
 * @property integer $role          角色：1接洽人，2摄影师
 * @property string $q_id           题目ID
 * @property integer $value         得分
 * @property integer $index         索引
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneBook $book
 * @property SceneAppraiseResult[] $sceneAppraiseResults
 */
class SceneAppraise extends ActiveRecord
{
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
            [['role', 'q_id', 'value', 'index', 'created_at', 'updated_at'], 'integer'],
            [['book_id'], 'string', 'max' => 32],
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
            'value' => Yii::t('app', 'Value'),
            'index' => Yii::t('app', 'Index'),
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
    public function getSceneAppraiseResults()
    {
        return $this->hasMany(SceneAppraiseResult::className(), ['appraise_id' => 'id']);
    }
}
