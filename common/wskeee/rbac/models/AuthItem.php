<?php

namespace wskeee\rbac\models;

use common\models\System;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;


/**
 *
 * @property string $name                       名称
 * @property integer $system_id                 所属系统模块id
 * @property integer $type                      类型
 * @property string $description                描述
 * @property string $rule_name                  规则名
 * @property string $data                       数据
 * 
 * @property System $roleCategory               角色类别
 * @property Item $item 数据
 */
class AuthItem extends Model
{
    /**
     * 所属系统模块ID
     * @var integer 
     */
    public $system_id;
    /**
     * 名称
     * @var string 
     */
    public $name;
    /**
     * 类型
     * @var integer 
     */
    public $type;
    /**
     * 描述
     * @var string 
     */
    public $description;
    /**
     * 规则名称
     * @var string 
     */
    public $ruleName;
    /**
     * 数据
     * @var string 
     */
    public $data;

    /**
     * @var Item
     */
    private $_item;
    
    /**
     * 类别
     * @var array 
     */
    public static $category = [];


    /**
     *
     * @var ManagerInterface
     */
    protected $authManager;


    /**
     * 初始对象
     * @param Item $item
     * @param array $config
     */
    public function __construct($item,$config = array()) 
    {
        $this->authManager = \Yii::$app->authManager;
        $this->_item = $item;
        if($item !== null)
        {
            $this->name = $item->name;
            $this->system_id = $item->system_id;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : json_decode($time->data);
        }
        
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eblog_auth_item';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_id', 'name', 'type'], 'required'],
            [['name'],'unique','when'=>function()
                {
                    return $this->getIsNewRecord() || ($this->_item->name != $this->name);
                }],
            [['name'], 'match', 'pattern' => '/^[\w-]+$/'],
            [['type'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'ruleName'], 'string', 'max' => 64],
            [['ruleName'],'in',
                'range'=>  array_keys($this->authManager->getRules()),
                'message'=>'没有找到对应规则!'],
            [['description', 'data', 'ruleName'], 'default']
        ];
    }
    
    /**
     * 重写唯一过虑器
     */
    public function unique()
    {
        $value = $this->name;
        if($this->authManager->getRole($value) !== null || $this->authManager->getPermission($value) !== null)
        {
            $message = \Yii::t('yii', '{attribute}"{value}" has already been taken.');
            $params = [
                'attribute'=>$this->getAttributeLabel('name'),
                'value'=>$value
            ];
            $this->addError('name', \Yii::$app->getI18n()->format($message, $params, \Yii::$app->language));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'system_id' => '所属模块',
            'name' => '名称',
            'type' => '类型',
            'description' => '描述',
            'ruleName' => '规则名',
            'data' => 'Data',
            'created_at' => '创建于',
            'updated_at' => '更新于',
        ];
    }
    
    /**
     * 检查是否为新创建对象
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }
    
    /**
     * 查找角色
     * @param string $id
     * @return null|\self
     */
    public static function find($id)
    {
        $item = Yii::$app->authManager->getRole($id);
        if($item !== null)
            return new self($item);
        return null;
    }
    
    /**
     * 获取类型名
     * @param mixed $type
     * @return string|array;
     */
    public static function getTypeName($type = null)
    {
        $result = [
            Item::TYPE_ROLE => 'Role',
            Item::TYPE_PERMISSION => 'Permission'
        ];
        if($type !== null)
            return $result[$type];
        return $result;
    }

    /**
     * 保存 角色/权限到 [yii\rbac\authManager]
     */
    public function save()
    {
        if($this->validate())
        {
            if($this->_item === null)
            {
                if($this->type == Item::TYPE_ROLE){
                    $this->_item = (array)$this->authManager->createRole($this->name);
                    $this->_item += ['system_id' => null];
                }
                else{
                    $this->_item = (array)$this->authManager->createPermission ($this->name);
                    $this->_item += ['system_id' => null];
                }
                $isNew = true;
            }else
            {
                $isNew = false;
                $this->_item = (array)$this->_item;
                $oldName = $this->_item['name'];
            }
            
            $this->_item['name'] = $this->name;
            $this->_item['system_id'] = $this->system_id;
            $this->_item['description'] = $this->description;
            $this->_item['ruleName'] = $this->ruleName;
            $this->_item['data'] = $this->data === null || $this->data === '' ? null : json_decode($this->data);
            $this->_item['createdAt'] = time();
            $this->_item['updatedAt'] = time();   
            
            if($isNew){
                Yii::$app->db->createCommand()->insert('ccoa_auth_item',[
                    'name' => $this->_item['name'],
                    'system_id' => $this->_item['system_id'],
                    'type' => $this->_item['type'],
                    'description' => $this->_item['description'], 
                    'rule_name' => $this->_item['ruleName'], 
                    'data'=> $this->_item['data'], 
                    'created_at' => $this->_item['createdAt'], 
                    'updated_at' => $this->_item['updatedAt'], 
                ])->execute();
                //$this->authManager->add ($this->_item);
            }
            else{
                Yii::$app->db->createCommand()->update ('ccoa_auth_item', [
                    'name' => $this->_item['name'],
                    'system_id' => $this->_item['system_id'],
                    'type' => $this->_item['type'],
                    'description' => $this->_item['description'], 
                    'rule_name' => $this->_item['ruleName'], 
                    'data'=> $this->_item['data'], 
                    'updated_at' => $this->_item['updatedAt'], ], ['name' => $oldName])->execute();
                //$this->authManager->update ($oldName, $this->_item);
            }
            return true;
        }else
            return false;
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getRoleCategory()
    {
        return $this->hasOne(System::className(), ['id' => 'system_id']);
    }
    
    /**
     * 
     * @return yii\rbac\Item;
     */
    public function getItem()
    {
        return $this->_item;
    }
}
