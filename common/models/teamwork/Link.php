<?php

namespace common\models\teamwork;

use common\models\teamwork\Phase;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_link_template}}".
 *
 * @property integer $id            ID
 * @property integer $phase_id      阶段ID
 * @property string $name           名称
 * @property integer $type          类型
 * @property array $types           类型名称
 * @property string $unit           单位
 * @property string $create_by      创建者
 * @property integer $total         总数
 * @property integer $completed     已完成数
 * @property integer $index         索引
 * @property string $is_delete      是否删除
 *
 * @property CourseLink[] $courseLinks     获取所有课程阶段
 * @property User $createBy                 获取创建者
 * @property Phase $phase                   获取阶段
 */
class Link extends ActiveRecord
{
    /** 状态 */
    const STATUS = 0;
    /** 数量 */
    const AMOUNT = 1;
    /** 类型 */
    public $types = [
        self::STATUS => '状态',
        self::AMOUNT => '数量',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_link_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phase_id', 'type', 'index', 'total', 'completed'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 16],
            [['is_delete'], 'string', 'max' => 4],
            [['create_by'], 'string', 'max' => 36],
            [['phase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phase::className(), 'targetAttribute' => ['phase_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'phase_id' => Yii::t('rcoa/teamwork', 'Phase ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'type' => Yii::t('rcoa', 'Type'),
            'unit' => Yii::t('rcoa/teamwork', 'Unit'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'index' => Yii::t('rcoa', 'Index'),
            'total' => Yii::t('rcoa/teamwork', 'Total'),
            'completed' => Yii::t('rcoa/teamwork', 'Completed'),
            'is_delete' => Yii::t('rcoa/teamwork', 'Is Delete'),
        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getCourseLinks()
    {
        return $this->hasMany(CourseLink::className(), ['link_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhase()
    {
        return $this->hasOne(Phase::className(), ['id' => 'phase_id']);
    }    
}
