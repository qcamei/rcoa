<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_recent_contacts}}".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $contacts_id
 * @property string $created_at
 * @property string $updated_at
 */
class McbsRecentContacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_recent_contacts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'contacts_id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'contacts_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'contacts_id' => Yii::t('app', 'Contacts ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
