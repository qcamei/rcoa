<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use frontend\modules\multimedia\utils\MultimediaTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $demand MultimediaTool */
?>

<div class="controlbar footer-demand-navbar">
    <div class="container">
        <?php
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            $selectClass = ' footer-demand-menu-bg';             //选择样式
            $menu = [
                [   
                    'controllerId'=>'default',                          //控制ID
                    'name'=>'主页',                                     //名称
                    'icon'=>'/filedata/demand/image/home.png',          //图标路径
                    'options'=>['/demand/default'],                     //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item',                 //样式
                ],
                [   
                    'controllerId'=>'task',                             //控制ID
                    'name'=>'任务',                                     //名称
                    'icon'=>'/filedata/demand/image/list-check.png',    //图标路径
                    'options'=>[
                        '/demand/task/index', 
                        'create_by' => Yii::$app->user->id, 
                        'undertake_person' => Yii::$app->user->id, 
                        'auditor' => Yii::$app->user->id
                    ],                                                  //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item',                 //样式
                ],
                [   
                    'controllerId'=>'statistics',                       //控制ID
                    'name'=>'统计',                                     //名称
                    'icon'=>'/filedata/demand/image/statistics.png',    //图标路径
                    'options'=>['/demand/statistics'],                  //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item',                 //样式
                ],
                [   
                    'controllerId'=>['business','college','project','course','expert'],                         //控制ID
                    'name'=>'基础数据',                                 //名称
                    'icon'=>'/filedata/demand/image/data_configuration_64.png',    //图标路径
                    'options'=>['/demand/business'],                   //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item',                //样式
                ],
                [   
                    'controllerId'=> 'workitem',                         //控制ID
                    'name'=>'样例库',                                    //名称
                    'icon'=>'/filedata/demand/image/yangliku.png',    //图标路径
                    'options'=>['/demand/workitem/list'],             //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item',                //样式
                ],
                [   //任务页-子按钮-新建
                    'controllerId'=>'task',                             //控制ID
                    'name'=>'创建任务',                                     //名称
                    'icon'=>'/filedata/demand/image/create.png',        //图标路径
                    'options'=>['/demand/task/create'],                 //跳转选项，第一索引为地址，第二起为传参
                    'class'=>'footer-demand-menu-item submenu-right',   //样式
                    /**
                    * 创建任务 按钮必须满足以下条件：
                    * 1、操作方法必须是 【task】
                    * 2、必须拥有创建权限
                    */
                    'condition'=> Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE)  //权限条件
                ],
            ];
            
            foreach ($menu AS $menuItem){
                //非条件控制显示以及条件控制显示
                if(!isset($menuItem['condition']) || ($controllerId == $menuItem['controllerId'] && $menuItem['condition'])){
                    $selected = is_array($menuItem['controllerId']) ? in_array($controllerId,$menuItem['controllerId']) : $controllerId == $menuItem['controllerId'];
                    echo Html::a(
                            Html::img([$menuItem['icon']]).Html::tag('span', $menuItem['name'],['class'=>'menu-name hidden-xs']),     //创建图标与文字
                            $menuItem['options'],                                                                                     //添加跳转条件 
                            [
                                'class' => $menuItem['class'].(($selected && !isset($menuItem['condition'])) ? $selectClass : ''),
                            ]
                        );
                }
            }
        ?>
    </div>
</div>

<?php
    DemandAssets::register($this);
?>
