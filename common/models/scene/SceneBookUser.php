<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_book_user}}".
 *
 * @property string $id
 * @property string $book_id        预定ID
 * @property integer $role          角色：1接洽人，2摄影师
 * @property string $user_id        用户ID
 * @property integer $is_primary    是否为主要角色：0否，1是
 * @property integer $sort_order    排序
 * @property integer $is_delete     是否已删除：0否，1是
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneBook $book
 */
class SceneBookUser extends ActiveRecord
{
    public static $roleName = [
        1 => '接洽人',
        2 => '摄影师',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_book_user}}';
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
            [['book_id', 'user_id'], 'required'],
            [['role', 'is_primary', 'sort_order', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['book_id'], 'string', 'max' => 32],
            [['user_id'], 'string', 'max' => 36],
            [['book_id', 'role', 'user_id'], 'unique', 'targetAttribute' => ['book_id', 'role', 'user_id'], 'message' => 'The combination of Book ID, Role and User ID has already been taken.'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'is_primary' => Yii::t('app', 'Is Primary'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_delete' => Yii::t('app', 'Is Delete'),
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
}
