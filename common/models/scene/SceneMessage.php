<?php

namespace common\models\scene;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_message}}".
 *
 * @property string $id         留言ID
 * @property string $title      标题
 * @property string $content    留言内容
 * @property string $created_by 创建人
 * @property string $book_id    关联的课程ID
 * @property string $reply_id   回复的留言ID
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneBook $book
 * @property User $createdBy
 */
class SceneMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_message}}';
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
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['reply_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['created_by'], 'string', 'max' => 36],
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
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'book_id' => Yii::t('app', 'Book ID'),
            'reply_id' => Yii::t('app', 'Reply ID'),
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
                $this->created_by = Yii::$app->user->id;
            }
            
            return true;
        }else
            return false;
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
