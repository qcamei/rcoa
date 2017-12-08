<?php

namespace common\models\helpcenter;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $id
 * @property string $category_id            分类ID
 * @property string $name                   文章名称
 * @property string $title                  文章标题
 * @property string $content                文章内容
 * @property string $view_count             查看次数
 * @property string $comment_count          回复数目
 * @property integer $can_comment           是否可以评论：0不可以，1可以
 * @property integer $is_show               是否显示：0不显示，1显示
 * @property string $like_count             点赞数
 * @property string $unlike_count           不喜欢数
 * @property string $created_by             创建人
 * @property string $created_at
 * @property string $updated_at
 */
class Post extends ActiveRecord
{
    /** 否 */
    const TYPE_NO_CHOICE = 0;
    /** 是 */
    const TYPE_YES_CHOICE = 1;
    
    /** 类型 */
    public static $TYPES = [
        self::TYPE_NO_CHOICE => '否',
        self::TYPE_YES_CHOICE => '是',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
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
            [['name', 'title', 'content'], 'required'],
            [['category_id', 'view_count', 'comment_count', 'can_comment', 'is_show', 'like_count', 'unlike_count', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 255],
            [['created_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', '{Belong}{Category}',[
                'Belong' => Yii::t('app', 'Belong'),
                'Category' => Yii::t('app', 'Category'),
            ]),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'view_count' => Yii::t('app', 'View Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'can_comment' => Yii::t('app', '{Can}{Comment}',[
                'Can' => Yii::t('app', 'Can'),
                'Comment' => Yii::t('app', 'Comment')
            ]),
            'is_show' => Yii::t('app', '{Is}{Show}', [
                'Is' => Yii::t('app', 'Is'),
                'Show' => Yii::t('app', 'Show'),
            ]),
            'like_count' => Yii::t('app', 'Like Count'),
            'unlike_count' => Yii::t('app', 'Unlike Count'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 父级
     * @return ActiveQuery
     */
    public function getParent(){
        return $this->hasOne(PostCategory::className(), ['id'=>'category_id']);
    }
    
    /**
     * 创建人
     * @return ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }
    
}
