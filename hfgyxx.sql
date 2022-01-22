# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.23)
# Database: gyjj
# Generation Time: 2021-05-13 09:29:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table gyjj_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_admin_user`;

CREATE TABLE `gyjj_admin_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '后端用户主键ID',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户密码',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态码 1正常 0待审核，99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '最后登录IP',
  `operate_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `gyjj_admin_user` WRITE;
/*!40000 ALTER TABLE `gyjj_admin_user` DISABLE KEYS */;

INSERT INTO `gyjj_admin_user` (`id`, `username`, `password`, `status`, `create_time`, `update_time`, `last_login_time`, `last_login_ip`, `operate_user`)
VALUES
	(1,'admin','5a7686ad50d031eb7533613216913713',1,0,1604556866,1604556866,'183.160.1.158',''),
	(2,'ceshi','eff782d83be27411d2354a0b73e59787',1,1603337280,1603337617,1603337617,'183.160.1.77','admin');

/*!40000 ALTER TABLE `gyjj_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gyjj_banner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_banner`;

CREATE TABLE `gyjj_banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `img_url` varchar(100) NOT NULL COMMENT '图片地址',
  `sequence` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示:0否1是',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='banner表';

LOCK TABLES `gyjj_banner` WRITE;
/*!40000 ALTER TABLE `gyjj_banner` DISABLE KEYS */;

INSERT INTO `gyjj_banner` (`id`, `img_url`, `sequence`, `is_show`, `status`, `create_time`, `update_time`, `delete_time`)
VALUES
	(1,'/upload/images/20200910/040cf6daff17d4cab66568828e1a19be.jpg',0,1,99,1599670772,1599670772,NULL),
	(2,'/upload/images/20200910/040cf6daff17d4cab66568828e1a19be.jpg',0,1,99,1599670934,1599670934,NULL),
	(3,'https://gyjj.schoolpi.net/upload/images/20201020/b63ba7bf687d04c2d2ef71cfc66fd25a.png',0,1,99,1603157167,1603157167,NULL),
	(4,'https://gyjj.schoolpi.net/upload/images/20201020/ebd8d48c3d105b46a2cbda3144557181.png',0,1,1,1603157253,1603157253,NULL),
	(5,'https://gyjj.schoolpi.net/upload/images/20201020/4315ad230048d57b8d563a6c8416740a.png',1,1,1,1603157346,1603157346,NULL);

/*!40000 ALTER TABLE `gyjj_banner` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gyjj_book_borrow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_book_borrow`;

CREATE TABLE `gyjj_book_borrow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `M_TITLE` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `LEND_DATE` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '借阅日期',
  `NORM_RET_DATE` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '应还日期',
  `CERT_ID_F` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='图书借阅表';



# Dump of table gyjj_book_list
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_book_list`;

CREATE TABLE `gyjj_book_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `M_TITLE` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `M_AUTHOR` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '作者',
  `M_PUBLISHER` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '出版社',
  `M_PUB_YEAR` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '出版日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='图书列表';



# Dump of table gyjj_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_category`;

CREATE TABLE `gyjj_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `path` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '路径, 逗号分割,如1,2,3',
  `operate_user` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '操作人',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示:0否1是',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `sequence` int unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='分类表';

LOCK TABLES `gyjj_category` WRITE;
/*!40000 ALTER TABLE `gyjj_category` DISABLE KEYS */;

