<?php

namespace common\models\teamwork;

use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_phase_link}}".
 *
 * @property integer $phases_id
 * @property integer $link_id
 * @property string $total
 * @property string $completed
 * @property integer $progress
 * @property string $create_by
 *
 * @property Link $linkOne
 * @property User $createBy
 * @property Phase $phases
 */
class PhaseLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_phase_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phases_id', 'link_id'], 'required'],
            [['phases_id', 'link_id', 'progress'], 'integer'],
            [['total', 'completed'], 'number'],
            [['create_by'], 'string', 'max' => 36]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phases_id' => Yii::t('rcoa/teamwork', 'Phases ID'),
            'link_id' => Yii::t('rcoa/teamwork', 'Link ID'),
            'total' => Yii::t('rcoa/teamwork', 'Total'),
            'completed' => Yii::t('rcoa/teamwork', 'Completed'),
            'progress' => Yii::t('rcoa/teamwork', 'Progress'),
            'create_by' => Yii::t('rcoa/teamwork', 'Create By'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkOne()
    {
        return $this->hasOne(Link::className(), ['id' => 'link_id']);
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
    public function getPhases()
    {
        return $this->hasOne(Phase::className(), ['id' => 'phases_id']);
    }
}
