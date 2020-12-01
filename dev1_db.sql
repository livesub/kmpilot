CREATE DATABASE IF NOT EXISTS `dev1_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dev1_db`;


DROP TABLE IF EXISTS `Board_Basic`;
CREATE TABLE IF NOT EXISTS `Board_Basic` (
  `BB_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `BB_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BB_email` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `BB_pass` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BB_title` varchar(70) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BB_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BB_wdate` int NOT NULL,
  `BB_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BB_view` int NOT NULL DEFAULT '0',
  `BB_ans_ori_id` int NOT NULL COMMENT '원글의 아이디값',
  `BB_ans_ord` int NOT NULL COMMENT '그룹(원글)내에서 순서',
  `BB_ans_depth` int NOT NULL COMMENT '게시글의 깊이',
  PRIMARY KEY (`BB_id`),
  KEY `BB_ans_ori_id` (`BB_ans_ori_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