INSERT INTO `gyjj_category` (`id`, `name`, `pid`, `path`, `operate_user`, `is_show`, `status`, `sequence`, `create_time`, `update_time`, `delete_time`)
VALUES
	(1,'校园简介',0,'','',1,1,1,1601190958,1601190958,NULL),
	(2,'校园新闻',0,'','',1,1,1,1601190963,1601190963,NULL),
	(3,'招生频道',0,'','',1,1,1,1601191077,1601191077,NULL),
	(4,'专业设置',0,'','',1,1,1,1601191083,1601191083,NULL),
	(5,'师资力量',0,'','',1,1,1,1601191092,1601191092,NULL),
	(6,'通知公告',0,'','',1,1,1,1601191099,1601191099,NULL),
	(7,'佳作赏析',0,'','',1,1,1,1601191107,1601191107,NULL),
	(8,'办学成果',0,'','',1,1,1,1601191116,1601191116,NULL),
	(9,'就业保障',0,'','',1,1,1,1601191132,1601191132,NULL),
	(10,'校园活动',0,'','',1,1,1,1601191140,1601191140,NULL),
	(11,'学校荣誉',0,'','',1,1,1,1601191147,1601191147,NULL),
	(12,'迎接新生',0,'','',1,1,1,1601191154,1601191154,NULL),
	(13,'校园风光',0,'','',1,1,1,1601191592,1601191592,NULL),
	(14,'学校视频',0,'','',1,1,1,1601191608,1601191608,NULL),
	(15,'招生简章',3,'','',1,1,1,1601193577,1601193577,NULL),
	(16,'招生咨询',3,'','',1,1,1,1601193586,1601193586,NULL),
	(17,'奖助政策',3,'','',1,1,1,1601193596,1601193596,NULL),
	(18,'实习',9,'','',1,1,1,1601281002,1601281002,NULL),
	(19,'就业',9,'','',1,1,1,1601281012,1601281012,NULL),
	(20,'图片',7,'','',1,1,1,1601282922,1601282922,NULL),
	(21,'视频',7,'','',1,1,1,1601282930,1601282930,NULL),
	(22,'联系我们',0,'','',1,1,1,1601282940,1601282940,NULL);

/*!40000 ALTER TABLE `gyjj_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gyjj_department
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_department`;

CREATE TABLE `gyjj_department` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `pid` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `sequence` int unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='部门架构表';



# Dump of table gyjj_department_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_department_user`;

CREATE TABLE `gyjj_department_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '职工号',
  `department_id` int unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `is_leader` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是负责人',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态码 1正常 0待审核，99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部门人员表';



# Dump of table gyjj_enroll
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_enroll`;

CREATE TABLE `gyjj_enroll` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别, 1男2女',
  `city` varchar(20) NOT NULL DEFAULT '' COMMENT '城市',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `subject` varchar(20) NOT NULL DEFAULT '' COMMENT '报考专业',
  `school` varchar(20) NOT NULL DEFAULT '' COMMENT '就读院校',
  `score` varchar(20) NOT NULL DEFAULT '' COMMENT '预估分',
  `graduated_year` varchar(20) NOT NULL DEFAULT '' COMMENT '毕业年份',
  `pass_number` varchar(50) NOT NULL DEFAULT '' COMMENT '准考证号',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='招生表';



# Dump of table gyjj_jsxxb
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_jsxxb`;

CREATE TABLE `gyjj_jsxxb` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `ZGH` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '职工号',
  `XM` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='教师信息表';



# Dump of table gyjj_lost
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_lost`;

CREATE TABLE `gyjj_lost` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `summary` varchar(100) NOT NULL DEFAULT '' COMMENT '概要',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `img_url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片地址',
  `detail` varchar(500) NOT NULL DEFAULT '' COMMENT '详情',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常,2已结束,99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='失物招领表';



# Dump of table gyjj_lottery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_lottery`;

CREATE TABLE `gyjj_lottery` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '发布人uid',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `start_time` int unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '抽奖人数',
  `target` tinyint(1) NOT NULL DEFAULT '0' COMMENT '目标人群, 0全部, 1老师, 2学生',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 2已结束, 99删除',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '介绍',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽奖表';



# Dump of table gyjj_lottery_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_lottery_option`;

CREATE TABLE `gyjj_lottery_option` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `lottery_id` int NOT NULL DEFAULT '0' COMMENT '抽奖表id',
  `count` int NOT NULL DEFAULT '0' COMMENT '抽奖人数',
  `value` varchar(20) NOT NULL DEFAULT '' COMMENT '奖项值',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `lottery_id` (`lottery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='抽奖选项表';



# Dump of table gyjj_lottery_winning
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_lottery_winning`;

CREATE TABLE `gyjj_lottery_winning` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `lottery_id` int NOT NULL DEFAULT '0' COMMENT '抽奖表id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '中奖人id',
  `number` int NOT NULL DEFAULT '0' COMMENT '中奖人对应的号码',
  `rank` tinyint NOT NULL DEFAULT '0' COMMENT '奖项排名',
  `rank_name` varchar(20) NOT NULL DEFAULT '' COMMENT '几等奖',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 2已结束, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `lottery_id` (`lottery_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='抽奖活动中奖表';



# Dump of table gyjj_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_menu`;

CREATE TABLE `gyjj_menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '路径, 前端定义内容, 原路返回即可',
  `src` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '图标, 前端使用',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示:0否1是',
  `cate_id` int unsigned NOT NULL DEFAULT '0',
  `sequence` int unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `status` tinyint(1) NOT NULL COMMENT '状态：1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='首页menu表';

LOCK TABLES `gyjj_menu` WRITE;
/*!40000 ALTER TABLE `gyjj_menu` DISABLE KEYS */;

