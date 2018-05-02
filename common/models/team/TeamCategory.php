<?php

namespace common\models\team;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%team_category}}".
 *
 * @property string $id
 * @property string $name
 * @property string $des
 * @property string $is_delete
 */
class TeamCategory extends ActiveRecord
{
    /**
     * @name 课程中心公共团队
     * @des 课程中心没有分组或者可以工作于多个开发团队的人	
     */
    const TYPE_CCOA_COMMON_TEAM = 'ccoa_common_team';
    
    /**
     * @name 课程中心开发团队
     * @des 承接产品中心发布的制作任务
     */
    const TYPE_CCOA_DEV_TEAM = 'ccoa_dev_team';
    
    /**
     * @name 产品中心
     * @des 发布的制作任务
     */
    const TYPE_PRODUCT_CENTER = 'product_center';
    
    /**
     * @name 工作系统团队
     * @des 发布和承接工作系统任务
     */
    const TYPE_WORKSYSTEM_TEAM = 'worksystem_team';
    
    /**
     * @name 外包团队
     * @des 承接发布的外包制作任务
     */
    const TYPE_EPIBOLY_TEAM = 'epiboly_team';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'string', 'max' => 60],
            [['name', 'des'], 'string', 'max' => 255],
            [['is_delete'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'des' => Yii::t('rcoa', 'Des'),
            'is_delete' => Yii::t('rcoa/team', 'Is Delete'),
        ];
    }
}
