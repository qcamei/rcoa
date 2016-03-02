/*
Navicat MySQL Data Transfer

Source Server         : rcoa
Source Server Version : 50525
Source Host           : 172.16.163.111:3306
Source Database       : rcoa

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2016-02-26 16:51:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `rcoa_auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_auth_assignment`;
CREATE TABLE `rcoa_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `item_name` (`item_name`),
  CONSTRAINT `rcoa_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of rcoa_auth_assignment
-- ----------------------------
INSERT INTO `rcoa_auth_assignment` VALUES ('p_new_publish', '10', '1446516425');
INSERT INTO `rcoa_auth_assignment` VALUES ('p_shoot_admin', '10', '1446516510');
INSERT INTO `rcoa_auth_assignment` VALUES ('p_shoot_cancel', '10', '1446516504');
INSERT INTO `rcoa_auth_assignment` VALUES ('p_shoot_cancel', '59', '1451015529');
INSERT INTO `rcoa_auth_assignment` VALUES ('p_shoot_index', '9', '1446539082');
INSERT INTO `rcoa_auth_assignment` VALUES ('p_shoot_own_cancel', '79', '1450954433');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_admin', '1', '1442371642');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_admin', '59', '1448587588');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_admin', '9', '1447224856');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '12', '1454638980');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '15', '1454639007');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '16', '1454639427');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '21', '1454639462');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '4', '1454639039');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '72', '1454639534');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_contact', '79', '1454652812');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_mp', '10', '1446514751');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_mp_leader', '11', '1446532532');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_leader', '13', '1446459041');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_leader', '65', '1449538096');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_leader', '74', '1451012166');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_man', '117', '1453778329');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_man', '14', '1446532504');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_man', '41', '1447831010');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_shoot_man', '76', '1449827004');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '100', '1450424144');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '101', '1450424317');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '102', '1450682231');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '103', '1450683540');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '104', '1450685613');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '105', '1450686249');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '106', '1450763970');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '107', '1450768832');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '108', '1450920324');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '109', '1450920670');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '110', '1450920929');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '111', '1450921478');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '112', '1450921771');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '113', '1450949844');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '114', '1451007178');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '115', '1451007203');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '116', '1452506734');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '22', '1447235071');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '23', '1447293613');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '24', '1447640944');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '25', '1447663454');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '26', '1447663599');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '29', '1447664397');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '31', '1447666133');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '32', '1447666613');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '33', '1447666749');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '34', '1447818877');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '35', '1447819006');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '36', '1447819126');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '37', '1447819248');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '38', '1447821340');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '39', '1447821482');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '40', '1447821578');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '42', '1447842052');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '43', '1447988203');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '44', '1447988463');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '45', '1447988776');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '48', '1448263117');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '49', '1448263719');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '50', '1448415953');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '51', '1448416351');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '52', '1448416604');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '53', '1448419428');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '54', '1448436141');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '55', '1448436262');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '57', '1448436936');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '58', '1448438291');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '60', '1448959008');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '61', '1449019304');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '62', '1449020177');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '63', '1449027937');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '64', '1449484338');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '66', '1449537933');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '67', '1449544078');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '68', '1449545802');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '69', '1449546288');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '70', '1449646777');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '71', '1449650173');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '73', '1449737065');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '75', '1449824391');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '77', '1450167802');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '78', '1450174064');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '80', '1450238468');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '81', '1450245400');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '82', '1450254200');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '83', '1450254981');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '84', '1450255210');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '85', '1450317347');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '87', '1450319554');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '91', '1450408127');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '92', '1450408696');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '93', '1450409016');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '94', '1450409160');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '95', '1450409339');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '96', '1450409510');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '97', '1450409638');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '98', '1450409790');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_teachers', '99', '1450409959');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '12', '1446532514');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '15', '1446532521');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '16', '1446707055');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '21', '1446533979');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '4', '1449712735');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '72', '1449654630');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd', '79', '1450238353');
INSERT INTO `rcoa_auth_assignment` VALUES ('r_wd_leader', '88', '1450335962');

-- ----------------------------
-- Table structure for `rcoa_auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_auth_item`;
CREATE TABLE `rcoa_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `rcoa_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `rcoa_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of rcoa_auth_item
-- ----------------------------
INSERT INTO `rcoa_auth_item` VALUES ('p_new_publish', '2', '平台新闻发布', null, null, '1446452738', '1447221117');
INSERT INTO `rcoa_auth_item` VALUES ('p_rbac_admin', '2', '管理用户或者的权限以及角色分配', null, null, '1448244252', '1448244252');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_admin', '2', '拍摄-管理', null, null, '1446452838', '1447221127');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_appraise', '2', '拍摄-评价', null, null, '1446452825', '1447990895');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_assign', '2', '拍摄-摄影师分派', null, null, '1446452812', '1447221136');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_cancel', '2', '拍摄-取消预约', null, null, '1446452797', '1447221145');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_create', '2', '拍摄-创建预约', null, null, '1446452769', '1447221151');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_index', '2', '拍摄-查看预约', null, null, '1446452755', '1447221171');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_own_appraise', '2', '摄影-接洽人与摄影师评价', 'ShootOwnAppraiseRule', null, '1448246772', '1448263447');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_own_cancel', '2', '拍摄-取消自己创建的预约', 'ShootOwnCancelRule', null, '1446706745', '1450948881');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_own_update', '2', '拍摄-更新自己创建的预约', 'ShootOwnRule', null, '1446706430', '1447221446');
INSERT INTO `rcoa_auth_item` VALUES ('p_shoot_update', '2', '拍摄-更新预约', null, null, '1446452782', '1447221192');
INSERT INTO `rcoa_auth_item` VALUES ('r_admin', '1', '管理员', null, null, '1441858950', '1447220560');
INSERT INTO `rcoa_auth_item` VALUES ('r_contact', '1', '接洽人', null, null, '1446444399', '1447220571');
INSERT INTO `rcoa_auth_item` VALUES ('r_guest', '1', '游客', null, null, '1446444475', '1447220577');
INSERT INTO `rcoa_auth_item` VALUES ('r_mp', '1', '多媒体制作师', null, null, '1446444461', '1447220586');
INSERT INTO `rcoa_auth_item` VALUES ('r_mp_leader', '1', '多媒体制作组长', null, null, '1446444448', '1447220595');
INSERT INTO `rcoa_auth_item` VALUES ('r_new_publisher', '1', '新闻事件管理员', null, null, '1446444340', '1447220602');
INSERT INTO `rcoa_auth_item` VALUES ('r_shoot_leader', '1', '摄影组长', null, null, '1446444420', '1447220610');
INSERT INTO `rcoa_auth_item` VALUES ('r_shoot_man', '1', '摄影师', null, null, '1446444434', '1447220620');
INSERT INTO `rcoa_auth_item` VALUES ('r_teachers', '1', '老师', null, null, '1447232999', '1447232999');
INSERT INTO `rcoa_auth_item` VALUES ('r_wd', '1', '编导', null, null, '1446444383', '1447220631');
INSERT INTO `rcoa_auth_item` VALUES ('r_wd_leader', '1', '编导组长', null, null, '1446444373', '1447220638');

-- ----------------------------
-- Table structure for `rcoa_auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_auth_item_child`;
CREATE TABLE `rcoa_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `rcoa_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rcoa_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of rcoa_auth_item_child
-- ----------------------------
INSERT INTO `rcoa_auth_item_child` VALUES ('r_admin', 'p_rbac_admin');
INSERT INTO `rcoa_auth_item_child` VALUES ('p_shoot_own_appraise', 'p_shoot_appraise');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_shoot_leader', 'p_shoot_assign');
INSERT INTO `rcoa_auth_item_child` VALUES ('p_shoot_own_cancel', 'p_shoot_cancel');
INSERT INTO `rcoa_auth_item_child` VALUES ('p_rbac_admin', 'p_shoot_create');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_wd', 'p_shoot_create');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_shoot_man', 'p_shoot_own_appraise');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_appraise');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_cancel');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_wd', 'p_shoot_own_update');
INSERT INTO `rcoa_auth_item_child` VALUES ('p_shoot_own_update', 'p_shoot_update');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_wd', 'r_contact');
INSERT INTO `rcoa_auth_item_child` VALUES ('r_shoot_leader', 'r_shoot_man');

-- ----------------------------
-- Table structure for `rcoa_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_auth_rule`;
CREATE TABLE `rcoa_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of rcoa_auth_rule
-- ----------------------------
INSERT INTO `rcoa_auth_rule` VALUES ('myRule', 'O:25:\"common\\models\\rbac\\MyRule\":3:{s:4:\"name\";s:6:\"myRule\";s:9:\"createdAt\";i:1446630496;s:9:\"updatedAt\";i:1446630496;}', '1446630496', '1446630496');
INSERT INTO `rcoa_auth_rule` VALUES ('ShootOwnAppraiseRule', 'O:39:\"common\\models\\rbac\\ShootOwnAppraiseRule\":3:{s:4:\"name\";s:20:\"ShootOwnAppraiseRule\";s:9:\"createdAt\";i:1448243519;s:9:\"updatedAt\";i:1448243519;}', '1448243519', '1448243519');
INSERT INTO `rcoa_auth_rule` VALUES ('ShootOwnCancelRule', 'O:37:\"common\\models\\rbac\\ShootOwnCancelRule\":3:{s:4:\"name\";s:18:\"ShootOwnCancelRule\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1448243519;}', '1448243469', '1448243519');
INSERT INTO `rcoa_auth_rule` VALUES ('ShootOwnRule', 'O:31:\"common\\models\\rbac\\ShootOwnRule\":3:{s:4:\"name\";s:12:\"ShootOwnRule\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1448243519;}', '1446693924', '1448243519');

-- ----------------------------
-- Table structure for `rcoa_expert`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_expert`;
CREATE TABLE `rcoa_expert` (
  `u_id` int(11) NOT NULL COMMENT '用户id',
  `type` int(11) DEFAULT NULL COMMENT '专家类型',
  `birth` varchar(64) DEFAULT NULL COMMENT '出生年份',
  `personal_image` varchar(255) DEFAULT NULL COMMENT '个人形象',
  `job_title` varchar(64) DEFAULT '' COMMENT '头衔',
  `job_name` varchar(64) DEFAULT NULL COMMENT '职称',
  `level` varchar(64) DEFAULT NULL COMMENT '级别',
  `employer` varchar(64) DEFAULT NULL COMMENT '单位信息',
  `attainment` text COMMENT '主要成就',
  PRIMARY KEY (`u_id`),
  CONSTRAINT `fk_u_id_id` FOREIGN KEY (`u_id`) REFERENCES `rcoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_expert
-- ----------------------------
INSERT INTO `rcoa_expert` VALUES ('91', '1', '1984', '/filedata/expert/personalImage/jinglaodao.jpg', '高级讲师', '天朝大学政治学院下院长', '1', '北大青华', '1999年获取中国物理十大杰出贡献奖。');
INSERT INTO `rcoa_expert` VALUES ('92', '1', '2005', '/filedata/expert/personalImage/engugu.jpg', '初级教师', '青华大学语言系副教授', '222', '青华大学言文学院', '1、5岁取得山东市文言文大赛一等奖\r\n2、18岁取得全国文言文最年轻成就奖\r\n3、30正常成为青华大学语言系副教授\r\n4、44岁获得文学贝诺儿奖');
INSERT INTO `rcoa_expert` VALUES ('93', '1', '2016', '/filedata/expert/personalImage/engugu.jpg', '顶级讲师', '天朝大学政治学院下院长', '1', '天朝大学', '1、3岁（1985）熟读4书5经\r\n2、5岁（1987）作为军事家在历代兵家也得到了较高的认可\r\n3、在技术发明上亦有灵巧的表现，如改良连弩');
INSERT INTO `rcoa_expert` VALUES ('94', '1', '', '/filedata/expert/personalImage/huangjiyin.jpg', '基因大师', '国家水稻大学转基因学院正院长', '1', '国家水稻大学', '1、抗病、高产杂交水稻冈优多系1号选育及利用，2000年，四川省科技进步三等奖\r\n2、四川省“九五”农作物育种攻关，2000年，四川省农作物育种攻关授予一等奖\r\n3、杂交稻高产抗病新组合冈优501，2000年，四川省科技进步三等奖\r\n4、四川省“八五”农作物育种攻关，1996年，四川省农作物育种攻关授予一等奖\r\n5、中籼杂交稻高产稻产新组合冈优22，1996年，四川省科技进步一等奖');
INSERT INTO `rcoa_expert` VALUES ('95', '2', '2019', '/filedata/expert/personalImage/baizhongshi.jpg', '百家宗师', '辉煌的中国百家宗师', '1', '辉煌的中国”', '1981年TVB版封神榜中，余子明饰演姜子牙；\r\n影视中的姜子牙\r\n影视中的姜子牙 (6张)\r\n1986年台湾华视版封神榜中，陈慧楼饰演姜子牙；\r\n1989年封神榜中，唐远之饰演姜子牙；\r\n1990年封神榜中，蓝天野饰演姜子牙；\r\n1999年莲花童子哪吒中，陈茂林饰演姜子牙；\r\n2001年封神榜中，余子明饰演姜子牙；\r\n2006年封神榜之凤鸣岐山中，刘德凯饰演姜子牙；\r\n2009年封神榜之武王伐纣中，刘德凯饰演姜子牙；\r\n2014年封神英雄榜中，陈键锋饰演姜子牙。\r\n2016年电视剧《封神》：于和伟饰演姜子牙。');
INSERT INTO `rcoa_expert` VALUES ('96', '2', '2011', '/filedata/expert/personalImage/caocao.jpg', '字孟德一名吉利', '三国中曹魏政权的奠基人', '1', '汉末魏王曹魏奠基人', '1、在艺术风格上，曹操诗歌朴实无华、不尚藻饰。\r\n2、曹操在文学上的功绩，还表现在他对建安文学（见建安七子）所起的建设性作用上，建安文学能够在长期战乱、社会残破的背景下得以勃兴，同他的重视和推动是分不开的。\r\n3、此外，曹操还有不少其他文章传世，例如《请追增郭嘉封邑表》、《让县自明本志令》、《与王修书》、《祀故太尉桥玄文》等，文字质朴，感情流露，流畅率真。');
INSERT INTO `rcoa_expert` VALUES ('97', '2', '2016', '/filedata/expert/personalImage/direnjie.jpg', '猜迷专家', '国家专案神机妙算', '1', '国家专案组', '1、《旧唐书·卷八十九·列传第三十九》[44] \r\n2、《新唐书·卷一百一十五·列传第四十》[45] \r\n3、《资治通鉴·卷二百二·唐纪十八》[65] \r\n4、《资治通鉴·卷二百四·唐纪二十》[66] \r\n5、《资治通鉴·卷二百五·唐纪二十一》[67] \r\n6、《资治通鉴·卷二百六·唐纪二十二》[68] \r\n6、《资治通鉴·卷二百七·唐纪二十三》[69] ');
INSERT INTO `rcoa_expert` VALUES ('98', '2', '2014', '/filedata/expert/personalImage/wangyizhi.jpg', '书圣', '东晋时期著名书法家', '1', '东晋时期官员，书法家', '王羲之的《兰亭集序》为历代书法家所敬仰，被誉作“天下第一行书”。王兼善隶、草、楷、行各体，精研体势，心摹手追，广采众长，备精诸体，冶于一炉，摆脱了汉魏笔风，自成一家，影响深远\r\n兰亭序局部\r\n兰亭序局部 (20张)\r\n 。其书法平和自然，笔势委婉含蓄，遒美健秀，世人常用曹植的《洛神赋》中：“翩若惊鸿，婉若游龙，荣曜秋菊，华茂春松。仿佛兮若轻云之蔽月，飘飖兮若流风之回雪。”一句来赞美王羲之的书法之美。传说王羲之小的时候苦练书法，日久，用于清洗毛笔的池塘水都变成墨色。后人评曰：“飘若游云，矫若惊龙”、“龙跳天门，虎卧凰阁”、“天质自然，丰神盖代”。有关于他的成语有入木三分、东床快婿等，王羲之书风最明显特征是用笔细腻，结构多变。\r\n王羲之书法影响了一代又一代的书苑。唐代的欧阳询、虞世南、诸遂良、薛稷、和颜真卿、柳公权，五代的杨凝式，宋代苏轼、黄庭坚、米芾、蔡襄，元代赵孟頫，明代董其昌，这些历代书法名家对王羲之心悦诚服，因而他享有“书圣”美誉。 （图册资料 [1]  [9-12]  ）');
INSERT INTO `rcoa_expert` VALUES ('99', '3', '2010', '/filedata/expert/personalImage/yanzhengqin.jpg', '1111111111', '光禄大夫、守太子太师', '2', '唐代名臣、杰出的书法家。', '李隆基：朕不识颜真卿形状何如，所为得如此！[35] \r\n李萼：闻公义烈，首唱大顺，河朔诸郡恃公为长城。[35] \r\n卢杞：颜真卿四方所信，使谕之，可不劳师旅。[35] \r\n李适：器质天资，公忠杰出，出入四朝，坚贞一志。属贼臣扰乱，委以存谕，拘肋累岁，死而不挠，稽其盛节，实谓犹生。[35] ');
INSERT INTO `rcoa_expert` VALUES ('102', '3', '2018', '/filedata/expert/personalImage/wangjiawei.jpg', '黄色的导演', '香港著名导演', '2', '香港大学电影系副教授', '1、第50届戛纳国际电影节最佳导演 \r\n2、柏林国际电影节评委会主席 \r\n3、戛纳国际电影节评委会主席 \r\n4、上海国际电影节评委会主席 \r\n5、第10届香港电影金像奖最佳导演 \r\n6、第28届台湾电影金马奖最佳导演 \r\n7、第33届香港金像奖最佳导演');
INSERT INTO `rcoa_expert` VALUES ('108', '3', '2000', '/filedata/expert/personalImage/zuojielun.jpg', 'raw神', '台湾新北市华语男歌手', '1', '台湾新北市', '2015    第26届金曲奖    最佳国语专辑奖    哎哟，不错哦    （提名）    \r\n▪ 2015    第26届金曲奖    最佳专辑制作人奖    哎哟，不错哦    （提名）    \r\n▪ 2013    第24届金曲奖    最佳国语专辑奖    十二新作    （提名）    \r\n▪ 2013    第24届金曲奖    最佳专辑制作人奖    十二新作    （提名）    \r\n▪ 2013    第24届金曲奖    最佳国语男歌手    十二新作    （提名）    \r\n▪ 2012    第23届金曲奖    最佳国语男歌手    惊叹号    （提名）    \r\n▪ 2012    第23届金曲奖    最佳编曲人奖    水手怕水    （提名）    \r\n▪ 2011    第22届金曲奖    最佳专辑制作人奖    跨时代    （提名）    \r\n▪ 2011    第22届金曲奖    最佳年度歌曲    超人不会飞    （提名）    \r\n▪ 2011    第22届金曲奖    最佳作曲人奖    烟花易冷    （提名）    \r\n▪ 2011    第22届金曲奖    最佳国语专辑奖    跨时代[100]     （获奖）    \r\n▪ 2011    第22届金曲奖    最佳国语男歌手奖    跨时代[100]     （获奖）    \r\n▪ 2009    第20届金曲奖    最佳作曲人奖    稻香    （提名）    \r\n▪ 2009    第20届金曲奖    最佳专辑制作人奖    魔杰座    （提名）    \r\n▪ 2009    第20届金曲奖    最佳作词人奖    稻香    （提名）    \r\n▪ 2009    第20届金曲奖    最佳国语专辑奖    魔杰座    （提名）    \r\n▪ 2009    第20届金曲奖    最佳年度歌曲奖    稻香[101]     （获奖）    \r\n▪ 2009    第20届金曲奖    最佳音乐录影带奖    魔术先生[101]     （获奖）    \r\n▪ 2009    第20届金曲奖    最佳国语男歌手奖    魔杰座[101]     （获奖）    \r\n▪ 2008    第19届金曲奖    最佳国语专辑奖    我很忙    （提名）    \r\n▪ 2008    第19届金曲奖    演奏类最佳专辑制作人奖    不能说的秘密电影原声带[102]     （获奖）    \r\n▪ 2008    第19届金曲奖    演奏类最佳专辑奖    不能说的秘密电影原声带    （提名）    \r\n▪ 2008    第19届金曲奖    演奏类最佳作曲人奖    琴房[102]     （获奖）    \r\n▪ 2008    第19届金曲奖    最佳年度歌曲奖    青花瓷[102]     （获奖）    \r\n▪ 2008    第19届金曲奖    最佳作曲人奖    青花瓷[102]     （获奖）    \r\n▪ 2007    第18届金曲奖    最佳单曲制作人奖    霍元甲[103]     （获奖）    \r\n▪ 2007    第18届金曲奖    最佳音乐录影带导演奖    红模仿    （提名）    \r\n▪ 2007    第18届金曲奖    最佳年度歌曲奖    千里之外    （提名）    \r\n▪ 2005    第16届金曲奖    最佳作曲人奖    七里香    （提名）    \r\n▪ 2005    第16届金曲奖    最佳流行音乐演唱专辑奖    七里香    （提名）    \r\n▪ 2005    第16届金曲奖    最佳国语男演唱人奖    七里香    （提名）    \r\n▪ 2004    第15届金曲奖    最佳作词人奖    梯田    （提名）    \r\n▪ 2004    第15届金曲奖    最佳作曲人奖    东风破    （提名）    \r\n▪ 2004    第15届金曲奖    最佳国语男演唱人奖    叶惠美    （提名）    \r\n▪ 2004    第15届金曲奖    最佳专辑制作人奖    叶惠美    （提名）    \r\n▪ 2004    第15届金曲奖    最佳流行音乐演唱专辑奖    叶惠美[104]     （获奖）    \r\n▪ 2003    第14届金曲奖    最佳流行音乐演唱专辑奖    八度空间    （提名）    \r\n▪ 2003    第14届金曲奖    最佳专辑制作人奖    八度空间    （提名）    \r\n▪ 2002    第13届金曲奖    最佳作曲人奖    爱在西元前[105]     （获奖）    \r\n▪ 2002    第13届金曲奖    最佳国语男演唱人奖    范特西    （提名）    \r\n▪ 2002    第13届金曲奖    最佳流行音乐演唱专辑奖    范特西[105]     （获奖）    \r\n▪ 2002    第13届金曲奖    最佳专辑制作人奖    范特西[105]     （获奖）    \r\n▪ 2001    第12届金曲奖    最佳专辑制作人奖    JAY    （提名）    \r\n▪ 2001    第12届金曲奖    最佳流行音乐演唱专辑奖    JAY[106]     （获奖）    \r\n▪ 2001    第12届金曲奖    最佳作曲人奖    可爱女人    （提名）    \r\n▪ 2001    第12届金曲奖    最佳新人奖    JAY    （提名）    ');
INSERT INTO `rcoa_expert` VALUES ('109', '3', '1980', '/filedata/expert/personalImage/zouxingchi.jpg', '赌圣', '为丽的电视特约演员', '1', '香港，祖籍浙江宁波', ' 2014    第33届香港电影金像奖最佳影片    西游·降魔篇    （提名）    \r\n▪ 2013    第50届台湾电影金马奖最佳改编剧本    西游·降魔篇    （提名）    \r\n▪ 2013    第50届台湾电影金马奖最佳动作设计    西游·降魔篇    （提名）    \r\n▪ 2009    第28届香港电影金像奖最佳男配角    长江7号    （提名）    \r\n▪ 2009    第28届香港电影金像奖最佳影片    长江7号    （提名）    \r\n▪ 2008    第29届大众电影百花奖最佳导演    长江7号    （提名）    \r\n▪ 2008    第15届北京大学生电影节最佳导演    长江七号    （提名）    \r\n▪ 2008    瑞士纳沙泰尔国际奇幻电影节Audience Award    长江7号    （获奖）    \r\n▪ 2006    第59届英国电影学院奖最佳外语片    功夫    （提名）    \r\n▪ 2006    第28届大众电影百花奖最佳影片    功夫    （提名）    \r\n▪ 2006    第11届美国评论家选择奖最佳外语片    功夫    （获奖）    \r\n▪ 2006    第28届大众电影百花奖最佳导演    功夫    （提名）    \r\n▪ 2006    第28届大众电影百花奖最佳男主角    功夫    （提名）    \r\n▪ 2006    第01届亚洲卓越奖杰出电影表演者    功夫    （获奖）    \r\n▪ 2006    第63届美国电影电视金球奖最佳外语片     功夫    （提名）    \r\n▪ 2006    第01届亚洲卓越奖杰出电影    功夫    （获奖）    \r\n▪ 2005    第4届犹他影评人协会奖最佳外语片    功夫    （获奖）    \r\n▪ 2005    第24届香港电影金像奖最佳导演    功夫    （提名）    \r\n▪ 2005    第42届台湾电影金马奖最佳剧情片    功夫    （获奖）    \r\n▪ 2005    第14届东南影评人协会奖最佳外语片第二名    功夫    （获奖）    ');
INSERT INTO `rcoa_expert` VALUES ('110', '4', '1970', '/filedata/expert/personalImage/zourenfa.jpg', '赌神', '中国影视演员、摄影家，国家一级演员', '1', '中国影视万世流芳', '▪ 2012    第15届上海国际电影节华语电影杰出贡献奖    （获奖）    \r\n▪ 2011    第14届中国电影华表奖优秀境外华裔男演员奖    《孔子》    （获奖）    \r\n▪ 2007    AZN亚洲卓越大奖终身成就奖    （获奖）    \r\n▪ 2005    第14届金鸡百花电影节中国电影百年百位优秀演员    （获奖）    \r\n▪ 2005    香港影视娱乐博览会最难忘电影角色奖    小马哥    （获奖）    \r\n▪ 2001    第2届美国亚裔艾美奖最佳男主角    《卧虎藏龙》    （获奖）    \r\n▪ 2000    第2届法国多维尔亚洲电影节终身成就奖    （获奖）    \r\n▪ 2000    第1届美国亚裔艾美奖最佳男主角    《安娜与国王》    （获奖）    \r\n▪ 1999    第5届百视达娱乐大奖最受欢迎本地艺人大奖    《再战边缘》    （获奖）    \r\n▪ 1997    新加坡亚太电影博览会跨越年代成就奖    （获奖）    \r\n▪ 1995    第1届美国亚裔艺术基金会“金环奖”最佳男主角    （获奖）    \r\n▪ 1993    《洛杉矶时报》  世上最酷的演员    （获奖）    \r\n▪ 1992    壹周刊电视大奖第一位    （获奖）    ');
INSERT INTO `rcoa_expert` VALUES ('111', '4', '', '/filedata/expert/personalImage/tangren.jpg', '江南四大才子之首', '居士、桃花庵主、鲁国唐生、逃禅仙吏等，明代画家、书法家、诗人', '1', '大唐', '唐寅的作品有《骑驴思归图》、《山路松声图》、《事茗图》、《王蜀宫妓图》、《李端端落籍图》、《秋风纨扇图》、《枯槎鸜鹆图》等绘画作品，藏于世界各大博物馆。');
INSERT INTO `rcoa_expert` VALUES ('114', '5', '2018', '/filedata/expert/personalImage/jincancan.jpg', '江南四大才子之首', '居士、桃花庵主、鲁国唐生、逃禅仙吏等，明代画家、书法家、诗人', '12', '中国影视万世流芳', '唐寅的作品有《骑驴思归图》、《山路松声图》、《事茗图》、《王蜀宫妓图》、《李端端落籍图》、《秋风纨扇图》、《枯槎鸜鹆图》等绘画作品，藏于世界各大博物馆。');
INSERT INTO `rcoa_expert` VALUES ('115', '5', '2018', '/filedata/expert/personalImage/jincancan.jpg', '江南四大才子之首', '居士、桃花庵主、鲁国唐生、逃禅仙吏等，明代画家、书法家、诗人', '12', '中国影视万世流芳', '唐寅的作品有《骑驴思归图》、《山路松声图》、《事茗图》、《王蜀宫妓图》、《李端端落籍图》、《秋风纨扇图》、《枯槎鸜鹆图》等绘画作品，藏于世界各大博物馆。');
INSERT INTO `rcoa_expert` VALUES ('116', '6', '1979', '/filedata/expert/personalImage/jincancan.jpg', '高级讲师', '青华大学语言系副教授', '1', '青华大学言文学院', '1999年获取中国物理十大杰出贡献奖。');

-- ----------------------------
-- Table structure for `rcoa_expert_project`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_expert_project`;
CREATE TABLE `rcoa_expert_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expert_id` int(11) NOT NULL COMMENT '专家id',
  `project_id` int(11) NOT NULL COMMENT '项目id',
  `start_time` char(11) NOT NULL COMMENT '开始时间',
  `end_time` char(11) DEFAULT NULL COMMENT '结束时间',
  `cost` int(11) DEFAULT '0' COMMENT '项目费用',
  `compatibility` int(2) DEFAULT '1' COMMENT '合作融洽度',
  PRIMARY KEY (`id`),
  KEY `fk_expert_id_id` (`expert_id`),
  CONSTRAINT `fk_expert_id_id` FOREIGN KEY (`expert_id`) REFERENCES `rcoa_expert` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_expert_project
-- ----------------------------
INSERT INTO `rcoa_expert_project` VALUES ('19', '91', '8', '2015/12/04', '', '2147483647', '1');
INSERT INTO `rcoa_expert_project` VALUES ('21', '91', '10', '2015/12/18', '2015/12/26', '366565', '1');
INSERT INTO `rcoa_expert_project` VALUES ('22', '108', '9', '2015/12/03', '', '101000011', '1');
INSERT INTO `rcoa_expert_project` VALUES ('23', '108', '12', '2015/12/16', '2015/12/31', '2147483647', '1');
INSERT INTO `rcoa_expert_project` VALUES ('24', '111', '12', '2015/12/06', '', '110000000', '1');
INSERT INTO `rcoa_expert_project` VALUES ('25', '91', '13', '2015/12/24', '', '7878878', '1');
INSERT INTO `rcoa_expert_project` VALUES ('26', '91', '36', '2015/12/01', '2015/12/18', '799999', '1');
INSERT INTO `rcoa_expert_project` VALUES ('27', '91', '13', '2015/12/27', '', '8090', '1');
INSERT INTO `rcoa_expert_project` VALUES ('28', '91', '34', '2015/12/27', '', '898898989', '1');
INSERT INTO `rcoa_expert_project` VALUES ('29', '91', '9', '2015/12/11', '', '0', '1');
INSERT INTO `rcoa_expert_project` VALUES ('30', '116', '9', '2016/01/16', '', '12455', '1');

-- ----------------------------
-- Table structure for `rcoa_expert_type`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_expert_type`;
CREATE TABLE `rcoa_expert_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '类型名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_expert_type
-- ----------------------------
INSERT INTO `rcoa_expert_type` VALUES ('1', '金牌讲师', '');
INSERT INTO `rcoa_expert_type` VALUES ('2', '专家讲师', '');
INSERT INTO `rcoa_expert_type` VALUES ('3', '无名讲师', '');
INSERT INTO `rcoa_expert_type` VALUES ('4', '国宝讲师', '');
INSERT INTO `rcoa_expert_type` VALUES ('5', '殿堂级讲师', '');
INSERT INTO `rcoa_expert_type` VALUES ('6', '初级讲师', '');

-- ----------------------------
-- Table structure for `rcoa_framework_item`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_framework_item`;
CREATE TABLE `rcoa_framework_item` (
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
  CONSTRAINT `fk_project_parent` FOREIGN KEY (`parent_id`) REFERENCES `rcoa_framework_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_framework_item
-- ----------------------------
INSERT INTO `rcoa_framework_item` VALUES ('2', '第一个学院', '无2', '1', null, '1453191553', null);
INSERT INTO `rcoa_framework_item` VALUES ('4', '第二大学院', '无gggggggggggggg', '1', null, '1443599491', null);
INSERT INTO `rcoa_framework_item` VALUES ('5', '青少年学院', '青少年学院', '1', null, null, null);
INSERT INTO `rcoa_framework_item` VALUES ('6', '成人继续教育学院', '成人继续教xxxx育学院', '1', null, null, null);
INSERT INTO `rcoa_framework_item` VALUES ('7', '中山大学中国国', '嘎啦在工顶戴要苛奔 共有点有人人遥仍抮失遥傜仍抮', '1', null, null, null);
INSERT INTO `rcoa_framework_item` VALUES ('8', '国家开放大学（广州实验）学院-2015年春季课程建设', '国家开放大学（广州实验）学院-2015年春季课程建设国家开放大学（广州实验）学院-2015年春季课程建设', '2', '1443603232', '1443603232', '2');
INSERT INTO `rcoa_framework_item` VALUES ('9', '广州电大教学支持服务项目资源建设', '广州电大教学支持服务项目资源建设', '2', '1443603332', '1443603332', '2');
INSERT INTO `rcoa_framework_item` VALUES ('10', '广东外语外贸大学学历教育', '', '2', '1443603344', '1443603344', '4');
INSERT INTO `rcoa_framework_item` VALUES ('11', '员工关系管理', '', '2', '1443603357', '1447291929', '6');
INSERT INTO `rcoa_framework_item` VALUES ('12', '企业培训师认证培训', '', '2', '1443603369', '1443603369', '6');
INSERT INTO `rcoa_framework_item` VALUES ('13', '心理咨询师认证培训', '', '2', '1443603381', '1446176408', '6');
INSERT INTO `rcoa_framework_item` VALUES ('14', '企业现场管理专项能力认证培训', '', '2', '1443603392', '1443603392', '8');
INSERT INTO `rcoa_framework_item` VALUES ('15', '珠海月苗学院', '3', '1', '1446101929', '1446101929', null);
INSERT INTO `rcoa_framework_item` VALUES ('16', 'E国际研究项目', 'eeeee', '2', '1446109051', '1446176446', '15');
INSERT INTO `rcoa_framework_item` VALUES ('17', '斗门与奥门10年经济发展项目', '经济发展', '2', '1446109126', '1446109126', '15');
INSERT INTO `rcoa_framework_item` VALUES ('18', '围棋青少年培训', '', '2', '1446109189', '1446109189', '5');
INSERT INTO `rcoa_framework_item` VALUES ('19', '吉它青少年培训项目', '', '2', '1446109220', '1446109220', '5');
INSERT INTO `rcoa_framework_item` VALUES ('22', '一个不能为空的项目', '', '2', '1446169871', '1446169871', null);
INSERT INTO `rcoa_framework_item` VALUES ('23', '提交后中转到项目', '', '2', '1446170183', '1446170183', null);
INSERT INTO `rcoa_framework_item` VALUES ('34', '奥门跨海项目', '', '2', '1446172477', '1446172477', null);
INSERT INTO `rcoa_framework_item` VALUES ('35', '吴川学院', '', '1', '1446172647', '1446172647', null);
INSERT INTO `rcoa_framework_item` VALUES ('36', '吴川一中', '', '2', '1446172679', '1446172679', '35');
INSERT INTO `rcoa_framework_item` VALUES ('37', '考研数学1年级初级班', '', '3', '1446175574', '1446175574', '8');
INSERT INTO `rcoa_framework_item` VALUES ('38', '心理援助计划', '', '3', '1446176541', '1447138679', '13');
INSERT INTO `rcoa_framework_item` VALUES ('39', '考研物理初始班', '', '3', '1446176596', '1446176596', '8');
INSERT INTO `rcoa_framework_item` VALUES ('40', '考研化学中级班', '', '3', '1446176609', '1446176609', '8');
INSERT INTO `rcoa_framework_item` VALUES ('41', '考研英语高级班', '', '3', '1446176623', '1446176623', '8');
INSERT INTO `rcoa_framework_item` VALUES ('42', '外语外贸4级', '', '3', '1446176655', '1446176655', '10');
INSERT INTO `rcoa_framework_item` VALUES ('43', '外语6级应该', '', '3', '1446184879', '1446184879', '10');
INSERT INTO `rcoa_framework_item` VALUES ('44', '外语8级', '', '3', '1446184908', '1446184908', '10');
INSERT INTO `rcoa_framework_item` VALUES ('45', '变态心理学', '', '3', '1446184945', '1447138691', '13');
INSERT INTO `rcoa_framework_item` VALUES ('46', '老板与员工工作斗争', '', '3', '1446184965', '1448863892', '11');
INSERT INTO `rcoa_framework_item` VALUES ('47', '国企面试技考', '', '3', '1446185003', '1446185003', '12');
INSERT INTO `rcoa_framework_item` VALUES ('48', '民企偷懒法宝', '', '3', '1446185044', '1446185044', '12');
INSERT INTO `rcoa_framework_item` VALUES ('51', '老板变态学与员工心理斗争-员工反击', '特长课程名', '3', '1448958565', '1448958565', '11');
INSERT INTO `rcoa_framework_item` VALUES ('52', '国有企业生存法则之360计-走为上计-空城计-无用计', '特笔芯', '3', '1448958665', '1448958665', '12');

-- ----------------------------
-- Table structure for `rcoa_framework_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_framework_item_child`;
CREATE TABLE `rcoa_framework_item_child` (
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`) USING BTREE,
  CONSTRAINT `fk_project_item_child_id` FOREIGN KEY (`child`) REFERENCES `rcoa_framework_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_project_item_id` FOREIGN KEY (`parent`) REFERENCES `rcoa_framework_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_framework_item_child
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_job`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_job`;
CREATE TABLE `rcoa_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '信息id',
  `system_id` int(11) NOT NULL COMMENT '任务系统',
  `relate_id` int(11) NOT NULL COMMENT '关联id',
  `subject` varchar(255) DEFAULT '无' COMMENT '主题',
  `content` text COMMENT '信息内容',
  `link` varchar(255) DEFAULT '""' COMMENT '任务超联接',
  `progress` int(3) DEFAULT '0' COMMENT '进度',
  `status` varchar(64) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `fk_system` (`system_id`),
  CONSTRAINT `fk_system` FOREIGN KEY (`system_id`) REFERENCES `rcoa_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_job
-- ----------------------------
INSERT INTO `rcoa_job` VALUES ('3', '1', '11', '第一个任务', '', 'http://www.wskeee.com', '10', '待指派');

-- ----------------------------
-- Table structure for `rcoa_job_notification`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_job_notification`;
CREATE TABLE `rcoa_job_notification` (
  `job_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` int(11) NOT NULL COMMENT '用户id',
  `status` int(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`job_id`,`u_id`),
  KEY `fk_user` (`u_id`),
  CONSTRAINT `fk_job` FOREIGN KEY (`job_id`) REFERENCES `rcoa_job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`u_id`) REFERENCES `rcoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_job_notification
-- ----------------------------
INSERT INTO `rcoa_job_notification` VALUES ('3', '3', '0');
INSERT INTO `rcoa_job_notification` VALUES ('3', '4', '0');
INSERT INTO `rcoa_job_notification` VALUES ('3', '5', '0');
INSERT INTO `rcoa_job_notification` VALUES ('3', '6', '0');

-- ----------------------------
-- Table structure for `rcoa_migration`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_migration`;
CREATE TABLE `rcoa_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_migration
-- ----------------------------
INSERT INTO `rcoa_migration` VALUES ('m000000_000000_base', '1441511973');
INSERT INTO `rcoa_migration` VALUES ('m130524_201442_init', '1441511978');
INSERT INTO `rcoa_migration` VALUES ('m140506_102106_rbac_init', '1441769187');

-- ----------------------------
-- Table structure for `rcoa_question`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_question`;
CREATE TABLE `rcoa_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '题目类型',
  `title` varchar(255) DEFAULT NULL COMMENT '题目标题',
  `des` varchar(255) DEFAULT NULL COMMENT '题目描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_question
-- ----------------------------
INSERT INTO `rcoa_question` VALUES ('1', '1', '拍摄场地与设备准备效率', '摄影——摄影师');
INSERT INTO `rcoa_question` VALUES ('2', '1', '拍摄的工作效率', '摄影——摄影师');
INSERT INTO `rcoa_question` VALUES ('3', '1', '拍摄效果', '摄影——摄影师');
INSERT INTO `rcoa_question` VALUES ('4', '1', '老师到场的效率', '摄影——编导评');
INSERT INTO `rcoa_question` VALUES ('5', '1', '现场编导的工作效率', '摄影——编导');
INSERT INTO `rcoa_question` VALUES ('6', '1', '授课痕迹遗留', '摄影——摄影师对编导评价题目');

-- ----------------------------
-- Table structure for `rcoa_question_op`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_question_op`;
CREATE TABLE `rcoa_question_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL COMMENT '题目id',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '选项类型',
  `title` varchar(255) DEFAULT NULL COMMENT '选项标题',
  `value` varchar(255) DEFAULT NULL COMMENT '选项值',
  PRIMARY KEY (`id`),
  KEY `pk_q_id` (`question_id`),
  CONSTRAINT `pk_q_id` FOREIGN KEY (`question_id`) REFERENCES `rcoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_question_op
-- ----------------------------
INSERT INTO `rcoa_question_op` VALUES ('1', '1', '1', '准时提供拍摄场地与设备', '3');
INSERT INTO `rcoa_question_op` VALUES ('3', '1', '1', '延迟提供拍摄场地与设备，半小时以内', '2');
INSERT INTO `rcoa_question_op` VALUES ('4', '1', '1', '延迟提供拍摄场地与设备，半小时以上', '0');
INSERT INTO `rcoa_question_op` VALUES ('8', '2', '1', '在拍摄过程中设备运行正常，并且指导老师高效完成拍摄工作', '3');
INSERT INTO `rcoa_question_op` VALUES ('9', '2', '1', '在拍摄过程中场地和设备出现小状态，但不影响正常拍摄', '2');
INSERT INTO `rcoa_question_op` VALUES ('10', '2', '1', '由于主观失误导致场地和设备出现各种状况，严重影响拍摄工作', '0');
INSERT INTO `rcoa_question_op` VALUES ('14', '6', '1', '将教师授课痕迹带走，包括实物如：纸巾、茶杯等。虚拟物如：授课讲稿。做到了零污染', '3');
INSERT INTO `rcoa_question_op` VALUES ('15', '6', '1', '实物痕迹干净，虚拟课件没有清理', '2');
INSERT INTO `rcoa_question_op` VALUES ('16', '6', '1', '授课完毕第一时间离开，所带教师现场痕迹无收拾', '0');
INSERT INTO `rcoa_question_op` VALUES ('17', '5', '1', '在拍摄过程中设备运行正常，并且指导老师高效完成拍摄工作', '3');
INSERT INTO `rcoa_question_op` VALUES ('18', '5', '1', '拍摄资准备不够充分，对老师的指引性有待提升', '2');
INSERT INTO `rcoa_question_op` VALUES ('19', '5', '1', '拍摄资料缺漏较多，对教师指引不够导致视频拍摄效率低', '0');
INSERT INTO `rcoa_question_op` VALUES ('20', '3', '1', '画面美观高清，无斑纹，无曝光，画面节奏紧凑，切换自然', '3');
INSERT INTO `rcoa_question_op` VALUES ('21', '3', '1', '画面有稍微瑕疵，不影响整体质量', '2');
INSERT INTO `rcoa_question_op` VALUES ('22', '3', '1', '拍摄效果不合格，需要补拍或重拍', '0');
INSERT INTO `rcoa_question_op` VALUES ('23', '4', '1', '老师准时到场', '3');
INSERT INTO `rcoa_question_op` VALUES ('24', '4', '1', '老师延迟到场半小时以内', '2');
INSERT INTO `rcoa_question_op` VALUES ('25', '4', '1', '老师延迟到场，半小时以上', '0');

-- ----------------------------
-- Table structure for `rcoa_shoot_appraise`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_appraise`;
CREATE TABLE `rcoa_shoot_appraise` (
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `role_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `value` tinyint(3) NOT NULL DEFAULT '3' COMMENT '分值',
  `index` tinyint(3) DEFAULT NULL COMMENT '索引',
  PRIMARY KEY (`b_id`,`role_name`,`q_id`),
  KEY `pk_question_id` (`q_id`),
  KEY `pk_role_id` (`role_name`),
  CONSTRAINT `pk_bookdetail_id` FOREIGN KEY (`b_id`) REFERENCES `rcoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_question_id` FOREIGN KEY (`q_id`) REFERENCES `rcoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_role_id` FOREIGN KEY (`role_name`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_appraise
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_shoot_appraise_result`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_appraise_result`;
CREATE TABLE `rcoa_shoot_appraise_result` (
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` int(11) NOT NULL COMMENT '用户id',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `role_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '得分',
  `data` varchar(255) DEFAULT NULL COMMENT '数据详细',
  PRIMARY KEY (`b_id`,`u_id`,`q_id`,`role_name`),
  KEY `pk_u_id` (`u_id`) USING BTREE,
  KEY `pk_q_id_pk` (`q_id`),
  KEY `pk_role_name` (`role_name`),
  CONSTRAINT `pk_b_id` FOREIGN KEY (`b_id`) REFERENCES `rcoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_q_id_pk` FOREIGN KEY (`q_id`) REFERENCES `rcoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_role_name` FOREIGN KEY (`role_name`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_u_id` FOREIGN KEY (`u_id`) REFERENCES `rcoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_appraise_result
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_shoot_appraise_template`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_appraise_template`;
CREATE TABLE `rcoa_shoot_appraise_template` (
  `role_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色',
  `q_id` int(11) NOT NULL COMMENT '题目id',
  `value` tinyint(3) DEFAULT '3' COMMENT '得分',
  `index` tinyint(3) DEFAULT '-1' COMMENT '索引',
  PRIMARY KEY (`role_name`,`q_id`),
  KEY `pk_qid` (`q_id`),
  CONSTRAINT `pk_qid` FOREIGN KEY (`q_id`) REFERENCES `rcoa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_role` FOREIGN KEY (`role_name`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_appraise_template
-- ----------------------------
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_contact', '4', '3', null);
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_contact', '5', '3', null);
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_contact', '6', '3', null);
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_shoot_man', '1', '3', null);
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_shoot_man', '2', '3', null);
INSERT INTO `rcoa_shoot_appraise_template` VALUES ('r_shoot_man', '3', '3', '-1');

-- ----------------------------
-- Table structure for `rcoa_shoot_bookdetail`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_bookdetail`;
CREATE TABLE `rcoa_shoot_bookdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(4) DEFAULT NULL COMMENT '场景id',
  `fw_college` varchar(32) DEFAULT NULL COMMENT 'rms数据库: 项目',
  `fw_project` varchar(32) DEFAULT NULL COMMENT 'rms数据库: 子项目',
  `fw_course` varchar(32) DEFAULT NULL COMMENT 'rms数据库: 课程',
  `lession_time` tinyint(2) DEFAULT '1' COMMENT '课时',
  `u_teacher` int(11) DEFAULT NULL COMMENT '老师',
  `u_contacter` int(11) DEFAULT NULL COMMENT '接洽人',
  `u_booker` int(11) DEFAULT NULL COMMENT '预约人',
  `u_shoot_man` int(11) DEFAULT NULL COMMENT '摄影师',
  `book_time` int(11) DEFAULT NULL COMMENT '约定时间 ',
  `index` tinyint(2) DEFAULT NULL COMMENT '顺序',
  `shoot_mode` tinyint(2) DEFAULT '1' COMMENT '拍摄模式',
  `photograph` tinyint(1) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '状态',
  `create_by` int(11) DEFAULT NULL COMMENT '任务创建者',
  `created_at` int(11) DEFAULT NULL COMMENT '创建于',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新于',
  `ver` int(11) DEFAULT '0' COMMENT '乐观锁',
  `remark` char(100) DEFAULT '无' COMMENT '备注',
  `start_time` char(20) DEFAULT NULL COMMENT '开始时间',
  PRIMARY KEY (`id`),
  KEY `fk_college` (`fw_college`),
  KEY `fk_project` (`fw_project`),
  KEY `fk_course` (`fw_course`),
  KEY `fk_contacter` (`u_contacter`),
  KEY `fk_booker` (`u_booker`),
  KEY `fk_site_id` (`site_id`),
  KEY `fk_creater` (`create_by`),
  KEY `fk_teacher_id` (`u_teacher`),
  KEY `u_shoot_man` (`u_shoot_man`),
  CONSTRAINT `fk_project` FOREIGN KEY (`fw_project`) REFERENCES `rms`.`rms_project_sys_data` (`PROJECT_SYS_DATA_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_booker` FOREIGN KEY (`u_booker`) REFERENCES `rcoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_college` FOREIGN KEY (`fw_college`) REFERENCES `rms`.`rms_project_sys_data` (`PROJECT_SYS_DATA_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_contacter` FOREIGN KEY (`u_contacter`) REFERENCES `rcoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_course` FOREIGN KEY (`fw_course`) REFERENCES `rms`.`rms_project_sys_data` (`PROJECT_SYS_DATA_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_creater` FOREIGN KEY (`create_by`) REFERENCES `rcoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_shoot_man` FOREIGN KEY (`u_shoot_man`) REFERENCES `rcoa_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_site_id` FOREIGN KEY (`site_id`) REFERENCES `rcoa_shoot_site` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_id` FOREIGN KEY (`u_teacher`) REFERENCES `rcoa_expert` (`u_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=793 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_bookdetail
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_shoot_bookdetail_role_name`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_bookdetail_role_name`;
CREATE TABLE `rcoa_shoot_bookdetail_role_name` (
  `b_id` int(11) NOT NULL COMMENT '拍摄任务ID',
  `u_id` int(11) NOT NULL COMMENT '用户角色ID',
  `role_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色',
  `primary_foreign` int(2) DEFAULT '1' COMMENT '主从',
  PRIMARY KEY (`b_id`,`u_id`,`role_name`),
  KEY `shoot_man` (`u_id`),
  KEY `fk_role_name` (`role_name`),
  CONSTRAINT `fk_bid` FOREIGN KEY (`b_id`) REFERENCES `rcoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_role_name` FOREIGN KEY (`role_name`) REFERENCES `rcoa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uid` FOREIGN KEY (`u_id`) REFERENCES `rcoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_bookdetail_role_name
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_shoot_history`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_history`;
CREATE TABLE `rcoa_shoot_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `b_id` int(11) NOT NULL COMMENT '任务id',
  `u_id` int(11) NOT NULL COMMENT '用户id',
  `type` tinyint(2) DEFAULT '1' COMMENT '操作类型',
  `history` varchar(500) DEFAULT NULL COMMENT '历史记录',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  KEY `fk_b_id` (`b_id`),
  KEY `fk_u_id` (`u_id`),
  CONSTRAINT `fk_b_id` FOREIGN KEY (`b_id`) REFERENCES `rcoa_shoot_bookdetail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_u_id` FOREIGN KEY (`u_id`) REFERENCES `rcoa_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_history
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_shoot_site`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_site`;
CREATE TABLE `rcoa_shoot_site` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '场景名称',
  `traffic` varchar(255) DEFAULT NULL COMMENT '交通、坐车指引',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_site
-- ----------------------------
INSERT INTO `rcoa_shoot_site` VALUES ('1', '7-1摄', '越秀区麓景西路41号广州广播电视大学7号楼一楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '7号楼一楼，蓝箱、访真场景拍摄');
INSERT INTO `rcoa_shoot_site` VALUES ('2', '7-1音', '越秀区麓景西路41号广州广播电视大学7号楼一楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '7号楼一楼，声音录制，隔音效果超好');
INSERT INTO `rcoa_shoot_site` VALUES ('3', '6-5摄', '越秀区麓景西路41号广州广播电视大学6号楼五楼 公交车：76 、76A 、76A快线 、93 、190 、544 、546  、 547', '6号楼五楼，标高清拍摄');

-- ----------------------------
-- Table structure for `rcoa_shoot_site_rule`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_shoot_site_rule`;
CREATE TABLE `rcoa_shoot_site_rule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '规则名',
  `type` tinyint(2) NOT NULL COMMENT '规则类型',
  `site` int(4) DEFAULT NULL COMMENT '关联场地',
  `start_time` int(11) DEFAULT NULL COMMENT '起始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `des` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`site`),
  CONSTRAINT `fk_site` FOREIGN KEY (`site`) REFERENCES `rcoa_shoot_site` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_shoot_site_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `rcoa_system`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_system`;
CREATE TABLE `rcoa_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块id',
  `name` varchar(64) DEFAULT NULL COMMENT '模块名',
  `module_image` varchar(255) DEFAULT NULL COMMENT '模块图片',
  `module_link` varchar(255) DEFAULT '#' COMMENT '描述',
  `des` varchar(255) DEFAULT NULL COMMENT '模块链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rcoa_system
-- ----------------------------
INSERT INTO `rcoa_system` VALUES ('1', '拍摄', '/filedata/avatars/u136.png', '/shoot/bookdetail', '专门提供编导与摄影师拍摄任务预约工作');
INSERT INTO `rcoa_system` VALUES ('2', '专家库', '/filedata/expert/personalImage/u183.jpg', '/expert/default', '专门提供专家库信息');
INSERT INTO `rcoa_system` VALUES ('3', '多媒体制作', '/filedata/avatars/u132.png', '#', '专门提供多媒体制作');

-- ----------------------------
-- Table structure for `rcoa_user`
-- ----------------------------
DROP TABLE IF EXISTS `rcoa_user`;
CREATE TABLE `rcoa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '密码重置',
  `sex` tinyint(2) DEFAULT '1' COMMENT '性别',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电子邮件',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `nickname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '头像',
  `ee` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ee号',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机',
  `created_at` int(11) NOT NULL COMMENT '创建于',
  `updated_at` int(11) NOT NULL COMMENT '更新于',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of rcoa_user
-- ----------------------------
INSERT INTO `rcoa_user` VALUES ('3', 'cy', '8hb7ziPfVr4a12X92RKVwIhpJXWYSeJP', '$2y$13$4sZVoduO5UYMDlqv3oANuO0M1gyq/CeXycZZ4ewRVA4VN17pAkBui', null, '1', 'cy@163.com', '10', '陈艳', '', null, null, '1441614873', '1446429761');
INSERT INTO `rcoa_user` VALUES ('4', 'wb', 'BusRaEvek1p930gnd2m6osMOMJvnrEj0', '$2y$13$XPTY6xZ6eZtiIQyJFijrpO9Q3JQXElDqqFCzzdCtsHnGWIJPRv0ny', null, '1', 'wb@163.com', '10', '魏彬', '', '', '', '1441614900', '1449654963');
INSERT INTO `rcoa_user` VALUES ('5', 'cdd', '8TSmrqT76q8tAxPW1WvS2m8zoxhHqtTj', '$2y$13$6Tj8SaXuwbS70Ndpbx.8qu06.wXQAfl1NDROQMm2BjuDaFDj55Hza', null, '1', 'cdd@163.com', '10', '陈丹丹', 'http://rcoa.gzedu.net/filedata/avatars/cdd.jpg', '', '', '1441614926', '1446443658');
INSERT INTO `rcoa_user` VALUES ('6', 'lbk', 'YWQ1DlBuZBnzTAgm7J6l2BciOxfGiB6S', '$2y$13$9D7.Fiauw.0B6r9WwR3UOuNmDI1tZxnwcYGWT964J/DfUs0PE.hQy', null, '1', 'lbk@163.com', '10', '黎饼坤', 'http://rcoa.gzedu.net/filedata/avatars/lbk.jpg', '', '', '1441614957', '1446443679');
INSERT INTO `rcoa_user` VALUES ('8', 'ykb', '4hPmrNoMkbHUyhZlRrnbxfWFcCblJR3z', '$2y$13$ffVbljJ35qG1wyry5XT0POZTF1FmidvbL7OSETqCuVYoFJnMWDwsS', null, '1', 'ykb@163.com', '10', '余克彬', '', null, null, '1442374028', '1442374028');
INSERT INTO `rcoa_user` VALUES ('9', 'wskeee', 'rgq5lSFeDMDtjf9wdLzIFGrywu0u5XD_', '$2y$13$5OTy36IbtSWibFRHHFP6VuNdkiTg9pAV6NqXaQl6yW.8PqxHnikKK', null, '1', 'wskeee@163.com', '10', 'wskeee', 'http://rcoa.gzedu.net/filedata/avatars/wskeee.jpg', '101463731', '', '1442374107', '1449649546');
INSERT INTO `rcoa_user` VALUES ('10', 'lindi', '0ApHSP2V2PMG4zxYTICURtgb9ESUCI0v', '123456', null, '1', 'lindi@gzedu.net', '10', '林迪', '', null, null, '1445226803', '1445226937');
INSERT INTO `rcoa_user` VALUES ('11', 'linyuxing', 'tEuqIO-8KYoq2OTt4UiIEiXF8QBt4trf', '123456', null, '1', 'linyuxing@gzedu.net', '10', '林育兴', '', null, null, '1445226883', '1450862032');
INSERT INTO `rcoa_user` VALUES ('12', 'huanglina', 'lZKYTKlqHFdDow_ASLJca4EhBq2rCT0z', '$2y$13$t6Z5gQXkS6qtju1G9bB5HeHzKqA3XgxRIlkCqC8.A0fPU.Hu6ttXq', null, '1', 'zhengguiqing@eenet.com', '10', '黄丽娜', '', '101480656', '', '1445226973', '1456301679');
INSERT INTO `rcoa_user` VALUES ('13', 'zouwei', 'JoxrwkO5XWBKp4m-QeTyx-NoTi_s6aM1', '$2y$13$.YvJ8zUkc/2KOfGGLMIOnOfvPRueWBugzZAeISwgQTT1WYQqwTIaO', null, '1', 'zhengguiqing@eenet.com', '10', '邹伟', '', '101480656', '', '1445227144', '1456301657');
INSERT INTO `rcoa_user` VALUES ('14', 'xiezhiwei', 'G7S932XljeJucxcqvRtBu3STlCYy0qiJ', '$2y$13$La4CBLj5nWdbAKXR7zShR.icjShfRjreVhOPIQR4knzuZ7v83Fikm', null, '1', 'zhengguiqing@eenet.com', '10', '谢志伟', '', '101480656', '', '1445227237', '1456301671');
INSERT INTO `rcoa_user` VALUES ('15', 'litin', '8aW_bCW2EomJTviD7XseOPnLiD3sVSn5', '$2y$13$gpXR1lFUAm7FLlg1ox47GuGYOZ8.sGg6.1RNaqtbbuYzTTxQ62qj2', null, '1', 'heyangchao@gzedu.net', '10', '李婷', '', '101463731', '', '1446429911', '1449649519');
INSERT INTO `rcoa_user` VALUES ('16', 'huangqiulin', 'Y7W8oaDcRe1tham2q1x4DHGI0-fMCCo8', '$2y$13$dibuw/E86F9tdfQetAgP5OGbpjBn23hjfQKysYjRfVCdD52p3fmIa', null, '1', 'heyangchao@gzedu.net', '10', '黄秋玲', '', '101463731', '', '1446430017', '1449649525');
INSERT INTO `rcoa_user` VALUES ('21', 'wuzhiqiang', '8nEVHs4a345u6CXxoRuIwcJZiWcBZR6C', '$2y$13$IF8txhIM36DtxZ4/SS2dm.VEKEjKc6yLGfBq1qFoXIAgHKzyhmd3W', null, '1', 'wzq@163.com', '10', '吴智强', 'http://rcoa.gzedu.net/filedata/avatars/wuzhiqiang.jpg', '101463731', '123456789', '1446443740', '1449649533');
INSERT INTO `rcoa_user` VALUES ('23', '159157621146', '8a56af6yhlttOJiaYRlEgZQ_Jraq4jMe', '123456', null, '1', null, '10', '马云4', '', null, '12312313', '1447293613', '1449648313');
INSERT INTO `rcoa_user` VALUES ('24', '15987412555', '-LyuSo0QB0k_hfja8keoENkxb53at396', '123456', null, '1', '11@1110.com', '10', '小小明', '', null, '15987412555', '1447640943', '1449216838');
INSERT INTO `rcoa_user` VALUES ('25', '159888888', 'ePETwACyhjhLCfjncc4PoywUh_sRgq2d', '123456', null, '1', 'hong@11.com', '10', '何小红', '', null, '159888888', '1447663454', '1447663454');
INSERT INTO `rcoa_user` VALUES ('26', '111111111', 'TP5dTWHdbktE4NwAp6nDjV9sDUDpOCG-', '123456', null, '1', '', '10', '抄老师', '', null, '111111111', '1447663599', '1447663599');
INSERT INTO `rcoa_user` VALUES ('29', '2', 'Xhndkhrt5SQ18aPV_ldHGsdVFTXypk1V', '123456', null, '1', '11@111.com', '10', '22', '', null, '2', '1447664397', '1447664397');
INSERT INTO `rcoa_user` VALUES ('31', '1115455555', 'AgSjbYJQkgiI_FUiBXdydCWBwEUxq2dG', '123456', null, '1', '', '10', '哎哟喂', '', null, '1115455555', '1447666133', '1447830789');
INSERT INTO `rcoa_user` VALUES ('32', '1235877878', 'NG-ZUzCheK1FSwxws-XHdD3Ui293oVXH', '123456', null, '1', '', '10', '毛老师', '', null, '1235877878', '1447666613', '1447830815');
INSERT INTO `rcoa_user` VALUES ('33', '1245678922', 'QOKnW0vGrCOYHYBfhr3CxPWDmfZjXec1', '123456', null, '1', '', '10', '刀教授', '', null, '1245678922', '1447666749', '1447842671');
INSERT INTO `rcoa_user` VALUES ('34', '1515151515', 'RucS5PLUSU2QUmlcGa1b8P540P6jj9ca', '123456', null, '1', '', '10', '李军明', '', null, '1515151515', '1447818877', '1447818877');
INSERT INTO `rcoa_user` VALUES ('35', '119', 'GjHB2hLfA_4quvDY9hwfERsgfOplh1_l', '123456', null, '1', '', '10', 'alex', '', null, '119', '1447819006', '1447831027');
INSERT INTO `rcoa_user` VALUES ('36', '1111111', 'Uz9AWhV9DQEyFhNe4Vwni_JbJLv4Fi5C', '123456', null, '1', null, '10', '黄丽', '', null, '1111111', '1447819126', '1450236337');
INSERT INTO `rcoa_user` VALUES ('37', '123456654321', 'brpNVijdhOad7rrpaOFg-X8yaVIcg-5E', '123456', null, '1', '', '10', '心老师', '', null, '123456654321', '1447819248', '1447831075');
INSERT INTO `rcoa_user` VALUES ('38', '123654789', 'lswRXy9eEsDGZeYwHZD1eFLgk4rtSM08', '123456', null, '1', '', '10', '江考题', '', null, '123654789', '1447821340', '1448263253');
INSERT INTO `rcoa_user` VALUES ('39', '9999999999', 'd-EJAvnEpZbK0rVhQpMWKqQgZJv9zJBb', '123456', null, '1', '', '10', '刘硅藻', '', null, '9999999999', '1447821482', '1448263303');
INSERT INTO `rcoa_user` VALUES ('40', '0102222234', 'hIWuqu_9MT-kJRhRvHhVXF01LKA7pyqH', '123456', null, '1', '', '10', 'elfee', '', null, '0102222234', '1447821578', '1447833804');
INSERT INTO `rcoa_user` VALUES ('41', 'huangjianhua', 'CbHSo_iVuoAg3ZUa-b0PhhTHfjrUDvvi', '$2y$13$z3y/7HyJX8gSfXRlvccEBeNJphoMpQQawkorRZ63wahuqg1HHrPim', null, '1', 'heyangchao@gzedu.net', '10', '黄建华', '', '123456789', '12345679', '1447830980', '1449482755');
INSERT INTO `rcoa_user` VALUES ('42', '111111', 'uGh3TpeuKDXo4ATqlLZGnAJG2MUtP6F3', '123456', null, '1', null, '10', '李小红', '', null, '1123123123', '1447842052', '1450058889');
INSERT INTO `rcoa_user` VALUES ('43', '11011245414', '3x2I4gAfKGWoncb-Fq5zL_aONMQKbHTH', '123456', null, '1', '', '10', '李心理', '', null, '11011245414', '1447988203', '1447989041');
INSERT INTO `rcoa_user` VALUES ('44', '1212121212', 'VrAp8PGkDlljgXZRRfTjOscIgpJhSdfI', '123456', null, '1', '', '10', '铁鹟', '', null, '1212121212', '1447988463', '1447989054');
INSERT INTO `rcoa_user` VALUES ('45', '0101411100', 'mqwLsx-shgT4pcfu5exjPlhYUBX2J-gl', '123456', null, '1', '', '10', 'eosfa', '', null, '0101411100', '1447988776', '1448263545');
INSERT INTO `rcoa_user` VALUES ('48', '123654', 'K-GKF0NyIfZ18aGoBPUKLrLVQqAh38sY', '123456', null, '1', '', '10', '李明', '', null, '123654', '1448263117', '1448263556');
INSERT INTO `rcoa_user` VALUES ('49', '123456654', 'LUXpqB_d9ZlyhVtk0xHxdsiSN-Gvdx1n', '123456', null, '1', '', '10', '何金银', '', null, '123456654', '1448263719', '1448263829');
INSERT INTO `rcoa_user` VALUES ('50', '123123', 'CJK8PcCwMfi8f_6RehfP7DsV_YTM0SKJ', '123456', null, '1', null, '10', '李老师', '', '165760648', '1345678901', '1448415953', '1451460162');
INSERT INTO `rcoa_user` VALUES ('51', '2222222222', 'n3t8yq3rW9TZhkoDebS354Th88BqcARd', '123456', null, '1', null, '10', '刘得华', '', null, '2222222222', '1448416351', '1448435584');
INSERT INTO `rcoa_user` VALUES ('52', '10086', 'm5p45pG7vtzKrjm4wohNgnBADjQCbn1-', '123456', null, '1', null, '10', '任志强', '', null, '10086', '1448416604', '1448418639');
INSERT INTO `rcoa_user` VALUES ('53', '123321123', 'FEjQ2peyM778OvRVbqC3yzip8hzpmTP5', '123456', null, '1', null, '10', '笑笑', '', null, '123321123', '1448419428', '1448941426');
INSERT INTO `rcoa_user` VALUES ('54', '13553345345', 'mc8nM14oIVIbgiX58VIAhLjbr0AdRX6x', '123456', null, '1', null, '10', '领导权', '', null, '13553345345', '1448436141', '1448436141');
INSERT INTO `rcoa_user` VALUES ('55', '45353535', 'FRXyRf9x6cLivT_uk6Pr-A7_FZf3diFI', '123456', null, '1', null, '10', '确认收', '', null, '45353535', '1448436262', '1449204316');
INSERT INTO `rcoa_user` VALUES ('57', '12233444', 'KLCbCsNyadSRUuL8NGDzDpClwNOoGhbX', '123456', null, '1', null, '10', '确认的', '', null, '12233444', '1448436936', '1448941150');
INSERT INTO `rcoa_user` VALUES ('58', '2334521245', 'lIfR_ZTKLiunDfUVIAiUV2gb3-YmV6pa', '123456', null, '1', null, '10', 'gggggg', '', null, '2334521245', '1448438291', '1449211957');
INSERT INTO `rcoa_user` VALUES ('59', 'zhengguiqing', 'ehuQ2CT9OicALj0CNsBExnQ6MrHlZSLK', '$2y$13$5vpNQkhy9zJzF0elekNiC.CI3GWSNpmdW401gaWBQze/quItlmUe6', null, '1', 'zhengguiqing@eenet.com', '10', '郑桂清', '', '', '', '1448587566', '1448587566');
INSERT INTO `rcoa_user` VALUES ('60', '15912647824', 'i4YzmmkwUsi3cgsJ9a91XtDljGSdg7Lt', '123456', null, '1', null, '10', '老变态', '', null, '15912647824', '1448959008', '1449136129');
INSERT INTO `rcoa_user` VALUES ('61', '5554558566', '91vXNT_x3bYBwgvY6HXwyuoMsw1bUINq', '123456', null, '1', null, '10', '我是要', '', null, '5554558566', '1449019304', '1449019304');
INSERT INTO `rcoa_user` VALUES ('62', '123645739933', 'HAAS2a3Lxj3CyVZ7nbv3e1zIQz_0aOp0', '123456', null, '1', null, '10', '煮锅凉', '', null, '123645739933', '1449020177', '1449020177');
INSERT INTO `rcoa_user` VALUES ('63', '172383837211', '0FsRD_q1dyMi6bmiUsAICE60yzSXazL0', '123456', null, '1', null, '10', '饭局三', '', null, '172383837211', '1449027937', '1449209682');
INSERT INTO `rcoa_user` VALUES ('64', '145225123', 'LMd_e3GItSG207wgxsX6QhmG1Ls2JVxn', '123456', null, '1', null, '10', '呃呃呃', '', null, '145225123', '1449484338', '1449538887');
INSERT INTO `rcoa_user` VALUES ('65', 'cfadc', 'DWmmVnQxMFAW49YSkgsZQ71b3w6-3cKd', '$2y$13$IBv9BC2NzHi2OgraXmjmaeU8niGBJvpvgEPFJIW.grlaE9NzaFA7O', null, '1', '11@11.ccc', '10', 'cfadc', '', '', '', '1449537727', '1449537727');
INSERT INTO `rcoa_user` VALUES ('66', '1333333333', '56fp3trrXgWH5dA2fXq-Ct6TV3q21raY', '123456', null, '1', null, '10', 'eee', '', null, '1333333333', '1449537933', '1450162330');
INSERT INTO `rcoa_user` VALUES ('67', '1234567899', 'kC94x8aRKh2BsBLnv3zEOf1u0m2MEcpP', '123456', null, '1', null, '10', '中国强', '', null, '1234567899', '1449544078', '1449544922');
INSERT INTO `rcoa_user` VALUES ('68', '12312312', 'uZv2K5hZwEkVxcdIkHLsKExfl3msU1ro', '123456', null, '1', null, '10', '何是地', '', null, '12312312', '1449545802', '1449546266');
INSERT INTO `rcoa_user` VALUES ('69', '12312341', 'nbMeo-t_mEAjz69OTetMQ_bTrKj6nw6C', '123456', null, '1', null, '10', '呕吐', '', null, '12312341', '1449546288', '1449546288');
INSERT INTO `rcoa_user` VALUES ('70', '123321231231', 'j7AZ-Q7eo64J0EPnEFmklggqmeVjn7Mz', '123456', null, '1', null, '10', '邓老师', '', null, '123321231231', '1449646777', '1449646777');
INSERT INTO `rcoa_user` VALUES ('71', '123123123', '6BE451IbviWOE8TRHvi-wIiwhTZriwpr', '123456', null, '1', null, '10', '黄老师', '', null, '123123123', '1449650173', '1451301883');
INSERT INTO `rcoa_user` VALUES ('72', 'abcd', 'ZkDwKAuTnCEGkXWq_YT-e6PZ7R7Jp8R6', '$2y$13$OOtI/fFGffJTfUG4QnY10.dPNzyg5R8dcPAwH96ZEQGpuVQ3JD6tS', null, '1', 'aaaa@qq.cc', '10', 'abcd', '', '', '', '1449654613', '1449654613');
INSERT INTO `rcoa_user` VALUES ('73', '25525214354', 'GcuIHCanAHMOasYxsOdfXpQklHu9O2PE', '123456', null, '1', null, '10', '新开工', '', null, '25525214354', '1449737065', '1449737065');
INSERT INTO `rcoa_user` VALUES ('74', 'asdf', 'F5WGrRjpJiRrGe4pZoK08VBfVW3oROJt', '$2y$13$0wdbkBBvof7gK1QGOtkAMempKrbprUFuquWP6g4ohDUMOZNhP50CG', null, '1', 'zhengguiqing@eenet.com', '10', '孟浩', '', '165760648', '', '1449802303', '1450771803');
INSERT INTO `rcoa_user` VALUES ('75', '11111', 'TD3qRbq1gOEcyDTx63sHiNUiRGo3juo0', '123456', null, '1', null, '10', '11111', '', null, '12345678901', '1449824391', '1450323682');
INSERT INTO `rcoa_user` VALUES ('76', 'wuge', 'h1_6V4QJxmhLxZq5vjeoYdqPTz4wkPtE', '$2y$13$GBv/zVrYSk6JhPFbV2Qpu..1tGh42s1.8Vb5hJ/rwgjNtYCprmWEi', null, '1', 'zhengguiqing@eenet.com', '10', '吴哥', '', '165760648', '', '1449826986', '1450236753');
INSERT INTO `rcoa_user` VALUES ('77', '1234123123', 'u5YWONht4WNqGgl2mtGyZ-fBoz6Ve-Uu', '123456', null, '1', null, '10', '邮老师', '', null, '1234123', '1450167802', '1450342670');
INSERT INTO `rcoa_user` VALUES ('78', '1574545454', 'JW6LQ_jTFMrXk0_c4sQNr8nfMC3GvLJy', '123456', null, '1', null, '10', '何老师', '', null, '1234123', '1450174064', '1450677505');
INSERT INTO `rcoa_user` VALUES ('79', 'xiaomi', 'Y8BB2AeNZw3cJqxEpK6YMIA_hA4u3ycI', '$2y$13$tSNqvhjFRYAn/GvewnG2TuVN2Huq8iV/BrRfyM9XY8QMvVkIeIpmu', null, '1', 'zhengguiqing@eenet.com', '10', '小芈', '', '165760648', '', '1450238330', '1450238330');
INSERT INTO `rcoa_user` VALUES ('80', '13414081006', 'b-D4g4YBhErtvN_mUD5seQqbjT25zYDb', '123456', null, '1', null, '10', '小敏', '', null, '13414081006', '1450238468', '1450425334');
INSERT INTO `rcoa_user` VALUES ('81', '12345678910', 'vWFaYIqfrGWbTkMhUm9rEr9DEe-_qLar', '123456', null, '1', null, '10', '晓华', '', null, '12345678910', '1450245400', '1450331718');
INSERT INTO `rcoa_user` VALUES ('82', '15555555666', 'UZ8bGhowTdYN6qVmwUQMtAuOfbkon8Se', '123456', null, '1', null, '10', '卡咕咕', '', null, '15555555666', '1450254200', '1450254200');
INSERT INTO `rcoa_user` VALUES ('83', '1231231231231', 'AMhRt0Ccxy6wR4E3H_yem4bD_nUin2VY', '123456', null, '1', null, '10', '或者', '', null, '1231231231231', '1450254981', '1450254981');
INSERT INTO `rcoa_user` VALUES ('84', '1565565232', 'wC3db7vUM_FZS7bVDxUuOql5aNedAM4M', '123456', null, '1', null, '10', '英老师', '', null, '123734534', '1450255210', '1451559314');
INSERT INTO `rcoa_user` VALUES ('85', '1345678901', '2ZUWA6JeiwOdenxiwjqkPm255gNKAg5N', '123456', null, '1', null, '10', '薛娇', '', '165760648', '1345678901', '1450317347', '1451638367');
INSERT INTO `rcoa_user` VALUES ('86', 'xiaoe', 'MhTZlC4tzmaYfT65SO_oHM8XzYwGDpnE', '$2y$13$P8oVVRf1VR46pCqQINKxF.wbBdhpZFcOJXqZmRc083O.aFOcbVA8i', null, '2', 'heyangchao@gzedu.net', '10', '小E', 'http://rcoa.gzedu.net/filedata/avatars/xiaoe.jpg', '101763469', '15915762146', '1450317903', '1450317903');
INSERT INTO `rcoa_user` VALUES ('87', '12345678901', 'oTjnljLZRp-rAm7iPepmVTkg-rSYT90R', '123456', null, '1', null, '10', '小华', '', '165760648', '12345678901', '1450319554', '1450406898');
INSERT INTO `rcoa_user` VALUES ('88', 'xuejiao', '4AP1ksL90P5ox6s4yPqnLH4EsGkZBr60', '$2y$13$9RVPz/4wmDJrVbIJl2w9Ruj3dpM7MgpdYEbp0lbyng2j14zhFXdyC', null, '1', 'zhengguiqing@eenet.com', '10', '薛娇', '', '1567606448', '', '1450335937', '1450335937');
INSERT INTO `rcoa_user` VALUES ('91', 'jinglaodao', 'AtzoHVIF-TV6_i59QUfa9MKO3J9Ez5nK', '$2y$13$5lQBUmGS8Zi3a6vDlX67qepwY7G07ZYsjxI63UV18uWnu5Vn5VRcu', null, '1', 'heyangchao@gzedu.net', '10', '金唠叨', '', null, '15915472512', '1450408127', '1450408127');
INSERT INTO `rcoa_user` VALUES ('92', 'liming', 'aIUuCAMHkOQoUiVfzfm1BuuRlks2w12R', '$2y$13$bhUKrirFWaM1b.n9w9cHyeFuJxNVaHdZqFxCLg6vWzWrzWtP.VQim', null, '1', 'qingguiqing@eenet.com', '10', '李明', '', null, '15475222555', '1450408696', '1450776488');
INSERT INTO `rcoa_user` VALUES ('93', 'engugu', 'hHaLdyVF7notnVb2Nij5KliSMxNpWMrO', '$2y$13$H0gFDFv8cs2WzCHdYjuUUuin4Dnh477u5xbazWyQxrQIYKMF1izGC', null, '2', 'zhengguiqing@eenet.com', '10', '恩咕咕', '', null, '12345654514', '1450409016', '1450409016');
INSERT INTO `rcoa_user` VALUES ('94', 'huangjiyin', 'knRzqPeOQ7Z6qKE0dm0cyT7JjbUmYerM', '$2y$13$oABOnKuSxTMtOTcbgQ6Vxex1gBGoJQP3g9ay28kKZuOAMa2P0anw2', null, '1', 'zhengguiqing@eenet.com', '10', '黄基因', '', '165760648', '12466532132', '1450409159', '1451901885');
INSERT INTO `rcoa_user` VALUES ('95', 'baizhongshi', 'cJuNgADDVx93-6jelRb5-89e2gp_Fwc8', '$2y$13$n.N42Gnu9/G7bfgvE3sRIOIn3LdKm0SUIiTHozxko8x3kLiFR0p2i', null, '1', 'heyangchao@gzedu.net', '10', '百宗师', '', null, '12365456', '1450409338', '1450409338');
INSERT INTO `rcoa_user` VALUES ('96', 'caocao', 'DiPu7NHZnXIqG8keDTP7KBNzdSsltrJn', '$2y$13$G.GGFIKWe9ZmZCbDETKg3edm.tvfjnBti4JEpIaqU4QnGdmcWk9Uy', null, '1', 'zhengguiqing@eenet.com', '10', '曹操', '', null, '888888888', '1450409509', '1450409509');
INSERT INTO `rcoa_user` VALUES ('97', 'direnjie', '9RmptohVpg41QwW6e5D1CddAVqXzYOav', '$2y$13$KZGnyQjClcQWhZkAcB20RuwEd1mkvMB4JAXjYzquwjAzBweAdlLAq', null, '1', 'heyangchao@gzedu.net', '10', '狄仁杰', '', null, '23123232', '1450409637', '1450409637');
INSERT INTO `rcoa_user` VALUES ('98', 'wangyizhi', 'sda6G-EjEgD5k2iVePzyYfXgJUG2wVDy', '$2y$13$0NaeQFTxXYYKUi4Nvwga8ubUo3WUOsNmx8s.uOKsSDO7HZ3dvSgGG', null, '2', 'zhengguiqing@eenet.com', '10', '王羲之', '', null, '326545646', '1450409789', '1450409789');
INSERT INTO `rcoa_user` VALUES ('99', 'yanzhengqin', 'gtPdkrdiGhqWAjI74PYsbGGUh2DBheVm', '$2y$13$F03y34zAw33XD.eK0TMbleqNw4pf5KNZ5Ri.29ev105EwE7aJw3Yq', null, '1', 'heyangchao@gzedu.net', '10', '颜真卿', '', null, '12312314513', '1450409958', '1450409958');
INSERT INTO `rcoa_user` VALUES ('100', '21552211212', '82Dbm9XV6Nmhc6GixDUztaAL6tlEmTMc', '123456', null, '1', null, '10', '国或3', '', null, '21552211212', '1450424144', '1450677393');
INSERT INTO `rcoa_user` VALUES ('101', '15555', 'CSom2frmFz1m4VR_uKzFPzJBVyuWMGUS', '123456', null, '1', null, '10', '哇靠', '', null, '15555', '1450424316', '1450604628');
INSERT INTO `rcoa_user` VALUES ('102', 'wangjiawei', 'ea4iVisIqvOR1IYzWl8qwjA_piw7oV68', '$2y$13$nY0lF7rQz2C2qaYDtpK2weSGbLrzkkA5pfxTt7DoI4PaQMmWP9/.O', null, '1', 'wulinan@gzedu.net', '10', '王家卫', '', null, '15921547445', '1450682231', '1450682231');
INSERT INTO `rcoa_user` VALUES ('103', '18122101800', '-VGga2YtBtmXoxD_Z5YjYOEht3NOWOL4', '123456', null, '1', null, '10', '里老师', '', null, '18122101800', '1450683540', '1450685278');
INSERT INTO `rcoa_user` VALUES ('104', '1800664455', 't7PyiEr194nzniPYAmEgNYetUh7eTY4Y', '123456', null, '1', null, '10', '李老师', '', null, '12', '1450685613', '1450766367');
INSERT INTO `rcoa_user` VALUES ('105', '112355556', 'jJsSVhJNJsyQWtQorrWx2VuUhc9J3qW1', '123456', null, '1', null, '10', '李生', '', null, '112355556', '1450686249', '1450686249');
INSERT INTO `rcoa_user` VALUES ('106', '12', 'YngXvILOt4LWKwnwPGZjKlnyHggdjCEZ', '123456', null, '1', null, '10', '李', '', null, '12', '1450763970', '1450766427');
INSERT INTO `rcoa_user` VALUES ('107', '12312385665', 'O_EotSXEepJCaZAJKtnnicIXyO9o1bTy', '123456', null, '1', null, '10', '数老师', '', null, '5123123123', '1450768832', '1451301941');
INSERT INTO `rcoa_user` VALUES ('108', 'zuojielun', 'Rc-EVp7GYxfWj2CS0BFqyPgd5YQNrHWx', '$2y$13$nt0iyXTjNf4CPLxT4K9v5O7M9CYXJZG1yCkrrok/uqplJLDInDME6', null, '1', 'heyangchao@gzedu.net', '10', '周杰伦', '', null, '15915762145', '1450920324', '1450920324');
INSERT INTO `rcoa_user` VALUES ('109', 'zouxingchi', 'JkIvtqjRPI6weHSSJ3JRseRdJa5AuR2p', '$2y$13$ABK2F5ud4ZoWx7FPSmNyNeOwkYuUvQf650f7Az9roDb/cnDkj5aBe', null, '1', 'heyangchao@gzedu.net', '10', '周星驰', '', null, '15915762147', '1450920669', '1450920669');
INSERT INTO `rcoa_user` VALUES ('110', 'zourenfa', 'fIJQzZ58EjetiyR4O7RPEdTooCqO1Wxs', '$2y$13$ny1YkukroniH9CQJ09qKLOoWxXJlSBt3grrZEOKWskcdFZ.4xOdvO', null, '1', 'zhengguiqing@eenet.com', '10', '周润发', '', null, '1571586255', '1450920928', '1450920928');
INSERT INTO `rcoa_user` VALUES ('111', 'tangren', 'c6F-Mnnn-PVTJ_fq65kr87s1eKWGuk4k', '$2y$13$ETeA61tx2189Rj5mkSoXmud/1Zp8Nd9WSuwUdv0IN5CZL64BXij2W', null, '1', 'heyangchao@gzedu.net', '10', '唐寅', '', null, '157152555', '1450921478', '1450921984');
INSERT INTO `rcoa_user` VALUES ('112', 'yeqinlao', 'R0R3VcNElrSaR-rPp66kuPjtZWJNawQp', '$2y$13$WExh4kG7UeQmK3NEnXQTzuOzSGsE2WBxoqEb3fCqCmWy0qn0rcnrO', null, '2', 'zhengguiqing@eenet.com', '10', '月勤劳', '', null, '3555333', '1450921771', '1450921771');
INSERT INTO `rcoa_user` VALUES ('113', '12313', 'aBkQW4BsNaYBNlnsZ6vCtcYD6f-qVUE7', '123456', null, '1', null, '10', '李者呈', '', null, '12313', '1450949844', '1451267402');
INSERT INTO `rcoa_user` VALUES ('114', 'ttaet', 'cUy3oTaCRPIE4_e75tPmcEXiU1Hn3zij', '$2y$13$awXtIG6TiNv0x5gFM1hGdu4YWDZdEwZaM1BLm2zo5vHFvqtUcuFFS', null, '1', '1212@163.com', '10', '捞到', '', null, '2323223', '1451007178', '1451007178');
INSERT INTO `rcoa_user` VALUES ('115', 'ttaet23', 'hMu-ufVrsZqNdfmmLhaZpmIk-V6lzHjy', '$2y$13$ZxXDz5PXxzLASweHqdKOCOUmm1TfdElIuwzt79nvGoItsdwhN/5Ki', null, '2', '2123123@163.adf', '10', '王国荣', '', null, '123123', '1451007202', '1451007202');
INSERT INTO `rcoa_user` VALUES ('116', 'jincancan', 'Ec1KgWSkvEaaEd88ADdlUkNwiZ2lCG6k', '$2y$13$/iSUiyDSf0IObVEi1NdGLu0VoPdM7BWJK9vmBntest4c17pDvXawm', null, '1', 'zhengguiqing@eenet.com', '10', '金灿灿', '', null, '12345678901', '1452506733', '1452506733');
INSERT INTO `rcoa_user` VALUES ('117', 'zhouwei2', '-BBHrQcPSD3GLj0pV0Vzh9azym88SBWP', '$2y$13$pNsr.oT7Ytsk03PBn0O5JOEygLyUbGZ7taZe70kL0bk0miiTPB6A.', null, '1', 'zhengguiqing@eenet.com', '10', '邹伟2', '', '', '', '1453778305', '1453778305');
