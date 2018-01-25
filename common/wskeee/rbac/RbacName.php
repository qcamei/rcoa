<?php
namespace wskeee\rbac;
class RbacName{
	/** 数据库备份 */
	const PERMSSION_BACKEND_DBBACKUPERMSSION_ADMIN = "p_backend_dbbackup_admin";
	/** 需求管理 */
	const PERMSSION_BACKEND_DEMAND_ADMIN = "p_backend_demand_admin";
	/** 专家管理 */
	const PERMSSION_BACKEND_EXPERT_ADMIN = "p_backend_expert_admin";
	/** 框架基础数据管理 */
	const PERMSSION_BACKEND_FRAMEWORK_ADMIN = "p_backend_framework_admin";
	/** 帮助中心管理 */
	const PERMSSION_BACKEND_HELPCENTER_ADMIN = "p_backend_helpcenter_admin";
	/** 课程制作管理 */
	const PERMSSION_BACKEND_MCONLINE_ADMIN = "p_backend_mconline_admin";
	/** 题库管理 */
	const PERMSSION_BACKEND_QUESTION_ADMIN = "p_backend_question_admin";
	/** 权限管理 */
	const PERMSSION_BACKEND_RBAC_ADMIN = "p_backend_rbac_admin";
	/** 情景预约管理 */
	const PERMSSION_BACKEND_SCENE_ADMIN = "p_backend_scene_admin";
	/** 拍摄管理 */
	const PERMSSION_BACKEND_SHOOT_ADMIN = "p_backend_shoot_admin";
	/** 模块管理 */
	const PERMSSION_BACKEND_SYSTEM_ADMIN = "p_backend_system_admin";
	/** 开发管理 */
	const PERMSSION_BACKEND_TEAMWORK_ADMIN = "p_backend_teamwork_admin";
	/** 团队管理 */
	const PERMSSION_BACKEND_TEMMANAGE_ADMIN = "p_backend_temmanage_admin";
	/** 单元测试 */
	const PERMSSION_BACKEND_UNITTEST_ADMIN = "p_backend_unittest_admin";
	/** 用户管理 */
	const PERMSSION_BACKEND_USER_ADMIN = "p_backend_user_admin";
	/** 任务管理 */
	const PERMSSION_BACKEND_WORKSYSTEM_ADMIN = "p_backend_worksystem_admin";
	/** 数据添加v2 */
	const PERMSSION_FRONTEND_DEMAND_BASEDATA_CREATE = "p_frontend_demand_basedata_create";
	/** 数据删除v2 */
	const PERMSSION_FRONTEND_DEMAND_BASEDATA_DELETE = "p_frontend_demand_basedata_delete";
	/** 数据查看v2 */
	const PERMSSION_FRONTEND_DEMAND_BASEDATA_READ = "p_frontend_demand_basedata_read";
	/** 数据更改v2 */
	const PERMSSION_FRONTEND_DEMAND_BASEDATA_UPDATE = "p_frontend_demand_basedata_update";
	/** 取消任务v2 */
	const PERMSSION_FRONTEND_DEMAND_CANCEL_TASK = "p_frontend_demand_cancel_task";
	/** 完成任务v2 */
	const PERMSSION_FRONTEND_DEMAND_COMPLETE_TASK = "p_frontend_demand_complete_task";
	/** 创建验收v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_ACCEPTANCE = "p_frontend_demand_create_acceptance";
	/** 创建申诉v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_APPEAL = "p_frontend_demand_create_appeal";
	/** 驳回申诉v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_APPEALREPLY = "p_frontend_demand_create_appealReply";
	/** 创建审核v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_CHECK = "p_frontend_demand_create_check";
	/** 创建开发v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_DEVELOP = "p_frontend_demand_create_develop";
	/** 创建任务v2 */
	const PERMSSION_FRONTEND_DEMAND_CREATE_TASK = "p_frontend_demand_create_task";
	/** 恢复任务v2 */
	const PERMSSION_FRONTEND_DEMAND_RESTORE_TASK = "p_frontend_demand_restore_task";
	/** 查看视图v2 */
	const PERMSSION_FRONTEND_DEMAND_SEE_VIEW = "p_frontend_demand_see_view";
	/** 提交验收v2 */
	const PERMSSION_FRONTEND_DEMAND_SUBMIT_ACCEPTANCE = "p_frontend_demand_submit_acceptance";
	/** 提交审核v2 */
	const PERMSSION_FRONTEND_DEMAND_SUBMIT_CHECK = "p_frontend_demand_submit_check";
	/** 承接任务v2 */
	const PERMSSION_FRONTEND_DEMAND_UNDERTAKE_TASK = "p_frontend_demand_undertake_task";
	/** 更新验收v2 */
	const PERMSSION_FRONTEND_DEMAND_UPDATE_ACCEPTANCE = "p_frontend_demand_update_acceptance";
	/** 更新审核v2 */
	const PERMSSION_FRONTEND_DEMAND_UPDATE_CHECK = "p_frontend_demand_update_check";
	/** 更新任务v2 */
	const PERMSSION_FRONTEND_DEMAND_UPDATE_TASK = "p_frontend_demand_update_task";
	/** 拍摄评价v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_APPRAISE = "p_frontend_scene_book_appraise";
	/** 预约指派v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_ASSIGN = "p_frontend_scene_book_assign";
	/** 取消转让v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_CANCEL_TRANSFER = "p_frontend_scene_book_cancel_transfer";
	/** 创建预约v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_CREATE = "p_frontend_scene_book_create";
	/** 承接转让v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_RECEIVE = "p_frontend_scene_book_receive";
	/** 申请转让v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_TRANSFER = "p_frontend_scene_book_transfer";
	/** 更新预约v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_UPDATE = "p_frontend_scene_book_update";
	/** 查看预约v2 */
	const PERMSSION_FRONTEND_SCENE_BOOK_VIEW = "p_frontend_scene_book_view";
	/** 课程录入v2 */
	const PERMSSION_FRONTEND_TEAMWORK_COURSE_INPUT = "p_frontend_teamwork_course_input";
	/** 任务暂停v2 */
	const PERMSSION_FRONTEND_TEAMWORK_COURSE_PAUSE = "p_frontend_teamwork_course_pause";
	/** 课程恢复v2 */
	const PERMSSION_FRONTEND_TEAMWORK_COURSE_RESTORE = "p_frontend_teamwork_course_restore";
	/** 课程移交v2 */
	const PERMSSION_FRONTEND_TEAMWORK_COURSE_TRANSFER = "p_frontend_teamwork_course_transfer";
	/** 查看视图v2 */
	const PERMSSION_FRONTEND_TEAMWORK_SEE_VIEW = "p_frontend_teamwork_see_view";
	/** 任务完成v2 */
	const PERMSSION_FRONTEND_TEAMWORK_TAKS_COMPLETE = "p_frontend_teamwork_taks_complete";
	/** 任务开始v2 */
	const PERMSSION_FRONTEND_TEAMWORK_TAKS_START = "p_frontend_teamwork_taks_start";
	/** 任务更新v2 */
	const PERMSSION_FRONTEND_TEAMWORK_TAKS_UPDATE = "p_frontend_teamwork_taks_update";
	/** 任务配置v2 */
	const PERMSSION_FRONTEND_TEAMWORK_TASK_COLLOCATION = "p_frontend_teamwork_task_collocation";
	/** 任务创建v2 */
	const PERMSSION_FRONTEND_TEAMWORK_TASK_CREATE = "p_frontend_teamwork_task_create";
	/** 周报管理v2 */
	const PERMSSION_FRONTEND_TEAMWORK_WEEKLY_CREATE = "p_frontend_teamwork_weekly_create";
	/** 取消支撑v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CANCEL_BRACE = "p_frontend_worksystem_cancel_brace";
	/** 取消外包v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CANCEL_EPIBOLY = "p_frontend_worksystem_cancel_epiboly";
	/** 取消任务v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CANCEL_TASK = "p_frontend_worksystem_cancel_task";
	/** 取消承接v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CANCEL_UNDERTAKE = "p_frontend_worksystem_cancel_undertake";
	/** 完成验收v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_COMPLETE_ACCEPTANCE = "p_frontend_worksystem_complete_acceptance";
	/** 提交验收v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CONTENTINFO_SUBMIT = "p_frontend_worksystem_contentinfo_submit";
	/** 添加修改v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_ACCEPTANCE = "p_frontend_worksystem_create_acceptance";
	/** 创建指派v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_ASSIGN = "p_frontend_worksystem_create_assign";
	/** 寻求支撑v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_BRACE = "p_frontend_worksystem_create_brace";
	/** 创建审核v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_CHECK = "p_frontend_worksystem_create_check";
	/** 寻求外包v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_EPIBOLY = "p_frontend_worksystem_create_epiboly";
	/** 创建任务v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_TASK = "p_frontend_worksystem_create_task";
	/** 承接任务v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_CREATE_UNDERTAKE = "p_frontend_worksystem_create_undertake";
	/** 查看视图v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_SEE_VIEW = "p_frontend_worksystem_see_view";
	/** 开始制作v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_START_MAKE = "p_frontend_worksystem_start_make";
	/** 提交审核v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_SUBMIT_CHECK = "p_frontend_worksystem_submit_check";
	/** 更新任务v2 */
	const PERMSSION_FRONTEND_WORKSYSTEM_UPDATE_TASK = "p_frontend_worksystem_update_task";
	/** 板书管理v2 */
	const PERMSSION_MCBS_ADMIN = "p_mcbs_admin";
	/** 查看视图v2 */
	const PERMSSION_MCBS_VIEW = "p_mcbs_view";
	/** 管理员v2 */
	const ROLE_ADMIN = "r_admin";
	/** 课程中心组v2 */
	const ROLE_CC_USERS = "r_cc_users";
	/** 课程开发经理v2 */
	const ROLE_COMMON_COURSE_DEV_MANAGER = "r_common_course_dev_manager";
	/** 外包v2 */
	const ROLE_COMMON_EXTERNAL_WORKER = "r_common_external_worker";
	/** 课程总监v2 */
	const ROLE_DEMAND_AUDITOR = "r_demand_auditor";
	/** 课程主任v2 */
	const ROLE_DEMAND_PROMULGATOR = "r_demand_promulgator";
	/** 游客v2 */
	const ROLE_GUEST = "r_guest";
	/** 技术人员v2 */
	const ROLE_MP = "r_mp";
	/** 摄影组长v2 */
	const ROLE_SHOOT_LEADER = "r_shoot_leader";
	/** 摄影师v2 */
	const ROLE_SHOOT_MAN = "r_shoot_man";
	/** 老师v2 */
	const ROLE_TEACHERS = "r_teachers";
	/** 教学编导v2 */
	const ROLE_WD = "r_wd";
}