INSERT INTO `gyjj_menu` (`id`, `title`, `url`, `src`, `is_show`, `cate_id`, `sequence`, `status`, `create_time`, `update_time`, `delete_time`)
VALUES
	(1,'学校简介','/pages/windex/introduction/introduction','/static/windex/index/menu_xxjj.png',1,1,1,1,1601171197,1601171197,NULL),
	(2,'学校新闻','/pages/windex/new/new','/static/windex/index/menu_xxxw.png',1,2,1,1,1601171233,1601171233,NULL),
	(3,'招生频道','/pages/windex/message/message','/static/windex/index/menu_zsxc.png',1,3,1,1,1601171253,1601171253,NULL),
	(4,'专业设置','/pages/windex/major/major','/static/windex/index/menu_zyk2.png',1,4,1,1,1601171275,1601171275,NULL),
	(5,'师资力量','/pages/windex/teacher/teacher','/static/windex/index/menu_szll.png',1,5,1,1,1601171294,1601171294,NULL),
	(6,'通知公告','/pages/windex/notice/notice','/static/windex/index/menu_xygg.png',1,6,1,1,1601171313,1601171313,NULL),
	(7,'佳作赏析','/pages/windex/better/better','/static/windex/index/menu_jzsx.png',1,7,1,1,1601171330,1601171330,NULL),
	(8,'办学成果','/pages/windex/results/results','/static/windex/index/menu_bxcg.png',1,8,1,1,1601171349,1601171349,NULL),
	(9,'报名','/pages/windex/better/better','/static/windex/index/menu_bm.png',1,0,1,1,1601171372,1601171372,NULL),
	(10,'就业保障','/pages/windex/employment/employment','/static/windex/index/menu_jybz.png',1,9,1,1,1601171388,1601171388,NULL),
	(11,'校园活动','/pages/windex/activity/activity','/static/windex/index/menu_xyhd.png',1,10,1,1,1601171406,1601171406,NULL),
	(12,'学校荣誉','/pages/windex/honor/honor','/static/windex/index/menu_xxry.png',1,11,1,1,1601171424,1601171424,NULL),
	(13,'迎接新生','/pages/windex/greet/greet','/static/windex/index/menu_yjxs.png',1,12,1,1,1601171439,1601171439,NULL),
	(14,'联系我们','/pages/windex/contact/contact','/static/windex/index/menu_lxwm.png',1,0,1,1,1601171455,1601171455,NULL);

/*!40000 ALTER TABLE `gyjj_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gyjj_migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_migrations`;

CREATE TABLE `gyjj_migrations` (
  `version` bigint NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gyjj_migrations` WRITE;
/*!40000 ALTER TABLE `gyjj_migrations` DISABLE KEYS */;

INSERT INTO `gyjj_migrations` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`)
VALUES
	(20181113071924,'CreateRulesTable','2020-09-10 09:28:20','2020-09-10 09:28:20',0);

/*!40000 ALTER TABLE `gyjj_migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gyjj_news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_news`;

CREATE TABLE `gyjj_news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新闻标题',
  `small_title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新闻短标题',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '操作人id',
  `cate_id` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `cover_url` varchar(200) NOT NULL DEFAULT '' COMMENT '封面图url地址, 主要给视频使用',
  `img_urls` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `xwbh` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新闻编号, 采集自校方新闻',
  `desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新闻描述',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门, 0否1是',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶, 0否1是',
  `read_count` int unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `upvote_count` int unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `comment_count` int unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `pub_date` int unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻资讯表';



# Dump of table gyjj_news_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_news_content`;

