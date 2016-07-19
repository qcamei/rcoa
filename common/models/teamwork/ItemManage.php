<?php

namespace common\models\teamwork;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_item_manage}}".
 *
 * @property integer $id                ID
 * @property integer $item_type_id      项目类别
 * @property integer $item_id           项目
 * @property integer $item_child_id     子项目
 * @property integer $team_id           创建者所在团队
 * @property string $create_by          创建者
 * @property integer $created_at        创建时间
 * @property string $forecast_time      预计上线时间
 * @property string $real_carry_out     实际完成时间
 * @property integer $status            状态
 * @property string $background         项目背景
 * @property string $use                项目用途
 * @property integer $progress          进度
 *
 * @property CourseManage[] $courseManages      获取所有课程
 * @property User $createBy                     获取创建人
 * @property Item $itemChild                    获取子项目
 * @property Item $item                         获取项目
 * @property ItemType $itemType                 获取项目类别
 * @property Team $team                         获取团队
 * @property TeamMember $teamMember             获取团队成员
 */
class ItemManage extends ActiveRecord
{
    /** 暂停 */
    const STATUS_TIME_OUT = 25;
    /** 正常 */
    const STATUS_NORMAL = 5;
    /** 完成 */
    const STATUS_CARRY_OUT = 15;
    /** 进度 */
    public $progress;

    /** 状态名 */
    public $statusName = [
        self::STATUS_NORMAL => '在建',
        self::STATUS_CARRY_OUT => '已完成',
        self::STATUS_TIME_OUT => '暂停',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_item_manage}}';
    }
    
    public function behaviors() {
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
            [['item_type_id', 'item_id', 'item_child_id', 'created_at', 'updated_at', 'status','team_id'], 'integer'],
            [['item_type_id', 'item_id', 'item_child_id'], 'required'],
            [['create_by'], 'string', 'max' => 36],
            [['forecast_time', 'real_carry_out'], 'string', 'max' => 60],
            [['background', 'use'], 'string', 'max' => 255],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' =>Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'item_type_id' => Yii::t('rcoa/teamwork', 'Item Type'),
            'item_id' => Yii::t('rcoa/teamwork', 'Item'),
            'item_child_id' => Yii::t('rcoa/teamwork', 'Item Child'),
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'forecast_time' => Yii::t('rcoa/teamwork', 'Forecast Time'),
            'real_carry_out' => Yii::t('rcoa/teamwork', 'Real Carry Out'),
            'status' => Yii::t('rcoa', 'Status'),
            'background' => Yii::t('rcoa/teamwork', 'Background'),
            'use' => Yii::t('rcoa/teamwork', 'Use'),
        ];
    }

    /**
     * 获取所有课程
     * @return ActiveQuery
     */
    public function getCourseManages()
    {
        return $this->hasMany(CourseManage::className(), ['project_id' => 'id']);
    }

    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * 获取子项目
     * @return ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_child_id']);
    }

    /**
     * 获取项目
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * 获取类别
     * @return ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }
    
    /**
     * 获取团队
     * @return ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
    
    /**
     * 获取团队成员
     * @return ActiveQuery
     */
    public function getTeamMember()
    {
        return $this->hasOne(TeamMember::className(), ['u_id' => 'create_by']);
    }
    
    /**
     * 获取状态是否为【在建】
     */
    public function getIsNormal()
    {
        return $this->status == self::STATUS_NORMAL;
    }
    
    /**
     * 获取状态是否为【暂停】
     */
    public function getIsTimeOut()
    {
        return $this->status == self::STATUS_TIME_OUT;
    }
    
    /**
     * 获取状态是否为【已完成】
     */
    public function getIsCarryOut()
    {
        return $this->status == self::STATUS_CARRY_OUT;
    }
    
    /**
     * 获取状态名称
     */
    public function getStatusName()
    {
        return $this->statusName[$this->status];
    }
    
    /**
     * 获取该条项目下所有课程是否为【完成】状态
     * @param ItemManage $model
     * @return boolean  true 为是
     */
    public function getIsCoursesStatus()
    {
        /* @var $model ItemManage */
        foreach ($this->courseManages as $value) {
            if($value->status == self::STATUS_CARRY_OUT)
                return true;
        }
        return false;
    }
}
