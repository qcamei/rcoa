<?php
namespace wskeee\rbac;
class RbacName{
	/** 课程需求-基础数据-添操作 */
	const PERMSSION_DEMAND_BASEDATA_CREATE = "p_demand_basedata_create";
	/** 课程需求-基础数据-删操作 */
	const PERMSSION_DEMAND_BASEDATA_DELETE = "p_demand_basedata_delete";
	/** 课程需求-基础数据-查操作 */
	const PERMSSION_DEMAND_BASEDATA_READ = "p_demand_basedata_read";
	/** 课程需求-基础数据-改操作 */
	const PERMSSION_DEMAND_BASEDATA_UPDATE = "p_demand_basedata_update";
	/** 课程需求-取消任务 */
	const PERMSSION_DEMAND_TASK_CANCEL = "p_demand_task_cancel";
	/** 课程需求-完成任务 */
	const PERMSSION_DEMAND_TASK_COMPLETE = "p_demand_task_complete";
	/** 课程需求-任务创建 */
	const PERMSSION_DEMAND_TASK_CREATE = "p_demand_task_create";
	/** 课程需求-任务添加验收 */
	const PERMSSION_DEMAND_TASK_CREATE_ACCEPTANCE = "p_demand_task_create_acceptance";
	/** 课程需求-任务添加审核 */
	const PERMSSION_DEMAND_TASK_CREATE_CHECK = "p_demand_task_create_check";
	/** 课程需求-任务创建课程产品 */
	const PERMSSION_DEMAND_TASK_CREATE_PRODUCT = "p_demand_task_create_product";
	/** 课程需求-任务删除验收 */
	const PERMSSION_DEMAND_TASK_DELETE_ACCEPTANCE = "p_demand_task_delete_acceptance";
	/** 课程需求-任务删除审核 */
	const PERMSSION_DEMAND_TASK_DELETE_CHECK = "p_demand_task_delete_check";
	/** 课程需求-任务删除课程产品 */
	const PERMSSION_DEMAND_TASK_DELETE_PRODUCT = "p_demand_task_delete_product";
	/** 课程需求-添加课程开发任务 */
	const PERMSSION_DEMAND_TASK_DEVELOP = "p_demand_task_develop";
	/** 课程需求-需求任务-编辑操作 */
	const PERMSSION_DEMAND_TASK_EDIT = "p_demand_task_edit";
	/** 课程需求-任务恢复 */
	const PERMSSION_DEMAND_TASK_RESTORE = "p_demand_task_restore";
	/** 课程需求-任务提交验收 */
	const PERMSSION_DEMAND_TASK_SUBMIT_ACCEPTANCE = "p_demand_task_submit_acceptance";
	/** 课程需求-任务提交审核 */
	const PERMSSION_DEMAND_TASK_SUBMIT_CHECK = "p_demand_task_submit_check";
	/** 课程需求-任务承接 */
	const PERMSSION_DEMAND_TASK_UNDERTAKE = "p_demand_task_undertake";
	/** 课程需求-任务更新 */
	const PERMSSION_DEMAND_TASK_UPDATE = "p_demand_task_update";
	/** 课程需求-任务更新验收 */
	const PERMSSION_DEMAND_TASK_UPDATE_ACCEPTANCE = "p_demand_task_update_acceptance";
	/** 课程需求-任务更新审核 */
	const PERMSSION_DEMAND_TASK_UPDATE_CHECK = "p_demand_task_update_check";
	/** 多媒体-任务指派 */
	const PERMSSION_MULTIMEDIA_TASK_ASSIGN = "p_multimedia_task_assign";
	/** 多媒体-取消任务 */
	const PERMSSION_MULTIMEDIA_TASK_CANCEL = "p_multimedia_task_cancel";
	/** 多媒体-完成任务 */
	const PERMSSION_MULTIMEDIA_TASK_COMPLETE = "p_multimedia_task_complete";
	/** 多媒体-任务创建 */
	const PERMSSION_MULTIMEDIA_TASK_CREATE = "p_multimedia_task_create";
	/** 多媒体-任务添加审核 */
	const PERMSSION_MULTIMEDIA_TASK_CREATE_CHECK = "p_multimedia_task_create_check";
	/** 多媒体-任务删除审核 */
	const PERMSSION_MULTIMEDIA_TASK_DELETE_CHECK = "p_multimedia_task_delete_check";
	/** 多媒体-任务更新 */
	const PERMSSION_MULTIMEDIA_TASK_UPDATE = "p_multimedia_task_update";
	/** 多媒体-任务更新审核 */
	const PERMSSION_MULTIMEDIA_TASK_UPDATE_CHECK = "p_multimedia_task_update_check";
	/** 平台新闻发布 */
	const PERMSSION_NEW_PUBLISH = "p_new_publish";
	/** 管理用户或者的权限以及角色分配 */
	const PERMSSION_RBAC_ADMIN = "p_rbac_admin";
	/** 拍摄-管理 */
	const PERMSSION_SHOOT_ADMIN = "p_shoot_admin";
	/** 拍摄-评价 */
	const PERMSSION_SHOOT_APPRAISE = "p_shoot_appraise";
	/** 拍摄-摄影师分派 */
	const PERMSSION_SHOOT_ASSIGN = "p_shoot_assign";
	/** 拍摄-取消预约 */
	const PERMSSION_SHOOT_CANCEL = "p_shoot_cancel";
	/** 拍摄-创建预约 */
	const PERMSSION_SHOOT_CREATE = "p_shoot_create";
	/** 拍摄-查看预约 */
	const PERMSSION_SHOOT_INDEX = "p_shoot_index";
	/** 摄影-接洽人与摄影师评价 */
	const PERMSSION_SHOOT_OWN_APPRAISE = "p_shoot_own_appraise";
	/** 拍摄-取消自己创建的预约 */
	const PERMSSION_SHOOT_OWN_CANCEL = "p_shoot_own_cancel";
	/** 拍摄-更新自己创建的预约 */
	const PERMSSION_SHOOT_OWN_UPDATE = "p_shoot_own_update";
	/** 拍摄-更新预约 */
	const PERMSSION_SHOOT_UPDATE = "p_shoot_update";
	/** 课程开发-课程录入 */
	const PERMSSION_TEAMWORK_COURSE_INPUT = "p_teamwork_course_input";
	/** 课程开发-课程恢复 */
	const PERMSSION_TEAMWORK_COURSE_RESTORE = "p_teamwork_course_restore";
	/** 课程开发-课程移交 */
	const PERMSSION_TEAMWORK_COURSE_TRANSFER = "p_teamwork_course_transfer";
	/** 课程开发-任务完成 */
	const PERMSSION_TEAMWORK_TAKS_COMPLETE = "p_teamwork_taks_complete";
	/** 课程开发-任务开始 */
	const PERMSSION_TEAMWORK_TAKS_START = "p_teamwork_taks_start";
	/** 课程开发-任务更新 */
	const PERMSSION_TEAMWORK_TAKS_UPDATE = "p_teamwork_taks_update";
	/** 课程开发-任务配置 */
	const PERMSSION_TEAMWORK_TASK_COLLOCATION = "p_teamwork_task_collocation";
	/** 课程开发-任务创建 */
	const PERMSSION_TEAMWORK_TASK_CREATE = "p_teamwork_task_create";
	/** 课程开发-周报创建 */
	const PERMSSION_TEAMWORK_WEEKLY_CREATE = "p_teamwork_weekly_create";
	/** 课程开发-周报编辑 */
	const PERMSSION_TEAMWORK_WEEKLY_UPDATE = "p_teamwork_weekly_update";
	/** 工作系统-任务创建 */
	const PERMSSION_WORKSYSTEM_TASK_CREATE = "p_worksystem_task_create";
	/** 管理员 */
	const ROLE_ADMIN = "r_admin";
	/** 课程中心组 */
	const ROLE_CC_USERS = "r_cc_users";
	/** 接洽人 */
	const ROLE_CONTACT = "r_contact";
	/** 课程需求审核人 */
	const ROLE_DEMAND_AUDITOR = "r_demand_auditor";
	/** 课程需求-基础数据管理人 */
	const ROLE_DEMAND_BASEDATA_ADMIN = "r_demand_basedata_admin";
	/** 课程需求发布者 */
	const ROLE_DEMAND_PROMULGATOR = "r_demand_promulgator";
	/** 课程需求-需求任务管理人 */
	const ROLE_DEMAND_TASK_ADMIN = "r_demand_task_admin";
	/** 课程需求承接人 */
	const ROLE_DEMAND_UNDERTAKE_PERSON = "r_demand_undertake_person";
	/** 游客 */
	const ROLE_GUEST = "r_guest";
	/** 多媒体制作师 */
	const ROLE_MP = "r_mp";
	/** 多媒体制作组长 */
	const ROLE_MP_LEADER = "r_mp_leader";
	/** 多媒体任务指派人 */
	const ROLE_MULTIMEDIA_ASSIGNPERSON = "r_multimedia_assignperson";
	/** 多媒体任务发布者 */
	const ROLE_MULTIMEDIA_PROMULGATOR = "r_multimedia_promulgator";
	/** 新闻事件管理员 */
	const ROLE_NEW_PUBLISHER = "r_new_publisher";
	/** 项目管理员  */
	const ROLE_PROJECT_MANAGER = "r_project_manager";
	/** 摄影组长 */
	const ROLE_SHOOT_LEADER = "r_shoot_leader";
	/** 摄影师 */
	const ROLE_SHOOT_MAN = "r_shoot_man";
	/** 预约拍摄系统管理员 */
	const ROLE_SHOOT_MANAGER = "r_shoot_manager";
	/** 老师 */
	const ROLE_TEACHERS = "r_teachers";
	/** 课程开发录入人 */
	const ROLE_TEAMWORK_COURSE_INPUTPERSON = "r_teamwork_course_inputperson";
	/** 课程开发经理 */
	const ROLE_TEAMWORK_DEVELOP_LEADER = "r_teamwork_develop_leader";
	/** 课程开发管理员 */
	const ROLE_TEAMWORK_DEVELOP_MANAGER = "r_teamwork_develop_manager";
	/** 课程开发周报开发者 */
	const ROLE_TEAMWORK_WEEKLY_DEVELOPER = "r_teamwork_weekly_developer";
	/** 所有用户 */
	const ROLE_USERS = "r_users";
	/** 编导 */
	const ROLE_WD = "r_wd";
	/** 编导组长 */
	const ROLE_WD_LEADER = "r_wd_leader";
	/** 工作系统-任务发布人 */
	const ROLE_WORKSYSTEM_PUBLISHER = "r_worksystem_publisher";
}