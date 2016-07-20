/*
Navicat MySQL Data Transfer

Source Server         : ccoa
Source Server Version : 50525
Source Host           : 172.16.163.111:3306
Source Database       : ccoa

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2016-07-20 13:55:51
*/

SET FOREIGN_KEY_CHECKS=0;		-- 取消外键关联
-- TRUNCATE table_name	-- 清空表数据

-- ----------------------------
-- Table structure for ccoa_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_auth_assignment`;
CREATE TABLE `ccoa_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `fk_u_id_1` (`user_id`),
  CONSTRAINT `fk_item_name` FOREIGN KEY (`item_name`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_u_id_1` FOREIGN KEY (`user_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分配用户角色表';

-- ----------------------------
-- Records of ccoa_auth_assignment
-- ----------------------------
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_leader', 'test_8', '1468894736');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', '13', '1468985427');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', '14', '1468985440');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', 'b360c86787a2a1510de034903c5cccf1', '1468986482');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', 'd85245c2ad04f5cd834e823f65db475d', '1468986341');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', 'facf5c478af9d3bfd290f096743d4e2b', '1468986356');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', 'test_4', '1468894673');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_shoot_man', 'test_9', '1468894751');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_teachers', '827c778917e76db96600b77f41fbc632', '1468895626');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '12', '1468985390');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '15', '1468985520');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '18c7a607148f64d5c0277c2c6b263bc4', '1468985528');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '23638ea73551c25229bfd86a52360824', '1468985953');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '25bf83933672773a92a7d44aee05785c', '1468985913');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '39e7a8f6c27193f88ffd3dcf0f59244c', '1468985558');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '4365937c93ba2435997a696d6bf313e8', '1468985571');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '4d4c2ca17f6eef7929bfa92c04b54bf1', '1468985869');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '7a0ee49d4a8417a080ba9f541a8735ce', '1468985939');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '7f3ffba1b7354efc409720dcc7360b36', '1468985589');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '90871b23573f55dad5bf6dc069a05521', '1468985603');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '967624f78ad9ad1431ed397c047d0054', '1468985842');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', '9ca3903e3cd8bb2d33f53833c4255090', '1468986122');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'a979a77d5f8a158e79931b4397920de7', '1468985624');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'b533ecc67dcb295d77c94c4c9e422fc5', '1468985634');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'c08dc0e17864653d28bca74840087492', '1468985857');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'c1bfd128c5e99b1a4a2f0376764c48de', '1468985648');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'd9d4f1ffd12afbe22e80fe1d6020c7c9', '1468985768');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'e8e3e400e7618323ef37d64a62c3e32a', '1468986303');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_1', '1468907600');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_10', '1468916734');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_2', '1468894588');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_5', '1468916792');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_6', '1468907623');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd', 'test_7', '1468894723');
INSERT INTO `ccoa_auth_assignment` VALUES ('r_wd_leader', 'test_1', '1468894542');

-- ----------------------------
-- Table structure for ccoa_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_auth_item`;
CREATE TABLE `ccoa_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  CONSTRAINT `ccoa_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `ccoa_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限和角色信息表';

-- ----------------------------
-- Records of ccoa_auth_item
-- ----------------------------
INSERT INTO `ccoa_auth_item` VALUES ('p_new_publish', '2', '平台新闻发布', null, null, '1446452738', '1447221117');
INSERT INTO `ccoa_auth_item` VALUES ('p_rbac_admin', '2', '管理用户或者的权限以及角色分配', null, null, '1448244252', '1448244252');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_admin', '2', '拍摄-管理', null, null, '1446452838', '1447221127');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_appraise', '2', '拍摄-评价', null, null, '1446452825', '1447990895');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_assign', '2', '拍摄-摄影师分派', null, null, '1446452812', '1447221136');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_cancel', '2', '拍摄-取消预约', null, null, '1446452797', '1447221145');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_create', '2', '拍摄-创建预约', null, null, '1446452769', '1447221151');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_index', '2', '拍摄-查看预约', null, null, '1446452755', '1447221171');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_own_appraise', '2', '摄影-接洽人与摄影师评价', 'ShootOwnAppraiseRule', null, '1448246772', '1448263447');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_own_cancel', '2', '拍摄-取消自己创建的预约', 'ShootOwnCancelRule', null, '1446706745', '1450948881');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_own_update', '2', '拍摄-更新自己创建的预约', 'ShootOwnRule', null, '1446706430', '1447221446');
INSERT INTO `ccoa_auth_item` VALUES ('p_shoot_update', '2', '拍摄-更新预约', null, null, '1446452782', '1447221192');
INSERT INTO `ccoa_auth_item` VALUES ('r_admin', '1', '管理员', null, null, '1441858950', '1447220560');
INSERT INTO `ccoa_auth_item` VALUES ('r_cc_users', '1', '课程中心组', null, null, '1468981495', '1468981495');
INSERT INTO `ccoa_auth_item` VALUES ('r_contact', '1', '接洽人', null, null, '1446444399', '1447220571');
INSERT INTO `ccoa_auth_item` VALUES ('r_guest', '1', '游客', null, null, '1446444475', '1447220577');
INSERT INTO `ccoa_auth_item` VALUES ('r_mp', '1', '多媒体制作师', null, null, '1446444461', '1447220586');
INSERT INTO `ccoa_auth_item` VALUES ('r_mp_leader', '1', '多媒体制作组长', null, null, '1446444448', '1447220595');
INSERT INTO `ccoa_auth_item` VALUES ('r_new_publisher', '1', '新闻事件管理员', null, null, '1446444340', '1447220602');
INSERT INTO `ccoa_auth_item` VALUES ('r_shoot_leader', '1', '摄影组长', null, null, '1446444420', '1447220610');
INSERT INTO `ccoa_auth_item` VALUES ('r_shoot_man', '1', '摄影师', null, null, '1446444434', '1447220620');
INSERT INTO `ccoa_auth_item` VALUES ('r_teachers', '1', '老师', null, null, '1447232999', '1447232999');
INSERT INTO `ccoa_auth_item` VALUES ('r_users', '1', '所有用户', null, null, '1468981460', '1468981460');
INSERT INTO `ccoa_auth_item` VALUES ('r_wd', '1', '编导', null, null, '1446444383', '1447220631');
INSERT INTO `ccoa_auth_item` VALUES ('r_wd_leader', '1', '编导组长', null, null, '1446444373', '1447220638');

-- ----------------------------
-- Table structure for ccoa_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_auth_item_child`;
CREATE TABLE `ccoa_auth_item_child` (
  `parent` varchar(64) NOT NULL DEFAULT 'parent 拥有 child 的权限',
  `child` varchar(64) NOT NULL DEFAULT 'parent 拥有 child 的权限',
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `ccoa_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ccoa_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色拥有的权限表';

-- ----------------------------
-- Records of ccoa_auth_item_child
-- ----------------------------
INSERT INTO `ccoa_auth_item_child` VALUES ('r_admin', 'p_rbac_admin');
INSERT INTO `ccoa_auth_item_child` VALUES ('p_shoot_own_appraise', 'p_shoot_appraise');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_shoot_leader', 'p_shoot_assign');
INSERT INTO `ccoa_auth_item_child` VALUES ('p_shoot_own_cancel', 'p_shoot_cancel');
INSERT INTO `ccoa_auth_item_child` VALUES ('p_rbac_admin', 'p_shoot_create');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_wd', 'p_shoot_create');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_contact', 'p_shoot_own_appraise');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_shoot_man', 'p_shoot_own_appraise');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_appraise');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_cancel');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_update');
INSERT INTO `ccoa_auth_item_child` VALUES ('p_shoot_own_update', 'p_shoot_update');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_users', 'r_admin');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_users', 'r_cc_users');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_contact');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_wd', 'r_contact');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_mp');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_mp_leader');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_users', 'r_new_publisher');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_shoot_leader');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_shoot_man');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_shoot_leader', 'r_shoot_man');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_users', 'r_teachers');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_wd');
INSERT INTO `ccoa_auth_item_child` VALUES ('r_cc_users', 'r_wd_leader');

-- ----------------------------
-- Table structure for ccoa_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_auth_rule`;
CREATE TABLE `ccoa_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Records of ccoa_auth_rule
-- ----------------------------
INSERT INTO `ccoa_auth_rule` VALUES ('myRule', 'O:25:\"common\\models\\rbac\\MyRule\":3:{s:4:\"name\";s:6:\"myRule\";s:9:\"createdAt\";i:1446630496;s:9:\"updatedAt\";i:1446630496;}', '1446630496', '1446630496');
INSERT INTO `ccoa_auth_rule` VALUES ('ShootOwnAppraiseRule', 'O:39:\"common\\models\\rbac\\ShootOwnAppraiseRule\":3:{s:4:\"name\";s:20:\"ShootOwnAppraiseRule\";s:9:\"createdAt\";i:1448243519;s:9:\"updatedAt\";i:1448243519;}', '1448243519', '1448243519');
INSERT INTO `ccoa_auth_rule` VALUES ('ShootOwnCancelRule', 'O:37:\"common\\models\\rbac\\ShootOwnCancelRule\":3:{s:4:\"name\";s:18:\"ShootOwnCancelRule\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1448243519;}', '1448243469', '1448243519');
INSERT INTO `ccoa_auth_rule` VALUES ('ShootOwnRule', 'O:31:\"common\\models\\rbac\\ShootOwnRule\":3:{s:4:\"name\";s:12:\"ShootOwnRule\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1448243519;}', '1446693924', '1448243519');

-- ----------------------------
-- Table structure for ccoa_banner
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_banner`;
CREATE TABLE `ccoa_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL COMMENT 'NAME',
  `path` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  `index` tinyint(2) NOT NULL DEFAULT '1' COMMENT '顺序',
  `isdisplay` varchar(4) DEFAULT '1' COMMENT '是否在页面显示  1为开启显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='平台广告栏表';

-- ----------------------------
-- Records of ccoa_banner
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_expert
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_expert`;
CREATE TABLE `ccoa_expert` (
  `u_id` varchar(36) NOT NULL COMMENT '用户id',
  `type` int(11) DEFAULT NULL COMMENT '专家类型',
  `birth` varchar(64) DEFAULT NULL COMMENT '出生年份',
  `personal_image` varchar(255) DEFAULT NULL COMMENT '个人形象',
  `job_title` varchar(64) DEFAULT '' COMMENT '头衔',
  `job_name` varchar(64) DEFAULT NULL COMMENT '职称',
  `level` varchar(64) DEFAULT NULL COMMENT '级别',
  `employer` varchar(64) DEFAULT NULL COMMENT '单位信息',
  `attainment` text COMMENT '主要成就',
  PRIMARY KEY (`u_id`),
  CONSTRAINT `ccoa_expert_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台专家库表';

-- ----------------------------
-- Records of ccoa_expert
-- ----------------------------
INSERT INTO `ccoa_expert` VALUES ('827c778917e76db96600b77f41fbc632', '1', '2009', '/filedata/expert/personalImage/test_teacher.jpg', '金牌', '金牌讲师', '高级', '', '');

