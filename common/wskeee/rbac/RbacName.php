<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\rbac;

/**
 * Description of RbacName
 *
 * @author Administrator
 */
class RbacName {
    /** 管理员 */
    const ROLE_ADMIN = 'r_admin';
    /** 所有用户 平台所有合法的账号 */
    const ROLE_USERS = 'r_users';
    /** 预约拍摄系统管理员 */
    const ROLE_SHOOT_MANAGER = 'r_shoot_manager';
    /** 课程中心组 课程中心所有用户，不包括外聘老师账号 */
    const ROLE_CC_USERS = 'r_cc_users';
    /** 接洽人 */
    const ROLE_CONTACT = 'r_contact';
    /** 游客 */
    const ROLE_GUEST = 'r_guest';
    /** 多媒体制作师 Multimedia Production */
    const ROLE_MP = 'r_mp';
    /** 多媒体制作组长 Multimedia Production leader */
    const ROLE_MP_LEADER = 'r_mp_leader';
    /** 新闻事件管理员 */
    const ROLE_NEW_PUBLISHER = 'r_new_publisher';
    /** 摄影组长 */
    const ROLE_SHOOT_LEADER = 'r_shoot_leader';
    /** 摄影师 */
    const ROLE_SHOOT_MAN = 'r_shoot_man';
    /** 编导 */
    const ROLE_WD = 'r_wd';
    /** 编导组长 */
    const ROLE_WD_LEADER = 'r_wd_leader';
    /** 老师 */
    const ROLE_TEACHERS = 'r_teachers';
    /** 项目系统管理员 */
    const ROLE_PROJECT_MANAGER = 'r_project_manager';
    /** 课程开发-开发管理员 */
    const ROLE_TEAMWORK_DEVELOP_MANAGER = 'r_teamwork_develop_manager';
    /** 课程开发-课程开发经理 */
    const ROLE_TEAMWORK_DEVELOP_LEADER = 'r_teamwork_develop_leader';
    /** 课程开发-周报开发者 */
    const ROLE_TEAMWORK_WEEKLY_DEVELOPER = 'r_teamwork_weekly_developer';
    /** 课程开发-课程录入人 */
    const ROLE_TEAMWORK_COURSE_INPUTPERSON   = 'r_teamwork_course_inputperson';
    /** 多媒体-任务发布者 */
    const ROLE_MULTIMEDIA_PROMULGATOR = 'r_multimedia_promulgator';
    /** 多媒体-任务指派人 */
    const ROLE_MULTIMEDIA_ASSIGNPERSON = 'r_multimedia_assignperson';
    /** 课程需求-审核人 */
    const ROLE_DEMAND_AUDITOR = 'r_demand_auditor';
    /** 课程需求-发布者 */
    const ROLE_DEMAND_PROMULGATOR = 'r_demand_promulgator';
    /** 课程需求-承接人 */
    const ROLE_DEMAND_UNDERTAKE_PERSON = 'r_demand_undertake_person';
    /** 课程需求-基础数据管理人 */
    const ROLE_DEMAND_BASEDATA_ADMIN = 'r_demand_basedata_admin';
    /** 课程需求-需求任务管理人 */
    const ROLE_DEMAND_TASK_ADMIN = 'r_demand_task_admin';




