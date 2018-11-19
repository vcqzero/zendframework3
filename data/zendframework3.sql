/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : zendframework3

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-11-19 17:31:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户登录名',
  `password` varchar(60) NOT NULL DEFAULT '' COMMENT '用户密码 60位',
  `realname` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(24) NOT NULL DEFAULT '' COMMENT '用户电话',
  `status` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('104', 'admin', '$2y$10$TwH89xXBgXVIYanoGZsz7.d28J38m74ZLqjT4ZqiG3ROMuN/PlA16', '', '', 'ENABLED', 'SUPER_USER');
