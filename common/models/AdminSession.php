<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_session}}".
 *
 * @property integer $session_id
 * @property integer $id
 * @property string $session_token
 */
class AdminSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_session}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'session_token'], 'required'],
            [['session_token'], 'string', 'max' => 56],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_id' => 'Session ID',
            'id' => 'ID',
            'session_token' => 'Session Token',
        ];
    }
}