-- ----------------------------
-- Table structure for ccoa_expert_project
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_expert_project`;
CREATE TABLE `ccoa_expert_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expert_id` varchar(36) NOT NULL COMMENT '专家id',
  `project_id` varchar(32) NOT NULL COMMENT '项目id',
  `start_time` char(11) NOT NULL COMMENT '开始时间',
  `end_time` char(11) DEFAULT NULL COMMENT '结束时间',
  `cost` int(11) DEFAULT '0' COMMENT '项目费用',
  `compatibility` int(2) DEFAULT '1' COMMENT '合作融洽度',
  PRIMARY KEY (`id`),
  KEY `fk_ExpertId` (`expert_id`),
  CONSTRAINT `fk_ExpertId` FOREIGN KEY (`expert_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='专家项目表';

-- ----------------------------
-- Records of ccoa_expert_project
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_expert_type
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_expert_type`;
CREATE TABLE `ccoa_expert_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '类型名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='专家类型表';

-- ----------------------------
-- Records of ccoa_expert_type
-- ----------------------------
INSERT INTO `ccoa_expert_type` VALUES ('1', '金牌讲师', '');
INSERT INTO `ccoa_expert_type` VALUES ('2', '专家讲师', '');
INSERT INTO `ccoa_expert_type` VALUES ('3', '无名讲师', '');
INSERT INTO `ccoa_expert_type` VALUES ('4', '国宝讲师', '');
INSERT INTO `ccoa_expert_type` VALUES ('5', '殿堂级讲师', '');
INSERT INTO `ccoa_expert_type` VALUES ('6', '初级讲师', '');

