<?php

namespace common\models\need;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_task}}".
 *
 * @property string $id             需求ID
 * @property string $company_id     所属公司
 * @property string $business_id    行业ID
 * @property string $layer_id       层次/类型ID
 * @property string $profession_id  专业/工种ID
 * @property string $course_id      课程ID
 * @property string $task_name      需求名称
 * @property int $level             等级：0普通 1加急
 * @property double $performance_percent 绩效比值
 * @property string $need_time      需求时间
 * @property string $finish_time    完成时间
 * @property int $status        状态：100创建中 200审核中 201审改中 300待承接 301待开始 302开发中 400验收中 401验改中 500已完成
 * @property int $is_del        是否取消：0否 1是
 * @property string $save_path          成品路径
 * @property string $plan_content_cost  预计内容费用
 * @property string $plan_outsourcing_cost  预计外包费用
 * @property string $reality_content_cost   实际内容费用
 * @property string $reality_outsourcing_cost   实际外包费用
 * @property string $des            需求任务备注
 * @property string $receive_by     承接人
 * @property string $audit_by       审核人
 * @property string $created_by     创建人
 * @property string $created_at     创建时间
 * @property string $updated_at     更新时间
 */
class NeedTask extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_task}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::class
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['company_id', 'business_id', 'layer_id', 'profession_id', 'course_id', 'level', 'need_time', 'finish_time', 'status', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['performance_percent', 'plan_content_cost', 'plan_outsourcing_cost', 'reality_content_cost', 'reality_outsourcing_cost'], 'number'],
            [['id'], 'string', 'max' => 32],
            [['task_name'], 'string', 'max' => 50],
            [['save_path', 'des'], 'string', 'max' => 255],
            [['receive_by', 'audit_by', 'created_by'], 'string', 'max' => 36],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'layer_id' => Yii::t('app', 'Layer ID'),
            'profession_id' => Yii::t('app', 'Profession ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'task_name' => Yii::t('app', 'Task Name'),
            'level' => Yii::t('app', 'Level'),
            'performance_percent' => Yii::t('app', 'Performance Percent'),
            'need_time' => Yii::t('app', 'Need Time'),
            'finish_time' => Yii::t('app', 'Finish Time'),
            'status' => Yii::t('app', 'Status'),
            'is_del' => Yii::t('app', 'Is Del'),
            'save_path' => Yii::t('app', 'Save Path'),
            'plan_content_cost' => Yii::t('app', 'Plan Content Cost'),
            'plan_outsourcing_cost' => Yii::t('app', 'Plan Outsourcing Cost'),
            'reality_content_cost' => Yii::t('app', 'Reality Content Cost'),
            'reality_outsourcing_cost' => Yii::t('app', 'Reality Outsourcing Cost'),
            'des' => Yii::t('app', 'Des'),
            'receive_by' => Yii::t('app', 'Receive By'),
            'audit_by' => Yii::t('app', 'Audit By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
