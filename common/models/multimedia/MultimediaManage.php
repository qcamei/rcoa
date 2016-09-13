<?php

namespace common\models\multimedia;

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
 * This is the model class for table "{{%multimedia_manage}}".
 *
 * @property integer $id                                ID
 * @property integer $item_type_id                      行业ID
 * @property integer $item_id                           层次/类型ID
 * @property integer $item_child_id                     专业/工种ID
 * @property integer $course_id                         课程ID
 * @property string $name                               任务名称
 * @property integer $video_length                      成品视频时长
 * @property integer $progress                          进度
 * @property string $proportion                         比例
 * @property integer $content_type                      任务类型
 * @property string $carry_out_time                     要求完成时间
 * @property integer $level                             等级
 * @property integer $make_team                         制作团队
 * @property integer $status                            状态
 * @property string $path                               框架表路径
 * @property integer $create_team                       创建团队
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * @property string $des                                描述
 *
 * @property MultimediaProportion $contentType          获取任务类型
 * @property Team $createTeam                           获取创建团队
 * @property Item $itemChild                            获取专业/工种
 * @property Item $item                                 获取层次/类型
 * @property ItemType $itemType                         获取行业
 * @property Team $makeTeam                             获取制作团队
 * @property Item $course                               获取课程
 * @property User $createBy                             获取创建者
 * @property MultimediaProducer[] $multimediaProducers  获取所有制作人
 * @property TeamMember[] $teamMember                   获取所有团队成员
 */
class MultimediaManage extends ActiveRecord
{
    /** 普通等级 */
    const LEVEL_ORDINARY = 0;
    /** 加急等级 */
    const LEVEL_URGENT = 1;

    /**
     * 等级名称
     * @var  array
     */
    public static $levelName = [
        self::LEVEL_ORDINARY => '普通',
        self::LEVEL_URGENT => '加急',
    ];

        /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_manage}}';
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
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'name', 'video_length', 'content_type', 'level'], 'required'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'video_length', 'progress', 'content_type', 'level', 'make_team', 'status', 'create_team', 'created_at', 'updated_at'], 'integer'],
            [['proportion'], 'number'],
            [['name', 'path', 'des'], 'string', 'max' => 255],
            [['carry_out_time'], 'string', 'max' => 60],
            [['create_by'], 'string', 'max' => 36],
            [['content_type'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaProportion::className(), 'targetAttribute' => ['content_type' => 'id']],
            [['create_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['create_team' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['make_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['make_team' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'item_type_id' => Yii::t('rcoa/multimedia', 'Item Type ID'),
            'item_id' => Yii::t('rcoa/multimedia', 'Item ID'),
            'item_child_id' => Yii::t('rcoa/multimedia', 'Item Child ID'),
            'course_id' => Yii::t('rcoa/multimedia', 'Course ID'),
            'name' => Yii::t('rcoa/multimedia', 'Name'),
            'video_length' => Yii::t('rcoa/multimedia', 'Video Length'),
            'progress' => Yii::t('rcoa/multimedia', 'Progress'),
            'proportion' => Yii::t('rcoa/multimedia', 'Proportion'),
            'content_type' => Yii::t('rcoa/multimedia', 'Content Type'),
            'carry_out_time' => Yii::t('rcoa/multimedia', 'Carry Out Time'),
            'level' => Yii::t('rcoa/multimedia', 'Level'),
            'make_team' => Yii::t('rcoa/multimedia', 'Make Team'),
            'status' => Yii::t('rcoa/multimedia', 'Status'),
            'path' => Yii::t('rcoa/multimedia', 'Path'),
            'create_team' => Yii::t('rcoa/multimedia', 'Create Team'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
            'des' => Yii::t('rcoa/multimedia', 'Des'),
        ];
    }

    /**
     * 获取任务类型
     * @return ActiveQuery
     */
    public function getContentType()
    {
        return $this->hasOne(MultimediaProportion::className(), ['id' => 'content_type']);
    }

    /**
     * 获取创建团队
     * @return ActiveQuery
     */
    public function getCreateTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'create_team']);
    }

    /**
     * 获取专业/工种
     * @return ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_child_id']);
    }

    /**
     * 获取层次/类型
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * 获取行业
     * @return ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }

    /**
     * 获取制作团队
     * @return ActiveQuery
     */
    public function getMakeTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'make_team']);
    }

    /**
     * 获取课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::className(), ['id' => 'course_id']);
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
     * 获取所有制作人
     * @return ActiveQuery
     */
    public function getMultimediaProducers()
    {
        return $this->hasMany(MultimediaProducer::className(), ['task_id' => 'id']);
    }

    /**
     * 获取所有团队成员
     * @return ActiveQuery
     */
    public function getTeamMember()
    {
        return $this->hasMany(TeamMember::className(), ['u_id' => 'u_id'])->viaTable('{{%multimedia_producer}}', ['task_id' => 'id']);
    }
}
