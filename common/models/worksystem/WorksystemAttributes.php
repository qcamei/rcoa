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
    const HANDWORKINPUT = 0;
    /** 输入类型 列表选择 */
    const LISTSELECTINPUT = 1; 
    /** 输入类型 多行文本 */
    const MULTILINETEXTINPUT = 2; 

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
        self::HANDWORKINPUT => '手工录入',
        self::LISTSELECTINPUT => '列表选择',
        self::MULTILINETEXTINPUT => '多行文本',
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
            [['name', 'type', 'input_type'], 'required'],
            [['name'], 'unique'],
            [['type', 'input_type', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['value_list'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
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
     * 获取是否为【唯一】类型
     * @return boolean
     */
    public function getIsUniqueType()
    {
        return $this->type == self::UNIQUETYPE;
    }
    
    /**
     * 获取是否为【单选】类型
     * @return boolean
     */
    public function getIsSingleSelectionType()
    {
        return $this->type == self::SINGLESELECTIONTYPE;
    }
    
    /**
     * 获取是否为【复选】类型
     * @return boolean
     */
    public function getIsChecksType()
    {
        return $this->type == self::CHECKSTYPE;
    }
    
    /**
     * 获取是否为【手工录入】输入类型
     * @return boolean
     */
    public function getIsHandworkInput()
    {
        return $this->input_type == self::HANDWORKINPUT;
    }
    
    /**
     * 获取是否为【列表选择】输入类型
     * @return boolean
     */
    public function getIsListSelectInput()
    {
        return $this->input_type == self::LISTSELECTINPUT;
    }
    
    /**
     * 获取是否为【多行文本】输入类型
     * @return boolean
     */
    public function getIsMultilineTextInput()
    {
        return $this->input_type == self::MULTILINETEXTINPUT;
    }
    
    /**
     * 获取类型名称
     * @return string
     */
    public function getTypeName()
    {
        return self::$typeName[$this->type];
    }
    
    /**
     * 获取输入类型名称
     * @return string
     */
    public function getInputTypeName()
    {
        return self::$inputTypeName[$this->type];
    }
}
