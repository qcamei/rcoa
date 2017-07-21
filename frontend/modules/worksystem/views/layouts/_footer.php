<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

?>

<div class="controlbar worksystem">
    <div class="footer-navbar">
        <div class="container">
            <?php
                $controllerId = Yii::$app->controller->id;          //当前控制器
                $actionId = Yii::$app->controller->action->id;      //当前行为方法
                $selectClass = ' footer-menu-bg';             //选择样式
                $menu = [
                    /*[   
                        'controllerId'=>'default',                          //控制ID
                        'name'=>'主页',                                     //名称
                        'icon'=>'/filedata/demand/image/home.png',          //图标路径
                        'options'=>['/demand/default'],                     //跳转选项，第一索引为地址，第二起为传参
                        'class'=>'footer-menu-item',                 //样式
                    ],*/
                    [   
                        'controllerId'=>'task',                             //控制ID
                        'name'=>'任务',                                     //名称
                        'icon'=>'/filedata/demand/image/list-check.png',    //图标路径
                        'options'=>[
                            '/worksystem/task/index', 
                            'create_by' => Yii::$app->user->id, 
                            'producer' => Yii::$app->user->id, 
                            'assign_people' => Yii::$app->user->id,
                            'status' => WorksystemTask::STATUS_DEFAULT,
                            'mark' => false,
                        ],                                                  //跳转选项，第一索引为地址，第二起为传参
                        'class'=>'footer-menu-item',                 //样式
                    ],
                    /*[   
                        'controllerId'=>'statistics',                       //控制ID
                        'name'=>'统计',                                     //名称
                        'icon'=>'/filedata/demand/image/statistics.png',    //图标路径
                        'options'=>['/demand/statistics'],                  //跳转选项，第一索引为地址，第二起为传参
                        'class'=>'footer-menu-item',                 //样式
                    ],*/
                    [   //任务页-子按钮-新建
                        'controllerId'=>'task',                             //控制ID
                        'name'=>'创建任务',                                     //名称
                        'icon'=>'/filedata/demand/image/create.png',        //图标路径
                        'options'=>['/worksystem/task/create'],                 //跳转选项，第一索引为地址，第二起为传参
                        'class'=>'footer-menu-item submenu-right',   //样式
                        /**
                        * 创建任务 按钮必须满足以下条件：
                        * 1、操作方法必须是 【task】
                        * 2、必须拥有创建权限
                        */
                        'condition'=> Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_CREATE)  //权限条件
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
</div>

<?php
    WorksystemAssets::register($this);
?>
