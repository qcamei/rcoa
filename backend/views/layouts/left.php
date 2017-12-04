<?php

use common\models\User;
use common\widgets\Menu;

/* @var $user User */
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= WEB_ROOT.$user->avatar?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->nickname ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => '清除缓存', 'icon' => 'eraser', 'url' => ['/system_admin/cache']],
                    ['label' => '数据库备份', 'icon' => 'database', 'url' => ['/backup']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => '公共',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '模块管理', 'icon' => 'circle-o', 'url' => ['/system_admin/default'],],
                            ['label' => '配置管理', 'icon' => 'circle-o', 'url' => ['/system_admin/config'],],
                        ],
                    ],
                    [
                        'label' => '权限与组织管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '用户列表', 'icon' => 'circle-o', 'url' => ['/user_admin'],],
                            ['label' => '职位列表', 'icon' => 'circle-o', 'url' => ['/position_admin'],],
                            ['label' => '用户角色', 'icon' => 'circle-o', 'url' => ['/rbac/user-role'],],
                            ['label' => '角色列表', 'icon' => 'circle-o', 'url' => ['/rbac/role'],],
                            ['label' => '权限列表', 'icon' => 'circle-o', 'url' => ['/rbac/permission'],],
                            ['label' => '路由列表', 'icon' => 'circle-o', 'url' => ['/rbac/route'],],
                            ['label' => '分组列表', 'icon' => 'circle-o', 'url' => ['/rbac/auth-group'],],
                        ],
                    ],
                    [
                        'label' => '项目基础数据管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '行业', 'icon' => 'circle-o',  'url' => ['/framework/type']],
                            ['label' => '层次/类型', 'icon' => 'circle-o',  'url' => ['/framework/college']],
                            ['label' => '专业/工种', 'icon' => 'circle-o',  'url' => ['/framework/project']],
                            ['label' => '课程', 'icon' => 'circle-o',  'url' =>[ '/framework/course']],
                        ],
                    ],
                    [
                        'label' => '题库',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '题目列表', 'icon' => 'circle-o',  'url' => ['/question_admin']],
                        ],
                    ],
                    [
                        'label' => '团队管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '团队分类','icon' => 'circle-o', 'url' => ['/teammanage_admin/team-category']],
                            ['label' => '团队列表','icon' => 'circle-o', 'url' => ['/teammanage_admin/team']],
                        ],
                    ],
                    [
                        'label' => '拍摄模块',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '评价题目列表', 'icon' => 'circle-o', 'url' => ['/shoot_admin/appraise']],
                            ['label' => '场地列表', 'icon' => 'circle-o', 'url' => ['/shoot_admin/site']],
                        ],
                    ],
                    [
                        'label' => '需求模块',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '审核人列表','icon' => 'circle-o', 'url' => ['/demand_admin/default']],
                            ['label' => '工作项模版类型','icon' => 'circle-o', 'url' => ['/demand_admin/templatetype']],
                            ['label' => '工作项模版','icon' => 'circle-o', 'url' => ['/demand_admin/workitem']],
                            ['label' => '权重模版','icon' => 'circle-o', 'url' =>[ '/demand_admin/weight']],
                            ['label' => '类别','icon' => 'circle-o', 'url' => ['/workitem_admin/type']],
                            ['label' => '工作项','icon' => 'circle-o', 'url' => ['/workitem_admin/default']],
                            ['label' => '价值','icon' => 'circle-o', 'url' => ['/workitem_admin/cost']],
                            ['label' => '工作项和权重','icon' => 'circle-o', 'url' => ['/demand_admin/import']],
                        ],
                    ],
                    [
                        'label' => '开发模块',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '阶段列表', 'icon' => 'circle-o',  'url' => ['/teamwork_admin/phase']],
                        ],
                    ],
                    [
                        'label' => '任务模块',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '审核人列表','icon' => 'circle-o', 'url' => ['/worksystem_admin/assign-team']],
                            ['label' => '任务类别列表','icon' => 'circle-o', 'url' => ['/worksystem_admin/task-type']],
                            ['label' => '内容信息列表','icon' => 'circle-o', 'url' =>[ '/worksystem_admin/content']],
                            ['label' => '附加属性列表','icon' => 'circle-o', 'url' =>[ '/worksystem_admin/attributes']],
                            ['label' => '附加属性模版','icon' => 'circle-o', 'url' => ['/worksystem_admin/attributes-template']],
                        ],
                    ],
                    [
                        'label' => '在线制作课程平台',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '信息统计','icon' => 'circle-o', 'url' => ['/mconline_admin/default']],
                            ['label' => '文件列表','icon' => 'circle-o', 'url' => ['/mconline_admin/uploadfile']],
                            ['label' => '活动类型列表','icon' => 'circle-o', 'url' => ['/mconline_admin/activity-type']],
                            ['label' => '日常任务日志','icon' => 'circle-o', 'url' => ['/mconline_admin/task-log']],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
