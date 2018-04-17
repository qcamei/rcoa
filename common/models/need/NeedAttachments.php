<?php

namespace common\models\need;

use Yii;

/**
 * This is the model class for table "{{%need_attachments}}".
 *
 * @property string $id
 * @property string $need_task_id   需求ID
 * @property string $upload_file_id 附件ID
 * @property int $is_del            是否删除：0否 1是
 */
class NeedAttachments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_attachments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_del'], 'integer'],
            [['need_task_id', 'upload_file_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'need_task_id' => Yii::t('app', 'Need Task ID'),
            'upload_file_id' => Yii::t('app', 'Upload File ID'),
            'is_del' => Yii::t('app', 'Is Del'),
        ];
    }
}
