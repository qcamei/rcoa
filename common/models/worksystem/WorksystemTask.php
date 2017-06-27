<?php

namespace common\models\worksystem;

use Yii;

/**
 * This is the model class for table "{{%worksystem_task}}".
 *
 * @property integer $id
 * @property integer $item_type_id
 * @property integer $item_id
 * @property integer $item_child_id
 * @property integer $course_id
 * @property string $name
 * @property integer $level
 * @property integer $is_epiboly
 * @property string $budget_cost
 * @property string $reality_cost
 * @property string $budget_bonus
 * @property string $reality_bonus
 * @property string $plan_end_time
 * @property integer $external_team
 * @property integer $status
 * @property integer $progress
 * @property integer $create_team
 * @property string $create_by
 * @property integer $index
 * @property integer $is_delete
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $finished_at
 * @property string $des
 *
 * @property WorksystemAddAttributes[] $worksystemAddAttributes
 * @property WorksystemCheck[] $worksystemChecks
 * @property WorksystemContentinfo[] $worksystemContentinfos
 * @property WorksystemOperation[] $worksystemOperations
 * @property User $createBy
 * @property FrameworkItemType $itemType
 * @property FrameworkItem $item
 * @property FrameworkItem $itemChild
 * @property FrameworkItem $course
 * @property Team $createTeam
 * @property Team $externalTeam
 * @property WorksystemTaskProducer[] $worksystemTaskProducers
 */
class WorksystemTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'level', 'is_epiboly', 'external_team', 'status', 'progress', 'create_team', 'index', 'is_delete', 'created_at', 'updated_at', 'finished_at'], 'integer'],
            [['budget_cost', 'reality_cost', 'budget_bonus', 'reality_bonus'], 'number'],
            [['des'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['plan_end_time'], 'string', 'max' => 60],
            [['create_by'], 'string', 'max' => 36],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FrameworkItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => FrameworkItem::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => FrameworkItem::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => FrameworkItem::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['create_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['create_team' => 'id']],
            [['external_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['external_team' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'item_type_id' => Yii::t('rcoa/worksystem', 'Item Type ID'),
            'item_id' => Yii::t('rcoa/worksystem', 'Item ID'),
            'item_child_id' => Yii::t('rcoa/worksystem', 'Item Child ID'),
            'course_id' => Yii::t('rcoa/worksystem', 'Course ID'),
            'name' => Yii::t('rcoa/worksystem', 'Name'),
            'level' => Yii::t('rcoa/worksystem', 'Level'),
            'is_epiboly' => Yii::t('rcoa/worksystem', 'Is Epiboly'),
            'budget_cost' => Yii::t('rcoa/worksystem', 'Budget Cost'),
            'reality_cost' => Yii::t('rcoa/worksystem', 'Reality Cost'),
            'budget_bonus' => Yii::t('rcoa/worksystem', 'Budget Bonus'),
            'reality_bonus' => Yii::t('rcoa/worksystem', 'Reality Bonus'),
            'plan_end_time' => Yii::t('rcoa/worksystem', 'Plan End Time'),
            'external_team' => Yii::t('rcoa/worksystem', 'External Team'),
            'status' => Yii::t('rcoa/worksystem', 'Status'),
            'progress' => Yii::t('rcoa/worksystem', 'Progress'),
            'create_team' => Yii::t('rcoa/worksystem', 'Create Team'),
            'create_by' => Yii::t('rcoa/worksystem', 'Create By'),
            'index' => Yii::t('rcoa/worksystem', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
            'finished_at' => Yii::t('rcoa/worksystem', 'Finished At'),
            'des' => Yii::t('rcoa/worksystem', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemAddAttributes()
    {
        return $this->hasMany(WorksystemAddAttributes::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemChecks()
    {
        return $this->hasMany(WorksystemCheck::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemContentinfos()
    {
        return $this->hasMany(WorksystemContentinfo::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemOperations()
    {
        return $this->hasMany(WorksystemOperation::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(FrameworkItemType::className(), ['id' => 'item_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'item_child_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'create_team']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExternalTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'external_team']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksystemTaskProducers()
    {
        return $this->hasMany(WorksystemTaskProducer::className(), ['worksystem_task_id' => 'id']);
    }
}
