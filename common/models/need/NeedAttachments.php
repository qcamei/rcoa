<?php

namespace common\models\need;

use common\modules\webuploader\models\Uploadfile;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_attachments}}".
 *
 * @property string $id
 * @property string $need_task_id   需求ID
 * @property string $upload_file_id 附件ID
 * @property int $is_del            是否删除：0否 1是
 * 
 * @property NeedTask $needTask     获取需求任务
 * @property Uploadfile $uploadfile 获取上传文件
 */
class NeedAttachments extends ActiveRecord
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
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getNeedTask()
    {
        return $this->hasOne(NeedTask::class, ['id' => 'need_task_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getUploadfile()
    {
        return $this->hasOne(Uploadfile::class, ['id' => 'upload_file_id']);
    }
}