    /** 平台新闻发布 */
    const PERMSSIONT_NEW_PUBLISH = 'p_new_publish';
    /** 权限管理 */
    const PERMSSIONT_RBAC_ADMIN = 'p_rbac_admin';
    /** 拍摄-管理 */
    const PERMSSIONT_SHOOT_ADMIN = 'p_shoot_admin';
    /** 拍摄-摄影师分派 */
    const PERMSSIONT_SHOOT_ASSIGN = 'p_shoot_assign';
    /** 拍摄-取消预约 */
    const PERMSSIONT_SHOOT_CANCEL = 'p_shoot_cancel';
    /** 拍摄-创建预约 */
    const PERMSSIONT_SHOOT_CREATE = 'p_shoot_create';
    /** 拍摄-评价 */
    const PERMSSIONT_SHOOT_APPRAISE = 'p_shoot_appraise';
    /** 拍摄-评价【接洽人】【摄影师】特有*/
    const PERMSSIONT_SHOOT_OWN_APPRAISE = 'p_shoot_own_appraise';
    /** 拍摄-查看预约 */
    const PERMSSIONT_SHOOT_INDEX = 'p_shoot_index';
    /** 拍摄-取消自己创建的预约 */
    const PERMSSIONT_SHOOT_OWN_CANCEL = 'p_shoot_own_cancel';
    /** 拍摄-更新自己创建的预约 */
    const PERMSSIONT_SHOOT_OWN_UPDATE = 'p_shoot_own_update';
    /** 拍摄-更新预约 */
    const PERMSSIONT_SHOOT_UPDATE = 'p_shoot_update';
    /** 课程开发-任务创建 */
    const PERMSSION_TEAMWORK_TASK_CREATE = 'p_teamwork_task_create';
    /** 课程开发-任务更新 */
    const PERMSSION_TEAMWORK_TASK_UPDATE = 'p_teamwork_taks_update';
    /** 课程开发-任务配置 */
    const PERMSSION_TEAMWORK_TASK_COLLOCATION = 'p_teamwork_taks_collocation';
    /** 课程开发-任务开始 */
    const PERMSSION_TEAMWORK_TASK_START = 'p_teamwork_taks_start';
    /** 课程开发-周报创建 */
    const PERMSSION_TEAMWORK_WEEKLY_CREATE = 'p_teamwork_weekly_create';
    /** 课程开发-周报编辑 */
    const PERMSSION_TEAMWORK_WEEKLY_UPDATE = 'p_teamwork_weekly_update';
    /** 课程开发-课程录入 */
    const PERMSSION_TEAMWORK_COURSE_INPUT = 'p_teamwork_course_input';
    /** 课程开发-任务完成 */
    const PERMSSION_TEAMWORK_TASK_COMPLETE = 'p_teamwork_taks_complete';
    /** 课程开发-课程移交 */
    const PERMSSION_TEAMWORK_COURSE_TRANSFER = 'p_teamwork_course_transfer';
    /** 课程开发-课程恢复 */
    const PERMSSION_TEAMWORK_COURSE_RESTORE = 'p_teamwork_course_restore';
    /** 多媒体-任务创建 */
    const PERMSSION_MULTIMEDIA_TASK_CREATE = 'p_multimedia_task_create';
    /** 多媒体-任务更新 */
    const PERMSSION_MULTIMEDIA_TASK_UPDATE = 'p_multimedia_task_update';
    /** 多媒体-取消任务 */
    const PERMSSION_MULTIMEDIA_TASK_CANCEL = 'p_multimedia_task_cancel';
    /** 多媒体-完成任务 */
    const PERMSSION_MULTIMEDIA_TASK_COMPLETE = 'p_multimedia_task_complete';
    /** 多媒体-任务添加审核 */
    const PERMSSION_MULTIMEDIA_TASK_CREATE_CHECK = 'p_multimedia_task_create_check';
    /** 多媒体-任务更新审核 */
    const PERMSSION_MULTIMEDIA_TASK_UPDATE_CHECK = 'p_multimedia_task_update_check';
    /** 多媒体-任务删除审核 */
    const PERMSSION_MULTIMEDIA_TASK_DELETE_CHECK = 'p_multimedia_task_delete_check';
    /** 多媒体-任务指派 */
    const PERMSSION_MULTIMEDIA_TASK_ASSIGN = 'p_multimedia_task_assign';
    /** 课程需求-任务创建 */
    const PERMSSION_DEMAND_TASK_CREATE = 'p_demand_task_create';
    /** 课程需求-任务更新 */
    const PERMSSION_DEMAND_TASK_UPDATE = 'p_demand_task_update';
    /** 课程需求-任务创建课程产品 */
    const PERMSSION_DEMAND_TASK_CREATE_PRODUCT = 'p_demand_task_create_product';
    /** 课程需求-任务删除课程产品 */
    const PERMSSION_DEMAND_TASK_DELETE_PRODUCT = 'p_demand_task_delete_product';
    /** 课程需求-取消任务 */
    const PERMSSION_DEMAND_TASK_CANCEL = 'p_demand_task_cancel';
    /** 课程需求-完成任务 */
    const PERMSSION_DEMAND_TASK_COMPLETE = 'p_demand_task_complete';
    /** 课程需求-任务添加审核 */
    const PERMSSION_DEMAND_TASK_CREATE_CHECK = 'p_demand_task_create_check';
    /** 课程需求-任务更新审核 */
    const PERMSSION_DEMAND_TASK_UPDATE_CHECK = 'p_demand_task_update_check';
    /** 课程需求-任务删除审核 */
    const PERMSSION_DEMAND_TASK_DELETE_CHECK = 'p_demand_task_delete_check';
    /** 课程需求-任务提交审核 */
    const PERMSSION_DEMAND_TASK_SUBMIT_CHECK = 'p_demand_task_submit_check';
    /** 课程需求-任务添加验收 */
    const PERMSSION_DEMAND_TASK_CREATE_ACCEPTANCE = 'p_demand_task_create_acceptance';
    /** 课程需求-任务更新验收 */
    const PERMSSION_DEMAND_TASK_UPDATE_ACCEPTANCE = 'p_demand_task_update_acceptance';
    /** 课程需求-任务删除验收 */
    const PERMSSION_DEMAND_TASK_DELETE_ACCEPTANCE = 'p_demand_task_delete_acceptance';
    /** 课程需求-任务提交验收 */
    const PERMSSION_DEMAND_TASK_SUBMIT_ACCEPTANCE = 'p_demand_task_submit_acceptance';
    /** 课程需求-任务承接 */
    const PERMSSION_DEMAND_TASK_UNDERTAKE = 'p_demand_task_undertake';
    /** 课程需求-添加课程开发任务 */
    const PERMSSION_DEMAND_TASK_DEVELOP = 'p_demand_task_develop';
    /** 课程需求-任务恢复 */
    const PERMSSION_DEMAND_TASK_RESTORE = 'p_demand_task_restore';
    /** 课程需求-基础数据-添操作 */
    const PERMSSION_DEMAND_BASEDATA_CREATE = 'p_demand_basedata_create';
    /** 课程需求-基础数据-删操作 */
    const PERMSSION_DEMAND_BASEDATA_DELETE = 'p_demand_basedata_delete';
    /** 课程需求-基础数据-改操作 */
    const PERMSSION_DEMAND_BASEDATA_UPDATE = 'p_demand_basedata_update';
    /** 课程需求-基础数据-查操作 */
    const PERMSSION_DEMAND_BASEDATA_READ = 'p_demand_basedata_read';
    /** 课程需求-需求任务-编辑操作 */
    const PERMSSION_DEMAND_TASK_EDIT = 'p_demand_task_edit';
    
    
}