CREATE TABLE `gyjj_news_content` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `news_id` int NOT NULL DEFAULT '0' COMMENT '新闻id',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '新闻内容',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `news_id` (`news_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='新闻内容表';



# Dump of table gyjj_proctor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_proctor`;

CREATE TABLE `gyjj_proctor` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '老师职工号',
  `date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '日期',
  `time_period` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '时间段',
  `place` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '考试地点',
  `subject` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '考试科目',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='监考表';



# Dump of table gyjj_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_question`;

CREATE TABLE `gyjj_question` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '发布人uid',
  `title` varchar(100) NOT NULL DEFAULT '1' COMMENT '标题',
  `start_time` int unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `intro` varchar(200) NOT NULL DEFAULT '' COMMENT '问卷介绍',
  `attend_count` int NOT NULL DEFAULT '0' COMMENT '参与人数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 2已结束, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='问卷表';



# Dump of table gyjj_question_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_question_option`;

CREATE TABLE `gyjj_question_option` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `problem_id` int NOT NULL DEFAULT '0' COMMENT '题目id',
  `question_id` int NOT NULL DEFAULT '0' COMMENT '问卷id',
  `value` varchar(500) NOT NULL DEFAULT '' COMMENT '选项内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `problem_id` (`problem_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='题目选项表';



# Dump of table gyjj_question_problem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_question_problem`;

CREATE TABLE `gyjj_question_problem` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `question_id` int NOT NULL DEFAULT '0' COMMENT '问卷id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '题目标题',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '题目类型, 1单选2多选',
  `sequence` tinyint(1) NOT NULL DEFAULT '1' COMMENT '排序',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='题目表';



# Dump of table gyjj_question_result
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_question_result`;

CREATE TABLE `gyjj_question_result` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '答题人id',
  `problem_id` int NOT NULL DEFAULT '0' COMMENT '题目id',
  `question_id` int NOT NULL DEFAULT '0' COMMENT '问卷id',
  `option_id` int NOT NULL DEFAULT '0' COMMENT '选项id',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `problem_id` (`problem_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='问卷题目结果表';



# Dump of table gyjj_question_suggest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_question_suggest`;

CREATE TABLE `gyjj_question_suggest` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `question_id` int NOT NULL DEFAULT '0' COMMENT '问卷id',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 2已结束, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户问卷建议表';



# Dump of table gyjj_repair
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_repair`;

CREATE TABLE `gyjj_repair` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `img_url` varchar(500) NOT NULL DEFAULT '' COMMENT '图片地址',
  `repair_cate_id` int unsigned NOT NULL DEFAULT '0' COMMENT '类目id',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '报修人id',
  `approver_id` int unsigned NOT NULL DEFAULT '0' COMMENT '审批人id',
  `repare_id` int unsigned NOT NULL DEFAULT '0' COMMENT '维修人id',
  `progress_bar` tinyint(1) NOT NULL DEFAULT '1' COMMENT '维修进度, 0拒绝, 1提交, 2审核, 3维修, 4完成',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '报修地址',
  `repair_desc` varchar(200) NOT NULL DEFAULT '' COMMENT '报修描述',
  `comment` varchar(200) NOT NULL DEFAULT '' COMMENT '评价信息',
  `approver_advice` varchar(200) NOT NULL DEFAULT '' COMMENT '审批人意见',
  `reparer_advice` varchar(200) NOT NULL DEFAULT '' COMMENT '维修人意见',
  `rating` tinyint(1) DEFAULT NULL COMMENT '评价, 星级, 共5星',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='报修表';



# Dump of table gyjj_repair_cate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_repair_cate`;

CREATE TABLE `gyjj_repair_cate` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `pid` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='报修类目表';



# Dump of table gyjj_rules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_rules`;

CREATE TABLE `gyjj_rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ptype` varchar(255) DEFAULT NULL,
  `v0` varchar(255) DEFAULT NULL,
  `v1` varchar(255) DEFAULT NULL,
  `v2` varchar(255) DEFAULT NULL,
  `v3` varchar(255) DEFAULT NULL,
  `v4` varchar(255) DEFAULT NULL,
  `v5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table gyjj_salary
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_salary`;

CREATE TABLE `gyjj_salary` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `month` varchar(20) NOT NULL DEFAULT '' COMMENT '月份',
  `number` varchar(50) NOT NULL DEFAULT '' COMMENT '职工号',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `tfgzhj` decimal(8,2) DEFAULT NULL COMMENT '统发工资合计',
  `gwgz` decimal(8,2) DEFAULT NULL COMMENT '岗位工资',
  `xjgz` decimal(8,2) DEFAULT NULL COMMENT '薪级工资',
  `jcxjxgz` decimal(8,2) DEFAULT NULL COMMENT '基础性绩效工资',
  `jhtg` decimal(8,2) DEFAULT NULL COMMENT '教护提高10%',
  `tfbf` decimal(8,2) DEFAULT NULL COMMENT '统发补发',
  `dfgzhj` decimal(8,2) DEFAULT NULL COMMENT '代发工资合计',
  `jhljt` decimal(8,2) DEFAULT NULL COMMENT '教、护龄津贴',
  `ft` decimal(8,2) DEFAULT NULL COMMENT '房贴',
  `dszvf` decimal(8,2) DEFAULT NULL COMMENT '独生子女费',
  `hmbt` decimal(8,2) DEFAULT NULL COMMENT '回民补贴',
  `jtbt` decimal(8,2) DEFAULT NULL COMMENT '交通补贴',
  `dfbf` decimal(8,2) DEFAULT NULL COMMENT '代发补发',
  `dkgzhj` decimal(8,2) DEFAULT NULL COMMENT '代扣工资合计',
  `gjj` decimal(8,2) DEFAULT NULL COMMENT '公积金',
  `yb` decimal(8,2) DEFAULT NULL COMMENT '医保',
  `sybx` decimal(8,2) DEFAULT NULL COMMENT '失业保险',
  `ylbx` decimal(8,2) DEFAULT NULL COMMENT '养老保险',
  `zynj` decimal(8,2) DEFAULT NULL COMMENT '职业年金',
  `ghhf` decimal(8,2) DEFAULT NULL COMMENT '工会会费',
  `dwdkgs` decimal(8,2) DEFAULT NULL COMMENT '单位代扣个税',
  `fz` decimal(8,2) DEFAULT NULL COMMENT '房租',
  `qtdk` decimal(8,2) DEFAULT NULL COMMENT '其他代扣',
  `yfgzhj` decimal(8,2) DEFAULT NULL COMMENT '应发工资合计',
  `sfgzhj` decimal(8,2) DEFAULT NULL COMMENT '实发工资合计',
  `jbgz` decimal(8,2) DEFAULT NULL COMMENT '基本工资',
  `xljt` decimal(8,2) DEFAULT NULL COMMENT '校龄津贴',
  `qtbf` decimal(8,2) DEFAULT NULL COMMENT '其他补发',
  `yfgz` decimal(8,2) DEFAULT NULL COMMENT '应发工资',
  `gjj1` decimal(8,2) DEFAULT NULL COMMENT '公积金1',
  `ylbx1` decimal(8,2) DEFAULT NULL COMMENT '医疗保险1',
  `sybx1` decimal(8,2) DEFAULT NULL COMMENT '失业保险1',
  `ylbx11` decimal(8,2) DEFAULT NULL COMMENT '养老保险1',
  `ghhf1` decimal(8,2) DEFAULT NULL COMMENT '工会会费1',
  `dwdkgs1` decimal(8,2) DEFAULT NULL COMMENT '单位代扣个税1',
  `qtdk1` decimal(8,2) DEFAULT NULL COMMENT '其他代扣1',
  `dkgzxj` decimal(8,2) DEFAULT NULL COMMENT '代扣工资小计',
  `sfgz` decimal(8,2) DEFAULT NULL COMMENT '实发工资',
  `jlxjx` decimal(8,2) DEFAULT NULL COMMENT '奖励性绩效',
  `ksjt` decimal(8,2) DEFAULT NULL COMMENT '课时津贴',
  `zbf` decimal(8,2) DEFAULT NULL COMMENT '值班费',
  `kwf` decimal(8,2) DEFAULT NULL COMMENT '考务费',
  `jndsjb` decimal(8,2) DEFAULT NULL COMMENT '技能大赛奖补',
  `zjgkjb` decimal(8,2) DEFAULT NULL COMMENT '职教高考奖补',
  `qtjb` decimal(8,2) DEFAULT NULL COMMENT '其他奖补',
  `ylf` decimal(8,2) DEFAULT NULL COMMENT '医疗费',
  `hsbz` decimal(8,2) DEFAULT NULL COMMENT '伙食补助',
  `wwf` decimal(8,2) DEFAULT NULL COMMENT '慰问费',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='薪酬表';



# Dump of table gyjj_schedule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_schedule`;

CREATE TABLE `gyjj_schedule` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `XQJ` tinyint(1) NOT NULL DEFAULT '1' COMMENT '星期几',
  `XN` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学年',
  `XQ` tinyint(1) NOT NULL DEFAULT '1' COMMENT '学期',
  `BJMC` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '班级名称',
  `KCMC` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '课程名称',
  `XM` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '老师姓名',
  `JSZGH` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '老师职工号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='课程表';



# Dump of table gyjj_schedule_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_schedule_group`;

CREATE TABLE `gyjj_schedule_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `XN` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学年',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='课程学年表';



# Dump of table gyjj_scores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_scores`;

CREATE TABLE `gyjj_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `XN` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学年',
  `XQ` tinyint(1) NOT NULL DEFAULT '1' COMMENT '学期',
  `XM` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `KCMC` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '课程名称',
  `CJ` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '成绩',
  `KCXZ` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '课程性质',
  `XH` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='学生成绩表';



# Dump of table gyjj_selection
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_selection`;

CREATE TABLE `gyjj_selection` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '发布人uid',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `start_time` int unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `intro` varchar(200) NOT NULL DEFAULT '' COMMENT '介绍',
  `target` tinyint(1) NOT NULL DEFAULT '0' COMMENT '目标人群, 0全部, 1老师, 2学生',
  `rule_count` tinyint(1) NOT NULL DEFAULT '1' COMMENT '最多可选人数',
  `attend_count` int NOT NULL DEFAULT '0' COMMENT '参与人数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 2已结束, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='评比表';



# Dump of table gyjj_selection_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_selection_option`;

CREATE TABLE `gyjj_selection_option` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `selection_id` int NOT NULL DEFAULT '0' COMMENT '评比表id',
  `img_url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片地址',
  `value` varchar(500) NOT NULL DEFAULT '' COMMENT '选项内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0待审核, 1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `selection_id` (`selection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='评比选项表';



# Dump of table gyjj_selection_result
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_selection_result`;

CREATE TABLE `gyjj_selection_result` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '答题人id',
  `selection_id` int NOT NULL DEFAULT '0' COMMENT '评比表id',
  `option_id` int NOT NULL DEFAULT '0' COMMENT '选项id',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `selection_id` (`selection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='评比结果表';



# Dump of table gyjj_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_user`;

CREATE TABLE `gyjj_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `openid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信openid',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账号类型, 1老师2学生',
  `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学号/职工号, 根据type决定',
  `identity_card` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证号',
  `status` tinyint(1) DEFAULT NULL COMMENT '用户状态：1. 正常',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';



# Dump of table gyjj_video
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_video`;

CREATE TABLE `gyjj_video` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `vid` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '直播录制文件的唯一标识',
  `class_id` int NOT NULL DEFAULT '0' COMMENT '视频分类id',
  `media_url` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频地址url',
  `cover_url` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图url',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `upload_time` int unsigned NOT NULL DEFAULT '0' COMMENT '视频上传时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `class_id` (`class_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='腾讯云视频列表';



# Dump of table gyjj_video_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_video_category`;

CREATE TABLE `gyjj_video_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `class_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `class_id` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `level` int NOT NULL DEFAULT '0' COMMENT '层级',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常, 99删除',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `class_id` (`class_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='腾讯云视频分类表';



# Dump of table gyjj_xsksls
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_xsksls`;

CREATE TABLE `gyjj_xsksls` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `ksh` varchar(50) NOT NULL COMMENT '考生号',
  `xm` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `fjh` varchar(25) NOT NULL DEFAULT '' COMMENT '房间号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生考试临时表';



# Dump of table gyjj_xsxxb
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gyjj_xsxxb`;

CREATE TABLE `gyjj_xsxxb` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `XH` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '学号',
  `XM` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `SFZH` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='学生信息表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
