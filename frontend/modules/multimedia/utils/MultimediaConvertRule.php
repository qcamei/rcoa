<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\multimedia\utils;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaTypeProportion;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of MultimediaConvertRule
 *
 * @author Administrator
 */
class MultimediaConvertRule {
    
    private static $instance=null;
    /**
     * 所有比例集合
     * @var Array[String:Array[]] 
     * Array ( 
	[1] => Array ( 
		[2016-10] => Array ( 
			[0] => Array ( [content_type] => 1 [proportion] => 30.00 [target_month] => 2016-09 [name] => 蓝箱 ) ) 
		[2016-09] => Array ( 
			[0] => Array ( [content_type] => 1 [proportion] => 20.00 [target_month] => 2016-10 [name] => 蓝箱 ) ) ) 
	[3] => Array ( 
		[2016-10] => Array ( 
			[0] => Array ( [content_type] => 3 [proportion] => 10.00 [target_month] => 2016-09 [name] => 外拍 ) ) 
		[2016-09] => Array ( 
			[0] => Array ( [content_type] => 3 [proportion] => 5.00 [target_month] => 2016-10 [name] => 外拍 ) ) ) 
	[2] => Array ( 
		[2016-10] => Array ( 
			[0] => Array ( [content_type] => 2 [proportion] => 20.00 [target_month] => 2016-09 [name] => 板书 ) ) 
		[2016-09] => Array ( 
			[0] => Array ( [content_type] => 2 [proportion] => 10.00 [target_month] => 2016-10 [name] => 板书 ) ) ) 
	[6] => Array ( 
		[2016-09] => Array ( 
			[0] => Array ( [content_type] => 6 [proportion] => 0.10 [target_month] => 2016-09 [name] => 转换 ) ) ) )
     */
    private $ruleProportion;
    
    public function __construct(){
        $this->findRuleProportion();
    }
    /**
     * 查找所有比例
     */
    private function findRuleProportion(){
        $query = (new Query())
                ->select([
                    'Proportion.content_type',
                    'FORMAT(Proportion.proportion * 10,0) / 10 AS proportion',
                    'Proportion.target_month',
                    'CT.name'])
                ->from(['Proportion'=>  MultimediaTypeProportion::tableName()])
                ->leftJoin(['CT'=>  MultimediaContentType::tableName()], 'CT.id=Proportion.content_type')
                ->orderBy('Proportion.target_month DESC');
        $this->ruleProportion = ArrayHelper::index($query->all(Yii::$app->db), null, ['content_type']);
    }
    
    /**
     * 获取类型对应月份标准工作量转换比例
     * @param int $type
     * @param string $target_month  默认空，为当月比例
     * @return real
     */
    public function getRuleProportion($type,$target_month=null){
        if($target_month == null)
            $target_month = date('Y-m', time());
        //如果当月有设置直接返回当月比例
        if(isset($this->ruleProportion[$type]))
        {
            foreach($this->ruleProportion[$type] as $index => $proportion)
            {
                if($target_month >= $proportion['target_month'])
                {
                    return $proportion['proportion'];
                }
            }
        }
        return 1.0;
    }
    
    /**
     * 获取单例
     * @return MultimediaConvertRule
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MultimediaConvertRule();
        }
        return self::$instance;
    }

}
