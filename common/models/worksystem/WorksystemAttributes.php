<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_attributes}}".
 *
 * @property integer $id                                        id
 * @property integer $worksystem_task_type_id                   引用工作系统任务类型id
 * @property string $name                                       属性名称
 * @property integer $type                                      属性类型：0 唯一属性，1 单选属性，2 复选属性
 * @property integer $input_type                                输入类型：0 手工录入， 1 从列表中选择， 2 多行文本框
 * @property string $value_list                                 候选列表：当【类型】为【单选】或者【复选】有效
 * @property integer $index                                     索引
 * @property integer $is_delete                                 是否删除
 * @property integer $created_at                                创建于
 * @property integer $updated_at                                更新于
 *
 * @property WorksystemAddAttributes[] $worksystemAddAttributes 获取所有的工作系统任务附加附加属性
 * @property WorksystemTaskType $worksystemTaskType             获取工作系统任务类型
 */
class WorksystemAttributes extends ActiveRecord
{
    
    /** 属性类型 唯一 */
    const UNIQUETYPE = 0;
    /** 属性类型 单选 */
    const SINGLESELECTIONTYPE = 1; 
    /** 属性类型 复选 */
    const CHECKSTYPE = 2; 
    
    /** 输入类型 手工录入 */
    const INPUTMANUALENTRY = 0;
    /** 输入类型 列表中选择 */
    const INPUTLISTSELECTION = 1; 
    /** 输入类型 多行文本 */
    const INPUTMULTILINETEXT = 2; 

    /**
     * 类别名称
     * @var array 
     */
    public static $typeName = [
        self::UNIQUETYPE => '唯一',
        self::SINGLESELECTIONTYPE => '单选',
        self::CHECKSTYPE => '复选',
    ];
    
    /**
     * 输入类别名称
     * @var array 
     */
    public static $inputTypeName = [
        self::INPUTMANUALENTRY => '手工录入',
        self::INPUTLISTSELECTION => '列表中选择',
        self::INPUTMULTILINETEXT => '多行文本',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_attributes}}';
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
            [['worksystem_task_type_id', 'name', 'type', 'input_type'], 'required'],
            [['worksystem_task_type_id', 'type', 'input_type', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['value_list'], 'string'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::t('rcoa', 'Name'),
            'type' => Yii::t('rcoa', 'Type'),
            'input_type' => Yii::t('rcoa/worksystem', 'Input Type'),
            'value_list' => Yii::t('rcoa/worksystem', 'Value List'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取所有的工作系统任务附加附加属性
     * @return ActiveQuery
     */
    public function getWorksystemAddAttributes()
    {
        return $this->hasMany(WorksystemAddAttributes::className(), ['worksystem_attributes_id' => 'id']);
    }

    /**
     * 获取工作系统任务类型
     * @return ActiveQuery
     */
    public function getWorksystemTaskType()
    {
        return $this->hasOne(WorksystemTaskType::className(), ['id' => 'worksystem_task_type_id']);
    }
}