-- ----------------------------
-- Table structure for ccoa_filemanage
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_filemanage`;
CREATE TABLE `ccoa_filemanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) DEFAULT '1' COMMENT '类型: 1为目录2为文档',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `pid` int(11) DEFAULT NULL COMMENT '上一级',
  `keyword` varchar(50) DEFAULT NULL COMMENT '关键字',
  `image` varchar(255) DEFAULT NULL COMMENT '图像',
  `file_link` varchar(255) DEFAULT NULL COMMENT '附件链接',
  PRIMARY KEY (`id`),
  KEY `fk_fm_pid` (`pid`),
  CONSTRAINT `fk_fm_pid` FOREIGN KEY (`pid`) REFERENCES `ccoa_filemanage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='文档管理表';

-- ----------------------------
-- Records of ccoa_filemanage
-- ----------------------------
INSERT INTO `ccoa_filemanage` VALUES ('36', '1', '课程中心规章制度', null, '课程中心,规章制度', '/filedata/image/folder.png', '');
INSERT INTO `ccoa_filemanage` VALUES ('37', '1', '网页示意图配图要求', '36', '网页示意图配图要求', '/filedata/image/folder.png', '');
INSERT INTO `ccoa_filemanage` VALUES ('38', '3', '资源中心-多媒体制作部-视频后期制作标准1.1', '36', '资源中心-多媒体制作部-视频后期制作标准1.1', '/filedata/image/xlsx.png', '/files1/file/acc638d7ca0c1f482c2a8f04cebc4784.xlsx');
INSERT INTO `ccoa_filemanage` VALUES ('39', '3', '网页示意图配图要求', '37', '网页示意图配图要求2', '/filedata/image/docx.png', '/files1/file/9c095c70248a87bed4dadbd8ac2698a8.docx');

-- ----------------------------
-- Table structure for ccoa_filemanage_detail
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_filemanage_detail`;
CREATE TABLE `ccoa_filemanage_detail` (
  `fm_id` int(11) NOT NULL COMMENT 'fm_id',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`fm_id`),
  CONSTRAINT `fk_fm_detail_id` FOREIGN KEY (`fm_id`) REFERENCES `ccoa_filemanage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档详情表';

-- ----------------------------
-- Records of ccoa_filemanage_detail
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_filemanage_owner
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_filemanage_owner`;
CREATE TABLE `ccoa_filemanage_owner` (
  `fm_id` int(11) NOT NULL COMMENT 'fm_id',
  `owner` varchar(150) NOT NULL COMMENT '所有者',
  PRIMARY KEY (`fm_id`,`owner`),
  KEY `fk_fm_owner` (`owner`),
  CONSTRAINT `fk_fm_id` FOREIGN KEY (`fm_id`) REFERENCES `ccoa_filemanage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fm_owner` FOREIGN KEY (`owner`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档所有者表';

-- ----------------------------
-- Records of ccoa_filemanage_owner
-- ----------------------------
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_admin');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_admin');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_admin');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_admin');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_contact');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_contact');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_contact');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_contact');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_guest');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_guest');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_guest');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_guest');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_mp');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_mp');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_mp');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_mp');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_mp_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_mp_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_mp_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_mp_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_new_publisher');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_new_publisher');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_new_publisher');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_new_publisher');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_shoot_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_shoot_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_shoot_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_shoot_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_shoot_man');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_shoot_man');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_shoot_man');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_shoot_man');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_teachers');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_teachers');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_teachers');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_teachers');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_wd');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_wd');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_wd');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_wd');
INSERT INTO `ccoa_filemanage_owner` VALUES ('36', 'r_wd_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('37', 'r_wd_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('38', 'r_wd_leader');
INSERT INTO `ccoa_filemanage_owner` VALUES ('39', 'r_wd_leader');

-- ----------------------------
-- Table structure for ccoa_framework_item
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_framework_item`;
CREATE TABLE `ccoa_framework_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `des` varchar(255) NOT NULL DEFAULT '无' COMMENT '描述',
  `level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '等级',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '父级id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_project_name` (`name`) USING BTREE,
  KEY `fk_project_parent` (`parent_id`),
  CONSTRAINT `fk_project_parent` FOREIGN KEY (`parent_id`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='项目基础数据表';

-- ----------------------------
-- Records of ccoa_framework_item
-- ----------------------------
INSERT INTO `ccoa_framework_item` VALUES ('67', '专科', '', '1', '1468834597', '1468834597', null);
INSERT INTO `ccoa_framework_item` VALUES ('68', '机械电子工程与管理', '', '2', '1468834611', '1468834611', '67');
INSERT INTO `ccoa_framework_item` VALUES ('69', '电气自动化技术', '', '2', '1468923433', '1468923433', '67');
INSERT INTO `ccoa_framework_item` VALUES ('70', '工商管理（企业生产运营管理方向）', '', '2', '1468923505', '1468923505', '67');
INSERT INTO `ccoa_framework_item` VALUES ('71', '连锁经营', '', '2', '1468923523', '1468923523', '67');
INSERT INTO `ccoa_framework_item` VALUES ('72', '本科', '', '1', '1468923692', '1468923692', null);
INSERT INTO `ccoa_framework_item` VALUES ('73', '国家教师资格考试培训项目 ', '', '1', '1468923791', '1468923791', null);
INSERT INTO `ccoa_framework_item` VALUES ('74', '工商管理', '', '2', '1468923987', '1468838956', '72');
INSERT INTO `ccoa_framework_item` VALUES ('75', '会计', '', '2', '1468923998', '1468838968', '72');
INSERT INTO `ccoa_framework_item` VALUES ('76', '幼儿园教师资格精讲精练课程', '', '2', '1468924275', '1468924275', '73');
INSERT INTO `ccoa_framework_item` VALUES ('77', '小学教师资格精讲精练课程', '', '2', '1468924285', '1468924285', '73');
INSERT INTO `ccoa_framework_item` VALUES ('78', '中学教师资格精讲精练课程', '', '2', '1468924293', '1468924293', '73');
INSERT INTO `ccoa_framework_item` VALUES ('79', '模具制造工艺', '', '3', '1468924382', '1468924382', '68');
INSERT INTO `ccoa_framework_item` VALUES ('80', 'CADCAM软件应用', '', '3', '1468924394', '1468924394', '68');
INSERT INTO `ccoa_framework_item` VALUES ('81', '机电设备诊断与维修', '', '3', '1468924404', '1468924404', '68');
INSERT INTO `ccoa_framework_item` VALUES ('82', '自动检测与自动化仪表', '', '3', '1468924423', '1468924423', '68');
INSERT INTO `ccoa_framework_item` VALUES ('83', '电气控制与PLC', '', '3', '1468924642', '1468924642', '69');
INSERT INTO `ccoa_framework_item` VALUES ('84', '工厂供配电技术', '', '3', '1468924650', '1468924650', '69');
INSERT INTO `ccoa_framework_item` VALUES ('85', '企业文化', '', '3', '1468924659', '1468924659', '69');
INSERT INTO `ccoa_framework_item` VALUES ('86', '职业道德修养', '', '3', '1468924667', '1468924667', '69');
INSERT INTO `ccoa_framework_item` VALUES ('87', '证券投资学', '', '3', '1468924703', '1468924703', '74');
INSERT INTO `ccoa_framework_item` VALUES ('88', '企业现场质量管理 ', '', '3', '1468924739', '1468924739', '70');
INSERT INTO `ccoa_framework_item` VALUES ('89', '企业现场改善实务', '', '3', '1468924746', '1468924746', '70');
INSERT INTO `ccoa_framework_item` VALUES ('90', '企业生产成本管理', '', '3', '1468924765', '1468924765', '70');
INSERT INTO `ccoa_framework_item` VALUES ('91', '学位论文指南（会计学本）', '', '3', '1468924807', '1468924807', '75');
INSERT INTO `ccoa_framework_item` VALUES ('92', '数控加工工艺与编程', '', '3', '1468924839', '1468924839', '68');
INSERT INTO `ccoa_framework_item` VALUES ('93', '连锁门店运营实务', '', '3', '1468924862', '1468924862', '71');
INSERT INTO `ccoa_framework_item` VALUES ('94', '特许经营概论', '', '3', '1468924870', '1468924870', '71');
INSERT INTO `ccoa_framework_item` VALUES ('95', '客户服务管理', '', '3', '1468924885', '1468924885', '71');
INSERT INTO `ccoa_framework_item` VALUES ('96', '商超运营管理', '', '3', '1468924894', '1468924894', '71');
INSERT INTO `ccoa_framework_item` VALUES ('97', '网络营销', '', '3', '1468924902', '1468924902', '71');
INSERT INTO `ccoa_framework_item` VALUES ('98', '商品推广', '', '3', '1468924910', '1468924910', '71');
INSERT INTO `ccoa_framework_item` VALUES ('99', '商务礼仪概论', '', '3', '1468924920', '1468924920', '71');
INSERT INTO `ccoa_framework_item` VALUES ('100', '《综合素质（小学）》精讲精练', '', '3', '1468924949', '1468924949', '77');
INSERT INTO `ccoa_framework_item` VALUES ('101', '《教育教学知识与能力（小学）》精讲精练', '', '3', '1468924957', '1468924957', '77');
INSERT INTO `ccoa_framework_item` VALUES ('102', '《综合素质（幼儿园》精讲精练', '', '3', '1468924974', '1468924974', '76');
INSERT INTO `ccoa_framework_item` VALUES ('103', '《保教知识与能力（幼儿园）》精讲精练', '', '3', '1468924981', '1468924981', '76');
INSERT INTO `ccoa_framework_item` VALUES ('104', '《综合素质（中学）》精讲精练', '', '3', '1468925004', '1468925004', '78');
INSERT INTO `ccoa_framework_item` VALUES ('105', '《教育知识与能力（中学）》精讲精练', '', '3', '1468925012', '1468925012', '78');

-- ----------------------------
-- Table structure for ccoa_framework_item_type
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_framework_item_type`;
CREATE TABLE `ccoa_framework_item_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '类别名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='项目类型表';

-- ----------------------------
-- Records of ccoa_framework_item_type
-- ----------------------------
INSERT INTO `ccoa_framework_item_type` VALUES ('1', '学历');
INSERT INTO `ccoa_framework_item_type` VALUES ('2', '非学历');
INSERT INTO `ccoa_framework_item_type` VALUES ('3', '机械电子行业');
INSERT INTO `ccoa_framework_item_type` VALUES ('4', '现代制造行业');
INSERT INTO `ccoa_framework_item_type` VALUES ('6', '连锁经营行业');
INSERT INTO `ccoa_framework_item_type` VALUES ('7', '金融财会行业');
INSERT INTO `ccoa_framework_item_type` VALUES ('8', '管理与咨询行业');

-- ----------------------------
-- Table structure for ccoa_job
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_job`;
CREATE TABLE `ccoa_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '信息id',
  `system_id` int(11) NOT NULL COMMENT '任务系统',
  `relate_id` int(11) NOT NULL COMMENT '关联id',
  `subject` varchar(255) CHARACTER SET utf8 DEFAULT '无' COMMENT '主题',
  `content` text CHARACTER SET utf8 COMMENT '信息内容',
  `link` varchar(255) CHARACTER SET utf8 DEFAULT '""' COMMENT '任务超联接',
  `progress` int(3) DEFAULT '0' COMMENT '进度',
  `status` varchar(64) CHARACTER SET utf8 DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `fk_system` (`system_id`),
  CONSTRAINT `fk_system` FOREIGN KEY (`system_id`) REFERENCES `ccoa_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='通知详情表';

-- ----------------------------
-- Records of ccoa_job
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_job_notification
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_job_notification`;
CREATE TABLE `ccoa_job_notification` (
  `job_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` varchar(36) NOT NULL COMMENT '用户id',
  `status` int(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`job_id`,`u_id`),
  KEY `u_id` (`u_id`),
  CONSTRAINT `ccoa_job_notification_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_job_id` FOREIGN KEY (`job_id`) REFERENCES `ccoa_job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关联用户通知表';

-- ----------------------------
-- Records of ccoa_job_notification
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_migration
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_migration`;
CREATE TABLE `ccoa_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ccoa_migration
-- ----------------------------
INSERT INTO `ccoa_migration` VALUES ('m000000_000000_base', '1441511973');
INSERT INTO `ccoa_migration` VALUES ('m130524_201442_init', '1441511978');
INSERT INTO `ccoa_migration` VALUES ('m140506_102106_rbac_init', '1441769187');

-- ----------------------------
-- Table structure for ccoa_question
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_question`;
CREATE TABLE `ccoa_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '题目类型',
  `title` varchar(255) DEFAULT NULL COMMENT '题目标题',
  `des` varchar(255) DEFAULT NULL COMMENT '题目描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='题目表';

-- ----------------------------
-- Records of ccoa_question
-- ----------------------------
INSERT INTO `ccoa_question` VALUES ('1', '1', '拍摄场地与设备准备效率', '摄影——摄影师');
INSERT INTO `ccoa_question` VALUES ('2', '1', '拍摄的工作效率', '摄影——摄影师');
INSERT INTO `ccoa_question` VALUES ('3', '1', '拍摄效果', '摄影——摄影师');
INSERT INTO `ccoa_question` VALUES ('4', '1', '老师到场的效率', '摄影——编导评');
INSERT INTO `ccoa_question` VALUES ('5', '1', '现场编导的工作效率', '摄影——编导');
INSERT INTO `ccoa_question` VALUES ('6', '1', '授课痕迹遗留', '摄影——摄影师对编导评价题目');

-- ----------------------------
-- Table structure for ccoa_question_op
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_question_op`;
CREATE TABLE `ccoa_question_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL COMMENT '题目id',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '选项类型',
  `title` varchar(255) DEFAULT NULL COMMENT '选项标题',
  `value` varchar(255) DEFAULT NULL COMMENT '选项值',
  PRIMARY KEY (`id`),
  KEY `pk_q_id` (`question_id`),
  CONSTRAINT `pk_q_id` FOREIGN KEY (`question_id`) REFERENCES `ccoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='题目选项表';

-- ----------------------------
-- Records of ccoa_question_op
-- ----------------------------
INSERT INTO `ccoa_question_op` VALUES ('1', '1', '1', '准时提供拍摄场地与设备', '3');
INSERT INTO `ccoa_question_op` VALUES ('3', '1', '1', '延迟提供拍摄场地与设备，半小时以内', '2');
INSERT INTO `ccoa_question_op` VALUES ('4', '1', '1', '延迟提供拍摄场地与设备，半小时以上', '0');
INSERT INTO `ccoa_question_op` VALUES ('8', '2', '1', '在拍摄过程中设备运行正常，并且指导老师高效完成拍摄工作', '3');
INSERT INTO `ccoa_question_op` VALUES ('9', '2', '1', '在拍摄过程中场地和设备出现小状态，但不影响正常拍摄', '2');
INSERT INTO `ccoa_question_op` VALUES ('10', '2', '1', '由于主观失误导致场地和设备出现各种状况，严重影响拍摄工作', '0');
INSERT INTO `ccoa_question_op` VALUES ('14', '6', '1', '将教师授课痕迹带走，包括实物如：纸巾、茶杯等。虚拟物如：授课讲稿。做到了零污染', '3');
INSERT INTO `ccoa_question_op` VALUES ('15', '6', '1', '实物痕迹干净，虚拟课件没有清理', '2');
INSERT INTO `ccoa_question_op` VALUES ('16', '6', '1', '授课完毕第一时间离开，所带教师现场痕迹无收拾', '0');
INSERT INTO `ccoa_question_op` VALUES ('17', '5', '1', '在拍摄过程中设备运行正常，并且指导老师高效完成拍摄工作', '3');
INSERT INTO `ccoa_question_op` VALUES ('18', '5', '1', '拍摄资准备不够充分，对老师的指引性有待提升', '2');
INSERT INTO `ccoa_question_op` VALUES ('19', '5', '1', '拍摄资料缺漏较多，对教师指引不够导致视频拍摄效率低', '0');
INSERT INTO `ccoa_question_op` VALUES ('20', '3', '1', '画面美观高清，无斑纹，无曝光，画面节奏紧凑，切换自然', '3');
INSERT INTO `ccoa_question_op` VALUES ('21', '3', '1', '画面有稍微瑕疵，不影响整体质量', '2');
INSERT INTO `ccoa_question_op` VALUES ('22', '3', '1', '拍摄效果不合格，需要补拍或重拍', '0');
INSERT INTO `ccoa_question_op` VALUES ('23', '4', '1', '老师准时到场', '3');
INSERT INTO `ccoa_question_op` VALUES ('24', '4', '1', '老师延迟到场半小时以内', '2');
INSERT INTO `ccoa_question_op` VALUES ('25', '4', '1', '老师延迟到场，半小时以上', '0');

-- ----------------------------
-- Table structure for ccoa_resource
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_resource`;
CREATE TABLE `ccoa_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `type` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_type` (`type`),
  CONSTRAINT `fk_type` FOREIGN KEY (`type`) REFERENCES `ccoa_resource_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='资源展示表';

-- ----------------------------
-- Records of ccoa_resource
-- ----------------------------
INSERT INTO `ccoa_resource` VALUES ('1', '图片名称', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('2', '图片名称2', '1', '/filedata/resource/u190.jpg', null);
INSERT INTO `ccoa_resource` VALUES ('3', '图片名称3', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('4', '图片名称4', '1', '/filedata/resource/u190.jpg', null);
INSERT INTO `ccoa_resource` VALUES ('5', '图片名称5', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('6', '图片名称6', '1', '/filedata/resource/u190.jpg', null);
INSERT INTO `ccoa_resource` VALUES ('7', '图片名称7', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('8', '图片名称8', '1', '/filedata/resource/u190.jpg', null);
INSERT INTO `ccoa_resource` VALUES ('9', '图片名称', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('10', '图片名称', '1', '/filedata/resource/u190.jpg', null);
INSERT INTO `ccoa_resource` VALUES ('11', '图片名称', '1', '/filedata/resource/u186.png', null);
INSERT INTO `ccoa_resource` VALUES ('12', '图片名称', '1', '/filedata/resource/u190.jpg', null);

-- ----------------------------
-- Table structure for ccoa_resource_path
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_resource_path`;
CREATE TABLE `ccoa_resource_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `r_id` int(11) DEFAULT NULL COMMENT '资源id',
  `path` varchar(255) DEFAULT NULL COMMENT '路径',
  `type` tinyint(2) DEFAULT '0' COMMENT '0为图片 1为视频',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_r_id` (`r_id`),
  CONSTRAINT `fk_r_id` FOREIGN KEY (`r_id`) REFERENCES `ccoa_resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='资源路径表';

-- ----------------------------
-- Records of ccoa_resource_path
-- ----------------------------
INSERT INTO `ccoa_resource_path` VALUES ('1', '1', '/filedata/resource/1.jpg', '0', '这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片这是一张图片');
INSERT INTO `ccoa_resource_path` VALUES ('2', '1', '/filedata/resource/2.jpg', '0', '这是一张图片');
INSERT INTO `ccoa_resource_path` VALUES ('3', '1', '/filedata/resource/3.jpg', '0', '这个是一张图片');
INSERT INTO `ccoa_resource_path` VALUES ('4', '1', '/filedata/resource/1.mp4', '1', '这还是一张图片');
INSERT INTO `ccoa_resource_path` VALUES ('5', '2', '/filedata/resource/1.mp4', '1', null);
INSERT INTO `ccoa_resource_path` VALUES ('6', '3', '/filedata/resource/1.jpg', '0', '这是一个ppt');
INSERT INTO `ccoa_resource_path` VALUES ('7', '4', '/filedata/resource/2.jpg\r\n', '0', '这是一个不知名的东西');
INSERT INTO `ccoa_resource_path` VALUES ('8', '3', '/filedata/resource/3.jpg', '0', '这是。。。。');
INSERT INTO `ccoa_resource_path` VALUES ('9', '4', '/filedata/resource/4.jpg', '0', '这是。。。。');
INSERT INTO `ccoa_resource_path` VALUES ('10', '5', '/filedata/resource/1.mp4', '1', '这是。。。');
INSERT INTO `ccoa_resource_path` VALUES ('11', '6', '/filedata/resource/1.mp4', '1', '');
INSERT INTO `ccoa_resource_path` VALUES ('12', '6', '/filedata/resource/1.jpg', '0', '这是、、、、');
INSERT INTO `ccoa_resource_path` VALUES ('13', '6', '/filedata/resource/1.mp4', '1', null);
INSERT INTO `ccoa_resource_path` VALUES ('14', '7', '/filedata/resource/2.jpg', '0', '阿斯蒂芬撒旦发射的防守打法');
INSERT INTO `ccoa_resource_path` VALUES ('15', '8', '/filedata/resource/3.jpg', '0', '大撒反对撒法打算发大水法撒旦撒旦法');
INSERT INTO `ccoa_resource_path` VALUES ('16', '9', '/filedata/resource/4.jpg', '0', '萨芬打算发生地方大撒');
INSERT INTO `ccoa_resource_path` VALUES ('17', '10', '/filedata/resource/1.jpg', '0', '阿朵发射的防守打法sad');
INSERT INTO `ccoa_resource_path` VALUES ('18', '11', '/filedata/resource/2.jpg', '0', '的萨芬三大发射点发');
INSERT INTO `ccoa_resource_path` VALUES ('19', '12', '/filedata/resource/1.mp4', '1', null);
INSERT INTO `ccoa_resource_path` VALUES ('20', '12', '/filedata/resource/1.mp4', '1', null);
INSERT INTO `ccoa_resource_path` VALUES ('24', '1', '/filedata/resource/1.jpg', '0', '大幅度所发生的');

-- ----------------------------
-- Table structure for ccoa_resource_type
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_resource_type`;
CREATE TABLE `ccoa_resource_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '类型名称',
  `image` varchar(255) DEFAULT NULL COMMENT '资源展示类型图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='资源展示类型表';

-- ----------------------------
-- Records of ccoa_resource_type
-- ----------------------------
INSERT INTO `ccoa_resource_type` VALUES ('1', '动画', '/filedata/resource/u186.png');
INSERT INTO `ccoa_resource_type` VALUES ('2', '视频', '/filedata/resource/u190.jpg');
INSERT INTO `ccoa_resource_type` VALUES ('3', 'PPT', '/filedata/resource/u186.png');
INSERT INTO `ccoa_resource_type` VALUES ('4', '模版', '/filedata/resource/u190.jpg');
INSERT INTO `ccoa_resource_type` VALUES ('5', '风景', '/filedata/resource/u186.png');
INSERT INTO `ccoa_resource_type` VALUES ('6', 'PPT', '/filedata/resource/u190.jpg');

-- ----------------------------
-- Table structure for ccoa_shoot_appraise
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_appraise`;
CREATE TABLE `ccoa_shoot_appraise` (
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `role_name` varchar(64) NOT NULL COMMENT '角色',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `value` tinyint(3) NOT NULL DEFAULT '3' COMMENT '分值',
  `index` tinyint(3) DEFAULT NULL COMMENT '索引',
  PRIMARY KEY (`b_id`,`role_name`,`q_id`),
  KEY `pk_question_id` (`q_id`),
  KEY `pk_role_id` (`role_name`),
  CONSTRAINT `ccoa_shoot_appraise_ibfk_1` FOREIGN KEY (`role_name`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_bookdetail_id` FOREIGN KEY (`b_id`) REFERENCES `ccoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_question_id` FOREIGN KEY (`q_id`) REFERENCES `ccoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色评价表';

-- ----------------------------
-- Records of ccoa_shoot_appraise
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_shoot_appraise_result
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_appraise_result`;
CREATE TABLE `ccoa_shoot_appraise_result` (
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` varchar(36) CHARACTER SET utf8 NOT NULL COMMENT '用户id',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `role_name` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '角色',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '得分',
  `data` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '数据详细',
  PRIMARY KEY (`b_id`,`u_id`,`q_id`,`role_name`),
  KEY `pk_u_id` (`u_id`) USING BTREE,
  KEY `pk_q_id_pk` (`q_id`),
  KEY `pk_role_name` (`role_name`),
  CONSTRAINT `ccoa_shoot_appraise_result_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ccoa_shoot_appraise_result_ibfk_2` FOREIGN KEY (`role_name`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_b_id` FOREIGN KEY (`b_id`) REFERENCES `ccoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_q_id_pk` FOREIGN KEY (`q_id`) REFERENCES `ccoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色评价结果表';

-- ----------------------------
-- Records of ccoa_shoot_appraise_result
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_shoot_appraise_template
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_appraise_template`;
CREATE TABLE `ccoa_shoot_appraise_template` (
  `role_name` varchar(64) NOT NULL DEFAULT '' COMMENT '角色',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `value` tinyint(3) DEFAULT '3' COMMENT '得分',
  `index` tinyint(3) DEFAULT '-1' COMMENT '索引',
  PRIMARY KEY (`role_name`,`q_id`),
  KEY `pk_qid` (`q_id`),
  CONSTRAINT `ccoa_shoot_appraise_template_ibfk_1` FOREIGN KEY (`role_name`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_qid` FOREIGN KEY (`q_id`) REFERENCES `ccoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色评价模版表';

-- ----------------------------
-- Records of ccoa_shoot_appraise_template
-- ----------------------------
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_contact', '4', '3', null);
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_contact', '5', '3', null);
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_contact', '6', '3', null);
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_shoot_man', '1', '3', null);
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_shoot_man', '2', '3', null);
INSERT INTO `ccoa_shoot_appraise_template` VALUES ('r_shoot_man', '3', '3', '-1');

-- ----------------------------
-- Table structure for ccoa_shoot_bookdetail
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_bookdetail`;
CREATE TABLE `ccoa_shoot_bookdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(4) DEFAULT NULL COMMENT '场景id',
  `fw_college` int(11) DEFAULT NULL COMMENT 'rms数据库: 项目',
  `fw_project` int(11) DEFAULT NULL COMMENT 'rms数据库: 子项目',
  `fw_course` int(11) DEFAULT NULL COMMENT 'rms数据库: 课程',
  `lession_time` tinyint(2) DEFAULT '1' COMMENT '课时',
  `u_teacher` varchar(36) DEFAULT NULL COMMENT '老师',
  `u_contacter` varchar(36) DEFAULT NULL COMMENT '接洽人',
  `u_booker` varchar(36) DEFAULT NULL COMMENT '预约人',
  `u_shoot_man` varchar(36) DEFAULT NULL COMMENT '摄影师',
  `book_time` int(11) DEFAULT NULL COMMENT '约定时间 ',
  `index` tinyint(2) DEFAULT NULL COMMENT '顺序',
  `shoot_mode` tinyint(2) DEFAULT '2' COMMENT '拍摄模式',
  `photograph` tinyint(1) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '状态',
  `create_by` varchar(36) DEFAULT NULL COMMENT '任务创建者',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新于',
  `ver` int(11) DEFAULT '0' COMMENT '乐观锁',
  `remark` varchar(255) DEFAULT '无' COMMENT '备注',
  `start_time` char(20) DEFAULT NULL COMMENT '开始时间',
  `business_id` int(11) DEFAULT NULL COMMENT '类别id',
  PRIMARY KEY (`id`),
  KEY `fk_college` (`fw_college`),
  KEY `fk_project` (`fw_project`),
  KEY `fk_course` (`fw_course`),
  KEY `fk_contacter` (`u_contacter`),
  KEY `fk_booker` (`u_booker`),
  KEY `fk_site_id` (`site_id`),
  KEY `fk_creater` (`create_by`),
  KEY `u_shoot_man` (`u_shoot_man`),
  KEY `fk_business_id` (`business_id`),
  KEY `fk_teacher` (`u_teacher`),
  CONSTRAINT `ccoa_shoot_bookdetail_ibfk_1` FOREIGN KEY (`fw_college`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ccoa_shoot_bookdetail_ibfk_2` FOREIGN KEY (`fw_project`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ccoa_shoot_bookdetail_ibfk_3` FOREIGN KEY (`fw_course`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ccoa_shoot_bookdetail_ibfk_4` FOREIGN KEY (`business_id`) REFERENCES `ccoa_framework_item_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_booker` FOREIGN KEY (`u_booker`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_contacter` FOREIGN KEY (`u_contacter`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_create_by` FOREIGN KEY (`create_by`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_shoot_man` FOREIGN KEY (`u_shoot_man`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_site_id` FOREIGN KEY (`site_id`) REFERENCES `ccoa_shoot_site` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher` FOREIGN KEY (`u_teacher`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1436 DEFAULT CHARSET=utf8 COMMENT='预约拍摄任务表';

-- ----------------------------
-- Records of ccoa_shoot_bookdetail
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_shoot_bookdetail_role_name
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_bookdetail_role_name`;
CREATE TABLE `ccoa_shoot_bookdetail_role_name` (
  `b_id` int(11) NOT NULL COMMENT '拍摄任务ID',
  `u_id` varchar(36) NOT NULL COMMENT '用户角色ID',
  `role_name` varchar(64) NOT NULL COMMENT '角色',
  `primary_foreign` int(2) DEFAULT '1' COMMENT '主从',
  `iscancel` varchar(4) NOT NULL DEFAULT 'N' COMMENT '拍摄任务是否为:取消，  Y为取消',
  PRIMARY KEY (`b_id`,`u_id`,`role_name`),
  KEY `u_id` (`u_id`),
  KEY `role_name` (`role_name`),
  CONSTRAINT `ccoa_shoot_bookdetail_role_name_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ccoa_shoot_bookdetail_role_name_ibfk_2` FOREIGN KEY (`role_name`) REFERENCES `ccoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bid` FOREIGN KEY (`b_id`) REFERENCES `ccoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约拍摄角色分配表';

-- ----------------------------
-- Records of ccoa_shoot_bookdetail_role_name
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_shoot_history
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_history`;
CREATE TABLE `ccoa_shoot_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` varchar(36) NOT NULL COMMENT '用户id',
  `type` tinyint(2) DEFAULT '1' COMMENT '操作类型',
  `history` varchar(500) DEFAULT NULL COMMENT '历史记录',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  KEY `fk_b_id` (`b_id`),
  KEY `fk_u_id` (`u_id`),
  CONSTRAINT `fk_b_id` FOREIGN KEY (`b_id`) REFERENCES `ccoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_u_id` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='历史原因表';

-- ----------------------------
-- Records of ccoa_shoot_history
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_shoot_site
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_site`;
CREATE TABLE `ccoa_shoot_site` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '场景名称',
  `traffic` varchar(255) DEFAULT NULL COMMENT '交通、坐车指引',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='预约拍摄场地表';

-- ----------------------------
-- Records of ccoa_shoot_site
-- ----------------------------
INSERT INTO `ccoa_shoot_site` VALUES ('1', '7-1摄', '越秀区麓景西路41号广州广播电视大学7号楼一楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '7号楼一楼，蓝箱、访真场景拍摄');
INSERT INTO `ccoa_shoot_site` VALUES ('2', '7-1音', '越秀区麓景西路41号广州广播电视大学7号楼一楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '7号楼一楼，声音录制，隔音效果超好');
INSERT INTO `ccoa_shoot_site` VALUES ('3', '6-5摄', '越秀区麓景西路41号广州广播电视大学6号楼五楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '6号楼五楼，标高清拍摄');

-- ----------------------------
-- Table structure for ccoa_shoot_site_rule
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_shoot_site_rule`;
CREATE TABLE `ccoa_shoot_site_rule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '规则名',
  `type` tinyint(2) NOT NULL COMMENT '规则类型',
  `site` int(4) DEFAULT NULL COMMENT '关联场地',
  `start_time` int(11) DEFAULT NULL COMMENT '起始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`site`),
  CONSTRAINT `fk_site` FOREIGN KEY (`site`) REFERENCES `ccoa_shoot_site` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约拍摄场地规则表';

-- ----------------------------
-- Records of ccoa_shoot_site_rule
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_system
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_system`;
CREATE TABLE `ccoa_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块id',
  `name` varchar(64) DEFAULT NULL COMMENT '模块名',
  `module_image` varchar(255) DEFAULT NULL COMMENT '模块图片',
  `module_link` varchar(255) DEFAULT '#' COMMENT '描述',
  `des` varchar(255) DEFAULT NULL COMMENT '模块链接',
  `isjump` tinyint(1) DEFAULT NULL COMMENT '是否跳转页面, 1:为是',
  `aliases` varchar(255) DEFAULT NULL COMMENT '模块别名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='平台系统表';

-- ----------------------------
-- Records of ccoa_system
-- ----------------------------
INSERT INTO `ccoa_system` VALUES ('1', '项目管理', '/filedata/site/system/course_project.png', '/teamwork/default', '专门提供课程项目管理', '0', 'teamwork');
INSERT INTO `ccoa_system` VALUES ('2', '预约拍摄', '/filedata/site/system/shoot_bookdeta.png', '/shoot/bookdetail', '专门提供编导与摄影师拍摄任务预约工作', '0', 'shoot');
INSERT INTO `ccoa_system` VALUES ('4', '专家库', '/filedata/site/system/expert_database.png', '/expert/default', '专门提供专家库信息', '0', 'expert');
INSERT INTO `ccoa_system` VALUES ('8', '资源展示', '/filedata/site/system/resources_display.png', '/resource/default', '', '0', 'resource');
INSERT INTO `ccoa_system` VALUES ('9', '文档管理', '/filedata/site/system/file_manage.png', '/filemanage/file', '', '0', 'filemanage');

-- ----------------------------
-- Table structure for ccoa_team
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_team`;
CREATE TABLE `ccoa_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '团队名称',
  `type` int(11) DEFAULT NULL COMMENT '团队类别',
  `des` varchar(255) DEFAULT '无' COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_team_type` (`type`),
  CONSTRAINT `fk_team_type` FOREIGN KEY (`type`) REFERENCES `ccoa_team_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='平台团队表';

-- ----------------------------
-- Records of ccoa_team
-- ----------------------------
INSERT INTO `ccoa_team` VALUES ('1', '一部', '1', '以大专本科工科为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('2', '二部', '1', '以大专本科文科为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('3', '三部', '1', '以中专为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('4', '四部', '1', '以中专为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('5', '五部', '1', '以政府业务为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('6', '六部', '1', '以大学生教育、教师资格培训为主，面向全业务');
INSERT INTO `ccoa_team` VALUES ('7', '一测', '1', '测试专用');

-- ----------------------------
-- Table structure for ccoa_teamwork_course_link
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_course_link`;
CREATE TABLE `ccoa_teamwork_course_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `course_phase_id` int(11) NOT NULL COMMENT '课程阶段',
  `link_id` int(11) NOT NULL COMMENT '环节ID',
  `total` int(11) DEFAULT '1' COMMENT '总数',
  `completed` int(11) DEFAULT '0' COMMENT '已完成数',
  `is_delete` varchar(4) DEFAULT 'N' COMMENT '是否删除：Y为是，N为否',
  PRIMARY KEY (`id`),
  KEY `fk_cl_course_id` (`course_id`),
  KEY `fk_cl_link_id` (`link_id`),
  KEY `fk_cl_course_phase_id` (`course_phase_id`),
  CONSTRAINT `fk_cl_course_phase_id` FOREIGN KEY (`course_phase_id`) REFERENCES `ccoa_teamwork_course_phase` (`phase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cl_course_id` FOREIGN KEY (`course_id`) REFERENCES `ccoa_teamwork_course_manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cl_link_id` FOREIGN KEY (`link_id`) REFERENCES `ccoa_teamwork_link_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1079 DEFAULT CHARSET=utf8 COMMENT='团队合作课程管理环节表';

-- ----------------------------
-- Records of ccoa_teamwork_course_link
-- ----------------------------
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1057', '86', '8', '23', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1058', '86', '8', '24', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1059', '86', '8', '25', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1060', '86', '8', '26', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1061', '86', '8', '27', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1062', '86', '9', '28', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1063', '86', '9', '29', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1064', '86', '10', '30', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1065', '86', '10', '31', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1066', '86', '10', '32', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1067', '86', '10', '33', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1068', '86', '10', '34', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1069', '86', '10', '35', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1070', '86', '10', '36', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1071', '86', '10', '37', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1072', '86', '10', '38', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1073', '86', '10', '39', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1074', '86', '11', '40', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1075', '86', '12', '41', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1076', '86', '13', '42', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1077', '86', '13', '43', '1', '0', 'N');
INSERT INTO `ccoa_teamwork_course_link` VALUES ('1078', '86', '14', '44', '1', '0', 'N');

-- ----------------------------
-- Table structure for ccoa_teamwork_course_manage
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_course_manage`;
CREATE TABLE `ccoa_teamwork_course_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL COMMENT '该课程对应的项目ID',
  `course_id` int(11) DEFAULT NULL COMMENT '课程ID',
  `teacher` varchar(36) DEFAULT NULL COMMENT '主讲老师',
  `weekly_editors_people` varchar(36) DEFAULT NULL COMMENT '周报编辑人',
  `credit` int(11) DEFAULT '1' COMMENT '学分',
  `lession_time` int(11) DEFAULT '1' COMMENT '学时',
  `video_length` int(11) DEFAULT NULL COMMENT '视频时长',
  `question_mete` int(11) DEFAULT NULL COMMENT '题量',
  `case_number` int(11) DEFAULT NULL COMMENT '案例数',
  `activity_number` int(11) DEFAULT NULL COMMENT '活动数',
  `team_id` int(11) DEFAULT NULL COMMENT '团队ID',
  `course_ops` varchar(36) DEFAULT NULL COMMENT '课程运维负责人',
  `create_by` varchar(36) DEFAULT NULL COMMENT '创建者',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新于',
  `plan_start_time` varchar(60) DEFAULT NULL COMMENT '计划开始时间',
  `plan_end_time` varchar(60) DEFAULT NULL COMMENT '计划完成时间',
  `real_carry_out` varchar(60) DEFAULT '无' COMMENT '实际完成时间',
  `status` int(11) DEFAULT '5' COMMENT '当前状态： 5为正常，15为完成',
  `des` varchar(255) DEFAULT '无' COMMENT '课程描述',
  `path` varchar(255) DEFAULT NULL COMMENT '存储服务器路径',
  PRIMARY KEY (`id`),
  KEY `fk_project_id` (`project_id`),
  KEY `fk_course_id` (`course_id`),
  KEY `fk_course_create_by` (`create_by`),
  KEY `fk_course_teacher` (`teacher`),
  KEY `fk_cm_team_id` (`team_id`),
  KEY `fk_weekly_editors_people` (`weekly_editors_people`),
  KEY `fk_course_ops` (`course_ops`),
  CONSTRAINT `fk_cm_team_id` FOREIGN KEY (`team_id`) REFERENCES `ccoa_team` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_course_create_by` FOREIGN KEY (`create_by`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_course_teacher` FOREIGN KEY (`teacher`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `ccoa_teamwork_item_manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_weekly_editors_people` FOREIGN KEY (`weekly_editors_people`) REFERENCES `ccoa_team_member` (`u_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 COMMENT='团队合作课程管理表';

-- ----------------------------
-- Records of ccoa_teamwork_course_manage
-- ----------------------------
INSERT INTO `ccoa_teamwork_course_manage` VALUES ('86', '30', '80', '827c778917e76db96600b77f41fbc632', '12', '3', '2', '1468947600', '1', '1', null, '4', '', '12', '1468994083', '1468994083', '2016-07-20 11:34', '2016-07-20 11:34', '无', '5', '无', '');

-- ----------------------------
-- Table structure for ccoa_teamwork_course_phase
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_course_phase`;
CREATE TABLE `ccoa_teamwork_course_phase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `phase_id` int(11) NOT NULL COMMENT '阶段ID',
  `weights` decimal(11,2) DEFAULT '0.10' COMMENT '权重',
  `is_delete` varchar(4) DEFAULT 'N' COMMENT '是否删除：Y为是，N为否',
  PRIMARY KEY (`id`),
  KEY `fk_cp_course_id` (`course_id`),
  KEY `fk_cp_phase_id` (`phase_id`),
  CONSTRAINT `fk_cp_course_id` FOREIGN KEY (`course_id`) REFERENCES `ccoa_teamwork_course_manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cp_phase_id` FOREIGN KEY (`phase_id`) REFERENCES `ccoa_teamwork_phase_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=400 DEFAULT CHARSET=utf8 COMMENT='团队合作课程管理阶段表';

-- ----------------------------
-- Records of ccoa_teamwork_course_phase
-- ----------------------------
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('393', '86', '8', '0.10', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('394', '86', '9', '0.10', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('395', '86', '10', '0.60', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('396', '86', '11', '0.05', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('397', '86', '12', '0.05', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('398', '86', '13', '0.05', 'N');
INSERT INTO `ccoa_teamwork_course_phase` VALUES ('399', '86', '14', '0.05', 'N');

-- ----------------------------
-- Table structure for ccoa_teamwork_course_producer
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_course_producer`;
CREATE TABLE `ccoa_teamwork_course_producer` (
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `producer` varchar(36) NOT NULL COMMENT '资源制作人(resource_producer)',
  PRIMARY KEY (`course_id`,`producer`),
  KEY `fk_crp_producer` (`producer`),
  CONSTRAINT `fk_crp_producer` FOREIGN KEY (`producer`) REFERENCES `ccoa_team_member` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_crp_course_id` FOREIGN KEY (`course_id`) REFERENCES `ccoa_teamwork_course_manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团队合作课程管理资源制作人表';

-- ----------------------------
-- Records of ccoa_teamwork_course_producer
-- ----------------------------
INSERT INTO `ccoa_teamwork_course_producer` VALUES ('86', '10');
INSERT INTO `ccoa_teamwork_course_producer` VALUES ('86', '12');
INSERT INTO `ccoa_teamwork_course_producer` VALUES ('86', '18c7a607148f64d5c0277c2c6b263bc4');
INSERT INTO `ccoa_teamwork_course_producer` VALUES ('86', '967624f78ad9ad1431ed397c047d0054');

-- ----------------------------
-- Table structure for ccoa_teamwork_course_summary
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_course_summary`;
CREATE TABLE `ccoa_teamwork_course_summary` (
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `create_time` varchar(60) NOT NULL COMMENT '创建时间',
  `content` varchar(255) DEFAULT '无' COMMENT '总结',
  `create_by` varchar(36) DEFAULT NULL COMMENT '周报开发人',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '修改于',
  PRIMARY KEY (`course_id`,`create_time`),
  KEY `fk_cs_create_by` (`create_by`),
  CONSTRAINT `fk_cs_create_by` FOREIGN KEY (`create_by`) REFERENCES `ccoa_teamwork_course_manage` (`weekly_editors_people`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cs_course_id` FOREIGN KEY (`course_id`) REFERENCES `ccoa_teamwork_course_manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团队合作课程管理周报表';

-- ----------------------------
-- Records of ccoa_teamwork_course_summary
-- ----------------------------

-- ----------------------------
-- Table structure for ccoa_teamwork_item_manage
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_item_manage`;
CREATE TABLE `ccoa_teamwork_item_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_id` int(11) DEFAULT NULL COMMENT '项目类别',
  `item_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `item_child_id` int(11) DEFAULT NULL COMMENT '子项目ID',
  `team_id` int(11) DEFAULT NULL COMMENT '团队ID',
  `create_by` varchar(36) DEFAULT NULL COMMENT '创建人',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新于',
  `forecast_time` varchar(60) DEFAULT NULL COMMENT '预计上线时间',
  `real_carry_out` varchar(60) DEFAULT '无' COMMENT '实际完成时间',
  `status` int(11) DEFAULT '5' COMMENT '状态：25为暂停，5为正常，15为完成',
  `background` varchar(255) DEFAULT '无' COMMENT '项目背景',
  `use` varchar(255) DEFAULT '无' COMMENT '项目用途',
  PRIMARY KEY (`id`),
  KEY `fk_fim_create_by` (`create_by`),
  KEY `fk_tw_team_id` (`team_id`),
  KEY `fk_fim_item_child_id` (`item_child_id`),
  KEY `fk_fim_item_id` (`item_id`),
  KEY `fk_fim_item_type_id` (`item_type_id`),
  CONSTRAINT `fk_fim_create_by` FOREIGN KEY (`create_by`) REFERENCES `ccoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_fim_item_child_id` FOREIGN KEY (`item_child_id`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fim_item_id` FOREIGN KEY (`item_id`) REFERENCES `ccoa_framework_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fim_item_type_id` FOREIGN KEY (`item_type_id`) REFERENCES `ccoa_framework_item_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tw_team_id` FOREIGN KEY (`team_id`) REFERENCES `ccoa_team` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='团队合作项目管理表';

-- ----------------------------
-- Records of ccoa_teamwork_item_manage
-- ----------------------------
INSERT INTO `ccoa_teamwork_item_manage` VALUES ('30', '3', '67', '68', '4', '12', '1468985631', '1468985631', '2016-07-29 19:00', '无', '5', '无', '无');

-- ----------------------------
-- Table structure for ccoa_teamwork_link_template
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_link_template`;
CREATE TABLE `ccoa_teamwork_link_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phase_id` int(11) DEFAULT NULL COMMENT '阶段ID',
  `name` varchar(255) DEFAULT NULL COMMENT '环节名称',
  `type` int(2) DEFAULT '0' COMMENT '类型：0为状态，1为数量',
  `total` int(11) DEFAULT '1' COMMENT '总数',
  `completed` int(11) DEFAULT '0' COMMENT '已完成数',
  `unit` varchar(16) DEFAULT NULL COMMENT '单位',
  `create_by` varchar(36) DEFAULT NULL COMMENT '创建者',
  `index` tinyint(3) DEFAULT '-1' COMMENT '索引',
  `is_delete` varchar(4) DEFAULT 'N' COMMENT '是否删除：Y为是，N为否',
  PRIMARY KEY (`id`),
  KEY `fk_phase_id` (`phase_id`),
  KEY `fk_link_create_by` (`create_by`),
  CONSTRAINT `fk_phase_id` FOREIGN KEY (`phase_id`) REFERENCES `ccoa_teamwork_phase_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='团队合作环节模版表';

-- ----------------------------
-- Records of ccoa_teamwork_link_template
-- ----------------------------
INSERT INTO `ccoa_teamwork_link_template` VALUES ('23', '8', '确定教学目标', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('24', '8', '确定课程教学目标', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('25', '8', '确定教材', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('26', '8', '确定师资', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('27', '8', '确定课程教学设计', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('28', '9', '内容设计', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('29', '9', '内容制作', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('30', '10', '知识型脚本', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('31', '10', '技能型脚本', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('32', '10', '真人情景演绎', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('33', '10', '动画演绎', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('34', '10', '板书演绎', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('35', '10', '情景交互训练（2D）', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('36', '10', '情景交互训练（3D）', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('37', '10', '习题（练习）', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('38', '10', '习题（测验）', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('39', '10', '测评交互', '1', '1', '0', '个', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('40', '11', '课程整合', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('41', '12', '课程测试', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('42', '13', '内部评审', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('43', '13', '外部评审', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_link_template` VALUES ('44', '14', '课程验收', '0', '1', '0', '', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');

-- ----------------------------
-- Table structure for ccoa_teamwork_phase_template
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_teamwork_phase_template`;
CREATE TABLE `ccoa_teamwork_phase_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '阶段名称',
  `weights` decimal(11,2) DEFAULT '0.10' COMMENT '权重',
  `create_by` varchar(36) DEFAULT NULL COMMENT '创建者',
  `index` tinyint(3) DEFAULT '-1' COMMENT '索引',
  `is_delete` varchar(4) DEFAULT 'N' COMMENT '是否删除：Y为是，N为否',
  PRIMARY KEY (`id`),
  KEY `fk_phase_create_by` (`create_by`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='团队合作阶段模版表';

-- ----------------------------
-- Records of ccoa_teamwork_phase_template
-- ----------------------------
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('8', '设计', '0.10', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('9', '教材开发', '0.10', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('10', '课程研发', '0.60', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('11', '课程整合', '0.05', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('12', '课程测试', '0.05', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('13', '课程评审', '0.05', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');
INSERT INTO `ccoa_teamwork_phase_template` VALUES ('14', '课程验收', '0.05', '8292ddc6b3a1c06ef8143dc590c9e169', '-1', 'N');

-- ----------------------------
-- Table structure for ccoa_team_member
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_team_member`;
CREATE TABLE `ccoa_team_member` (
  `team_id` int(11) NOT NULL COMMENT '所属团队ID',
  `u_id` varchar(36) NOT NULL COMMENT '用户ID',
  `is_leader` varchar(4) DEFAULT 'N' COMMENT '是否为队长：Y为是，N为否',
  `index` int(11) DEFAULT '1' COMMENT '索引',
  `position` varchar(60) DEFAULT NULL COMMENT '职位',
  PRIMARY KEY (`team_id`,`u_id`),
  KEY `fk_team_u_id` (`u_id`),
  CONSTRAINT `fk_team_id` FOREIGN KEY (`team_id`) REFERENCES `ccoa_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_team_u_id` FOREIGN KEY (`u_id`) REFERENCES `ccoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团队成员表';

-- ----------------------------
-- Records of ccoa_team_member
-- ----------------------------
INSERT INTO `ccoa_team_member` VALUES ('1', '36aa1fcd1f89849aede1e63aec86a7b8', 'N', '3', '摄影后期');
INSERT INTO `ccoa_team_member` VALUES ('1', '4365937c93ba2435997a696d6bf313e8', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('1', '9ca3903e3cd8bb2d33f53833c4255090', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('1', 'c1bfd128c5e99b1a4a2f0376764c48de', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('1', 'ca5863a64daf0153625726f146880283', 'N', '3', '影视后期');
INSERT INTO `ccoa_team_member` VALUES ('1', 'f79b8baa143765a6dc9812c249aadd59', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('2', '15', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('2', '377686460a80b3a3863f32e45378c20d', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('2', '56750e09e917df92058da8a2210af1db', 'N', '3', '影视后期');
INSERT INTO `ccoa_team_member` VALUES ('2', '7a0ee49d4a8417a080ba9f541a8735ce', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('2', 'be3b4aae2db5091ce4231081234215e7', 'N', '3', '影视后期');
INSERT INTO `ccoa_team_member` VALUES ('2', 'c08dc0e17864653d28bca74840087492', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('3', '0519497ca8a2cd0a6c9cd2bf976e56fe', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('3', '23638ea73551c25229bfd86a52360824', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('3', '90871b23573f55dad5bf6dc069a05521', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('3', 'ee0139f41e43475a75c507c8ce10ea41', 'N', '3', '影视后期');
INSERT INTO `ccoa_team_member` VALUES ('4', '10', 'N', '3', '影视后期');
INSERT INTO `ccoa_team_member` VALUES ('4', '12', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('4', '18c7a607148f64d5c0277c2c6b263bc4', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('4', '967624f78ad9ad1431ed397c047d0054', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('5', '21', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('5', '25bf83933672773a92a7d44aee05785c', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('5', '4d4c2ca17f6eef7929bfa92c04b54bf1', 'N', '2', '教学编导');
INSERT INTO `ccoa_team_member` VALUES ('6', 'd72b80140dab77065099512042bdb648', 'Y', '1', '开发经理');
INSERT INTO `ccoa_team_member` VALUES ('7', 'test_2', 'Y', null, '编导');
INSERT INTO `ccoa_team_member` VALUES ('7', 'test_6', 'Y', null, '开发经理');

-- ----------------------------
-- Table structure for ccoa_team_type
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_team_type`;
CREATE TABLE `ccoa_team_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '团队类别',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='团队类型表';

-- ----------------------------
-- Records of ccoa_team_type
-- ----------------------------
INSERT INTO `ccoa_team_type` VALUES ('1', '项目课程管理', '项目课程管理团队');

-- ----------------------------
-- Table structure for ccoa_user
-- ----------------------------
DROP TABLE IF EXISTS `ccoa_user`;
CREATE TABLE `ccoa_user` (
  `id` varchar(36) NOT NULL,
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) DEFAULT NULL,
  `password` varchar(64) NOT NULL COMMENT '密码',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT '密码重置',
  `sex` tinyint(2) DEFAULT '1' COMMENT '性别',
  `email` varchar(255) DEFAULT NULL COMMENT '电子邮件',
  `status` int(2) DEFAULT '10' COMMENT '状态',
  `nickname` varchar(128) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT '/filedata/avatars/default_avatar.jpg' COMMENT '头像',
  `ee` varchar(255) DEFAULT NULL COMMENT 'ee号',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新于',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台用户表';

-- ----------------------------
-- Records of ccoa_user
-- ----------------------------
INSERT INTO `ccoa_user` VALUES ('025a11105fedb3580055cce355457c0b', 'zhuangxiaoyan', 'ekzKxVgRPjFFX0Aoz--s2HWAipHt5ODd', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '庄晓燕', '/filedata/avatars/default_avatar.jpg', '', '', '1456819128', '1456898205');
INSERT INTO `ccoa_user` VALUES ('0519497ca8a2cd0a6c9cd2bf976e56fe', 'tuling', 'tw4HvzCe4tixtXmAzJKeVtRxN6iCsSlp', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'tuling@eenet.com', '10', '涂玲', '/filedata/avatars/default_avatar.jpg', '101463440', '15913176373', '1457509945', '1468987099');
INSERT INTO `ccoa_user` VALUES ('0597f9e625412af1d77cfda9545302ce', 'ludan', 'LLb9B_Zt4JEZyMNwkw6v0uhpLJ-SXBK9', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'ludan@eenet.com', '10', '卢丹', '/filedata/avatars/default_avatar.jpg', '165760397', '', '1457509791', '1457509791');
INSERT INTO `ccoa_user` VALUES ('0e9f5f5cb3de66fe9f00d90190558542', 'zhongjian', 'l3mQZwF7-mAQx4TkwyPUElTyH3C2UMTO', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhongjian@eenet.com', '10', '钟健', '/filedata/avatars/default_avatar.jpg', '165760807', '18565337926', '1468986729', '1468986745');
INSERT INTO `ccoa_user` VALUES ('10', 'lindi', '0ApHSP2V2PMG4zxYTICURtgb9ESUCI0v', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'lindi@eenet.com', '10', '林迪', '/filedata/avatars/default_avatar.jpg', '165767694', '13570903870', '1445226803', '1468986835');
INSERT INTO `ccoa_user` VALUES ('11', 'linyuxing', 'tEuqIO-8KYoq2OTt4UiIEiXF8QBt4trf', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'linyuxing@eenet.com', '10', '林育兴', '/filedata/avatars/default_avatar.jpg', '101480657', '13694289897', '1445226883', '1468987262');
INSERT INTO `ccoa_user` VALUES ('12', 'huanglina', 'lZKYTKlqHFdDow_ASLJca4EhBq2rCT0z', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'huanglina@eenet.com', '10', '黄丽娜', '/filedata/avatars/default_avatar.jpg', '100100194', '15013088344', '1445226973', '1468986599');
INSERT INTO `ccoa_user` VALUES ('13', 'zouwei', 'JoxrwkO5XWBKp4m-QeTyx-NoTi_s6aM1', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '邹伟', '/filedata/avatars/default_avatar.jpg', '165760648', '18520430109', '1445227144', '1468987274');
INSERT INTO `ccoa_user` VALUES ('14', 'xiezhiwei', 'G7S932XljeJucxcqvRtBu3STlCYy0qiJ', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'xiezhiwei@eenet.com', '10', '谢志伟', '/filedata/avatars/default_avatar.jpg', '165767662', '15813335421', '1445227237', '1468987016');
INSERT INTO `ccoa_user` VALUES ('15', 'litin', '8aW_bCW2EomJTviD7XseOPnLiD3sVSn5', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'litin@eenet.com', '10', '李婷', '/filedata/avatars/default_avatar.jpg', '165763653', '18903074092', '1446429911', '1468987241');
INSERT INTO `ccoa_user` VALUES ('18c7a607148f64d5c0277c2c6b263bc4', 'yangxuejiao', 'kzf2BgZFw9zAztE5JSmIwJ44BDbPuYGi', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'yangxuejiao@eenet.com', '10', '杨雪娇', '/filedata/avatars/default_avatar.jpg', '165760058', '18902248680', '1457510357', '1468986797');
INSERT INTO `ccoa_user` VALUES ('191496af43bda40392a5e99bae059a27', 'fangyusheng', 'zvVp7Ewo-EHyybrDtaSPJm49z6DUu7gy', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'fangyusheng@eenet.com', '10', '方育生', '/filedata/avatars/default_avatar.jpg', '165760240', '', '1457509402', '1457509402');
INSERT INTO `ccoa_user` VALUES ('21', 'wuzhiqiang', '8nEVHs4a345u6CXxoRuIwcJZiWcBZR6C', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'wuzhiqiang@eenet.com', '10', '吴智强', '/filedata/avatars/default_avatar.jpg', '100100228', '18664809449', '1446443740', '1468986862');
INSERT INTO `ccoa_user` VALUES ('23638ea73551c25229bfd86a52360824', 'zhengyeqing', 'hkn-0050nq-6fnNCJYuomqyZYqpYUr62', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zhengyeqing@eenet.com', '10', '郑叶清', '/filedata/avatars/default_avatar.jpg', '165760723', '13763340307', '1457511132', '1468987087');
INSERT INTO `ccoa_user` VALUES ('25bf83933672773a92a7d44aee05785c', 'zhanglili', 'tqO9CtPuCKx1aza4JxhaNidJm3cSgfvc', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zhanglili@eenet.com', '10', '张丽丽', '/filedata/avatars/default_avatar.jpg', '165760107', '13066332270', '1457510470', '1468987061');
INSERT INTO `ccoa_user` VALUES ('36aa1fcd1f89849aede1e63aec86a7b8', 'wengerdi', '8GIrjxMSBxvqjQ42mwqvn3rZcx7Q7JGP', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'wengerdi@eenet.com', '10', '翁二娣', '', '165760683', '13631149619', '1468981178', '1468981178');
INSERT INTO `ccoa_user` VALUES ('377686460a80b3a3863f32e45378c20d', 'chenwenxiang', 'INxaLcsmJNcjsKXAAeFnxE3QoY1rRwND', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'chenwenxiang@eenet.com', '10', '陈文香', '/filedata/avatars/default_avatar.jpg', '100100181', '13824453965', '1457509861', '1468986962');
INSERT INTO `ccoa_user` VALUES ('39e7a8f6c27193f88ffd3dcf0f59244c', 'hexiaoen', '3I6RwZMaaI-EWMlppslrnsARhVX66q16', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'hexiaoen@eenet.com', '10', '何晓恩', '/filedata/avatars/default_avatar.jpg', '100100179', '', '1457510039', '1457510039');
INSERT INTO `ccoa_user` VALUES ('3c376bb33b1d43128f3785c5d214cd5e', 'admin', '3c376bb33b1d43128f3785c5d214cd5e', '21218CCA77804D2BA1922C33E0151105', '', '1', '123@qq.com', '10', '超级管理员', '/filedata/avatars/default_avatar.jpg', '', '88888888888', '1450409959', '1460346815');
INSERT INTO `ccoa_user` VALUES ('4365937c93ba2435997a696d6bf313e8', 'zengwenjuan', 'fpUzMql-SHsgfTULgHbOV9TolYOyymbo', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zengwenjuan@eenet.com', '10', '曾文娟', '/filedata/avatars/default_avatar.jpg', '165760302', '15920129688', '1457510280', '1468987155');
INSERT INTO `ccoa_user` VALUES ('4d4c2ca17f6eef7929bfa92c04b54bf1', 'huangxiaofang', 'Hh7Akg56cT9feWNQe-FM9xo99QhdwBgR', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'huangxiaofang@eenet.com', '10', '黄小芳', '/filedata/avatars/default_avatar.jpg', '165760155', '15902011905', '1457510333', '1468987045');
INSERT INTO `ccoa_user` VALUES ('55bcddd44d6745054e583b8d20047082', 'lijiaohua', 'xIHbY5u3DUgbKQm1P76Hy0Ah_HwlToZR', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'lijiaohua@eenet.com', '10', '李姣华', '/filedata/avatars/default_avatar.jpg', '165767632', '', '1457509372', '1457509372');
INSERT INTO `ccoa_user` VALUES ('56750e09e917df92058da8a2210af1db', 'liuwei', 'BB1yawACVTGs-jOBaTAOcHLXOm07trJq', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'liuwei@eenet.com', '10', '刘伟', '', '165760764', '18801163051', '1468981513', '1468981513');
INSERT INTO `ccoa_user` VALUES ('59', 'zhengguiqing', 'ehuQ2CT9OicALj0CNsBExnQ6MrHlZSLK', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '郑桂清', '/filedata/avatars/default_avatar.jpg', '165760648', '18565337926', '1448587566', '1468998618');
INSERT INTO `ccoa_user` VALUES ('7a0ee49d4a8417a080ba9f541a8735ce', 'fangyingna', 'xKbRD-1JDcqQbpRxnxRJI4A5O2p8jK6D', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'fangyingna@eenet.com', '10', '方颖娜', '/filedata/avatars/default_avatar.jpg', '165760716', '13560025573', '1457511107', '1468987075');
INSERT INTO `ccoa_user` VALUES ('7f3ffba1b7354efc409720dcc7360b36', 'wulinan', 'az0OOuODFALHb3P6-Kp5LCxZwA2tEE0h', '2823999B5F85C7FD8D9EDED57F5A550D', null, '1', 'wulinan@eenet.com', '10', '吴林安', '/filedata/avatars/default_avatar.jpg', '101480656', '18122101800', '1457509343', '1468975727');
INSERT INTO `ccoa_user` VALUES ('827c778917e76db96600b77f41fbc632', 'test_teacher', 'jsp9fvJY_xhMHVNLVxAHUVW-NCjrYzb9', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zhengguiqing@eenet.com', '10', '测试专家', '/filedata/avatars/default_avatar.jpg', '', '', '1468895626', '1468908286');
INSERT INTO `ccoa_user` VALUES ('8b8be80f1a961115a8975325f2a9b814', 'luzhixuan', 'uCS0z_-Qb7G-yZTzLd_tIV6X5U1LzxPt', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'luzhixuan@eenet.com', '10', '卢智轩', '/filedata/avatars/default_avatar.jpg', '165760551', '', '1457509820', '1457509820');
INSERT INTO `ccoa_user` VALUES ('9', 'wskeee', 'rgq5lSFeDMDtjf9wdLzIFGrywu0u5XD_', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'wskeee@163.com', '10', 'wskeee', '/filedata/avatars/default_avatar.jpg', '101463731', '', '1442374107', '1459825721');
INSERT INTO `ccoa_user` VALUES ('90871b23573f55dad5bf6dc069a05521', 'huangqiuling', '4wUvlldDaYZvrjQQ1EYBS91XXq3QqAtk', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'huangqiuling@eenet.com', '10', '黄秋玲', '/filedata/avatars/default_avatar.jpg', '165763728', '18922243921', '1457510421', '1468986815');
INSERT INTO `ccoa_user` VALUES ('967624f78ad9ad1431ed397c047d0054', 'zhangqiuyun', 'UfIg3YaM9SmObxKGzY982SQbPiM-Sw3d', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zhangqiuyun@eenet.com', '10', '张秋云', '/filedata/avatars/default_avatar.jpg', '165760094', '13694255087', '1457510253', '1468986849');
INSERT INTO `ccoa_user` VALUES ('98ca3083bb6a746414d4a2cd6d2db9c2', 'wangmin', 'L-br1BHyQQiQbqISietyYMSG56qh48U4', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'wangmin@eenet.com', '10', '王敏', '/filedata/avatars/default_avatar.jpg', '101480667', '13728016553', '1457510720', '1457510720');
INSERT INTO `ccoa_user` VALUES ('9ca3903e3cd8bb2d33f53833c4255090', 'fangyuan', 'Um4L3KofevAxVopKbP-uY0uXs7xRZr3t', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'fangyuan@eenet.com', '10', '方媛', '/filedata/avatars/default_avatar.jpg', '165760783', '18565590712', '1468986106', '1468986106');
INSERT INTO `ccoa_user` VALUES ('a45d14139dcee501744ba87edaa272a9', 'zhangjian', 'xFJ7ji6LtZW5wz7cBAyrpRHgUxq42qPB', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhangjian@eenet.com', '10', '张建', '/filedata/avatars/default_avatar.jpg', '165760728', '', '1457511161', '1457511161');
INSERT INTO `ccoa_user` VALUES ('a900761e88f648763ea19f80b0d41563', 'heyangchao', 'znjj-PYcqk5Eauqn5tJsJWEiz0U-BR2T', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '何阳超', '/filedata/avatars/default_avatar.jpg', '101463731', '15915762146', '1457511032', '1457511032');
INSERT INTO `ccoa_user` VALUES ('a95a13d7f00e9df75ba40c5f8713818b', 'zhuangyanxiao', 'qRdzERifV768VWL839LTgkhPeFlf1I2h', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'zhuangyanxiao@eenet.com', '10', '庄艳晓', '/filedata/avatars/default_avatar.jpg', '165760564', '', '1457510565', '1457510565');
INSERT INTO `ccoa_user` VALUES ('a979a77d5f8a158e79931b4397920de7', 'songci', 'Ik9n1XYxFJGD03gPoKz5JZcPSZ7jhulO', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'songci@eenet.com', '10', '宋词', '/filedata/avatars/default_avatar.jpg', '165760385', '18688870005', '1457510500', '1468987031');
INSERT INTO `ccoa_user` VALUES ('b360c86787a2a1510de034903c5cccf1', 'huangzongren', 'sCUAuTVXpKosXGWcZAmrS8ixrs4dN3_4', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'huangzongren@eenet.com', '10', '黄宗仁', '/filedata/avatars/default_avatar.jpg', '165760882', '13570415707', '1468986472', '1468987216');
INSERT INTO `ccoa_user` VALUES ('b533ecc67dcb295d77c94c4c9e422fc5', 'yuxinjing', 'ZEik-YeMXrgrYYU9-kSx6cL2M4YxppWx', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'yuxinjing@eenet.com', '10', '玉新靖', '/filedata/avatars/default_avatar.jpg', '165760156', '15521278238', '1457510225', '1468986900');
INSERT INTO `ccoa_user` VALUES ('be3b4aae2db5091ce4231081234215e7', 'yebidan', 'sLZgQfEu2MjHSjpYdsMnWqybuDSXHpTm', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'yebidan@eenet.com', '10', '叶碧丹', '/filedata/avatars/default_avatar.jpg', '165760304', '13662501402', '1457510798', '1468986949');
INSERT INTO `ccoa_user` VALUES ('c08dc0e17864653d28bca74840087492', 'huangluyi', 'MZFwbgsZPa094v4rBN5Ln8A_4-bIRX4D', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'huangluyi@eenet.com', '10', '黄露怡', '/filedata/avatars/default_avatar.jpg', '165763814', '13763318305', '1457510309', '1468986915');
INSERT INTO `ccoa_user` VALUES ('c157b7e2f8523ea4c2d60f0400a9dac8', 'xuyan', 'ol2dcu3dcsrfY25czkIRavBUXaJrySB1', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'xuyan@eenet.com', '10', '徐燕', '/filedata/avatars/default_avatar.jpg', '101463728', '18928843814', '1457509279', '1468986569');
INSERT INTO `ccoa_user` VALUES ('c1bfd128c5e99b1a4a2f0376764c48de', 'caihelian', 'EPNngfLBuQsvqfMuf9mEye85DjYpthjY', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'caihelian@eenet.com', '10', '蔡和连', '/filedata/avatars/default_avatar.jpg', '165760293', '13697407587', '1457510442', '1468986990');
INSERT INTO `ccoa_user` VALUES ('ca5863a64daf0153625726f146880283', 'tanzhaotang', 'oqxO4GbcTWQJ3gh2H0uw2_vK24_uT7t1', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'tanzhaotang@eenet.com', '10', '谭兆棠', '/filedata/avatars/default_avatar.jpg', '165760508', '13631413913', '1457510829', '1468987003');
INSERT INTO `ccoa_user` VALUES ('d72b80140dab77065099512042bdb648', 'yinyanlan', 'vDHDY2ov3PMnmcuRejNFpIQVc7_0gJwc', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'yinyanlan@eenet.com', '10', '尹艳兰', '/filedata/avatars/default_avatar.jpg', '100100203', '15920329743', '1457509973', '1468986778');
INSERT INTO `ccoa_user` VALUES ('d85245c2ad04f5cd834e823f65db475d', 'fengweilun', 'Gc2JQHfKfOvOGAkhEgB_E_o3YmsIYJWV', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'fengweilun@eenet.com', '10', '冯伟伦', '/filedata/avatars/default_avatar.jpg', '165760403', '13622207864', '1457510986', '1468987109');
INSERT INTO `ccoa_user` VALUES ('d9d4f1ffd12afbe22e80fe1d6020c7c9', 'wanghongrong', 'ni3l41dY0y-jMj2Q9nftT1pWVTdHbTmW', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'wanghongrong@eenet.com', '10', '王洪荣', '/filedata/avatars/default_avatar.jpg', '165760036', '15521072918', '1457510106', '1468987174');
INSERT INTO `ccoa_user` VALUES ('e8e3e400e7618323ef37d64a62c3e32a', 'herunning', 'VVm68YcSq1GjEUkaHt1pee5H3N-qeeX4', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'herunning@eenet.com', '10', '贺润宁', '/filedata/avatars/default_avatar.jpg', '165761022', '', '1468986281', '1468986281');
INSERT INTO `ccoa_user` VALUES ('eca5ecb3116d6e4d47c868aa6e7a3871', 'laiweihong', 'rmaVckua5n7XPWkyMSKfeCp8tLbDY-fb', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'laiweihong@eenet.com', '10', '赖伟虹', '/filedata/avatars/default_avatar.jpg', '165760044', '13570214952', '1457511055', '1468987229');
INSERT INTO `ccoa_user` VALUES ('ee0139f41e43475a75c507c8ce10ea41', 'liuzhongli', '7tm-RvecB0HnTslCBnvG7Xmxc_0vmfdY', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'liuzhongli@eenet.com', '10', '刘忠立', '/filedata/avatars/default_avatar.jpg', '165760509', '13017380998', '1457510854', '1468986617');
INSERT INTO `ccoa_user` VALUES ('efa3d497c5d75af6143f29352e5ab0f5', 'liuzhenna', 'OQOqhdR0hUEi9VpqE_-GVSrC8vZvFXtH', '21218CCA77804D2BA1922C33E0151105', null, '2', 'liuzhenna@eenet.com', '10', '刘振娜', '/filedata/avatars/default_avatar.jpg', '101463735', '15902059749', '1457318617', '1468986977');
INSERT INTO `ccoa_user` VALUES ('f79b8baa143765a6dc9812c249aadd59', 'dengweijuan', 'CnLQD9asQcSW1Znf9aPN3Q-2Z9j0Fwo6', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'dengweijuan@eenet.com', '10', '邓伟娟', '/filedata/avatars/default_avatar.jpg', '100100191', '18617391950', '1457509892', '1468987142');
INSERT INTO `ccoa_user` VALUES ('facf5c478af9d3bfd290f096743d4e2b', 'liangliang', 'dobj7WTn-QtChPvq-VoT_rJqW-MP5NXi', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'liangliang@eenet.com', '10', '梁良', '/filedata/avatars/default_avatar.jpg', '165760506', '18520430109', '1457511008', '1468987287');
INSERT INTO `ccoa_user` VALUES ('fc52039dc9a565ca103db7662e569e4b', 'cheyanshan', 'pLFxXZVeRAQMc2EUiShLfy122V8A7J5W', 'E10ADC3949BA59ABBE56E057F20F883E', null, '2', 'cheyanshan@eenet.com', '10', '车燕珊', '/filedata/avatars/default_avatar.jpg', '101463445', '', '1457511181', '1457511181');
INSERT INTO `ccoa_user` VALUES ('test_1', 'test_1', 'ZKoGaIKMgf8HcE13RPEaf7nr4P3jTSOE', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '编导组长1', '/filedata/avatars/default_avatar.jpg', '101463731', '15915762146', '1468894061', '1468998755');
INSERT INTO `ccoa_user` VALUES ('test_10', 'test_10', 'QqE2E4HyBW8UUXFztLj0wJRocWrVrTzL', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '编导10', '/filedata/avatars/default_avatar.jpg', '165760648', '', '1468894513', '1468916764');
INSERT INTO `ccoa_user` VALUES ('test_2', 'test_2', '4weG94POXCq9NoG00vB9qQtfK35psxCG', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '编导2', '/filedata/avatars/default_avatar.jpg', '101463731', '15915762146', '1468894111', '1468998770');
INSERT INTO `ccoa_user` VALUES ('test_3', 'test_3', 'ZFuifbXKZ2XKXBnpwq4xapLR0DDIuf9S', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '摄影组长3', '/filedata/avatars/default_avatar.jpg', '101463731', '', '1468894175', '1468998846');
INSERT INTO `ccoa_user` VALUES ('test_4', 'test_4', 'Wn_RVNT6HFaBbZYYZMfhZqjRG9D05go1', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '摄影师4', '/filedata/avatars/default_avatar.jpg', '101463731', '', '1468894217', '1468998878');
INSERT INTO `ccoa_user` VALUES ('test_5', 'test_5', 'dXCHmMABTychyoNeseotWWpVo1f3tWrK', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'heyangchao@eenet.com', '10', '编导5', '/filedata/avatars/default_avatar.jpg', '101463731', '', '1468894248', '1468916780');
INSERT INTO `ccoa_user` VALUES ('test_6', 'test_6', 'cxuNTuUxKmzltwtlE_LQelzKaP5toWGi', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '编导6', '/filedata/avatars/default_avatar.jpg', '165760648', '', '1468894335', '1468999092');
INSERT INTO `ccoa_user` VALUES ('test_7', 'test_7', 'FwX1iDWfEOK1ZN3cwh3b2pfyO54-MmN2', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '编导7', '/filedata/avatars/default_avatar.jpg', '165760648', '', '1468894381', '1468998961');
INSERT INTO `ccoa_user` VALUES ('test_8', 'test_8', 'ml10skuyW6legoqnmcWzuKBPkeLsUu-H', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '摄影组长8', '/filedata/avatars/default_avatar.jpg', '101480656', '', '1468894431', '1468999694');
INSERT INTO `ccoa_user` VALUES ('test_9', 'test_9', 'WVl_AJY-7hlzGsseVgg4ucpO0ZczE_aj', 'E10ADC3949BA59ABBE56E057F20F883E', null, '1', 'zhengguiqing@eenet.com', '10', '摄影师9', '/filedata/avatars/default_avatar.jpg', '165760648', '', '1468894467', '1468999008');
