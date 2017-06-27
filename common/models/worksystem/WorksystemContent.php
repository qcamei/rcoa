<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%worksystem_content}}".
 *
 * @property integer $id                                id
 * @property integer $worksystem_task_type_id           引用工作系统任务类别id
 * @property string $type_name                          类型名称
 * @property string $icon                               图标
 * @property integer $is_new                            新建/改造：1为新建，0为改造
 * @property string $price                              单价
 * @property string $unit                               单位
 * @property string $des                                描述
 * @property integer $index                             顺序
 * @property integer $is_delete                         是否删除
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property WorksystemTaskType $worksystemTaskType     工作系统任务类别
 */
class WorksystemContent extends ActiveRecord
{
    
    /** 建设模式 新建 */
    const MODE_NEWLYBUILD = 1;
    /** 建设模式 改造 */
    const MODE_REMAKE = 0;

    /**
     * 建设模式名称
     * @var array 
     */
    public static $modeName = [
        self::MODE_NEWLYBUILD => '新建',
        self::MODE_REMAKE => '改造',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_content}}';
    }

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
            [['worksystem_task_type_id', 'type_name', 'unit'], 'required'],
            [['worksystem_task_type_id', 'is_new', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['des'], 'string'],
            [['type_name', 'icon'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 60],
            [['worksystem_task_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTaskType::className(), 'targetAttribute' => ['worksystem_task_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'worksystem_task_type_id' => Yii::t('rcoa/worksystem', 'Worksystem Task Type ID'),
            'type_name' => Yii::t('rcoa/worksystem', 'Type Name'),
            'icon' => Yii::t('rcoa/worksystem', 'Icon'),
            'is_new' => Yii::t('rcoa/worksystem', 'Is New'),
            'price' => Yii::t('rcoa/worksystem', 'Price'),
            'unit' => Yii::t('rcoa/worksystem', 'Unit'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }
    
    public function beforeSave($insert) {
        
        if(parent::beforeSave($insert))
        {
            $upload = UploadedFile::getInstance($this, 'icon');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/worksystem/content/');
                $name = md5(rand(1,10000) + time()) .".$ext";
                $upload->saveAs($uploadpath.$name);
                $this->icon = '/filedata/worksystem/content/'.$name;
            }
            
            if(trim($this->icon) == '')
                $this->icon = $this->getOldAttribute ('icon');
            
            return true;
        }
    }

    /**
     * 工作系统任务类别
     * @return ActiveQuery
     */
    public function getWorksystemTaskType()
    {
        return $this->hasOne(WorksystemTaskType::className(), ['id' => 'worksystem_task_type_id']);
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {
        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        
        return $uploadpath;
    }
}
