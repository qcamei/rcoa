<?php

namespace common\models\teamwork;

use common\models\teamwork\Phase;
use common\models\teamwork\PhaseLink;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_link}}".
 *
 * @property integer $id        ID
 * @property integer $phase_id  阶段ID
 * @property string $name       名称
 * @property integer $type      类型
 * @property array $types       类型名称
 * @property string $unit       单位
 * @property integer $progress  进度
 * @property string $create_by  创建者
 *
 * @property User $createBy     获取创建者
 * @property Phase $phase  获取阶段
 * @property PhaseLink[] $phaseLinks   
 * @property Phase[] $phases   获取所有阶段
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
        return '{{%teamwork_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phase_id', 'type', 'progress'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 16],
            [['create_by'], 'string', 'max' => 36]
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
            'progress' => Yii::t('rcoa/teamwork', 'Progress'),
            'create_by' => Yii::t('rcoa', 'Create By'),
        ];
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

    /**
     * @return ActiveQuery
     */
    public function getPhaseLinks()
    {
        return $this->hasMany(PhaseLink::className(), ['link_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhases()
    {
        return $this->hasMany(Phase::className(), ['id' => 'phases_id'])->viaTable('{{%framework_phase_link}}', ['link_id' => 'id']);
    }
}
