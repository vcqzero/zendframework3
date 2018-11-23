/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : zendframework3

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-11-23 17:38:46
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
  `avatar` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('105', '阿德民', 'c', 'sd-test', 'sd', '', '', '');
INSERT INTO `users` VALUES ('106', '3', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('108', '3e', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('109', 'we', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('110', 'wt', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('111', 'twet', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('112', '23', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('113', 'gre', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('114', 'gg34', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('115', '34', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('116', '343', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('117', '34r', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('118', '34rt3', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('119', '34r3', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('120', '34r4', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('121', 'sfs', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('122', '34r34', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('124', '34r34dg', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('125', 'dfgfd', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('126', 'dfgdf', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('127', 'dfgd', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('128', 'dgd', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('129', 'dfgdfgd', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('130', 'dgfd', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('131', 'wf', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('133', 'we23', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('134', '23f', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('135', '2fg', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('136', 'wfw', '', '', '', '', '', '');
INSERT INTO `users` VALUES ('139', 'admin', '$2y$10$JZaWRbXEVKPeHabGQPqrtO0z7XsB6M28irJgc1nKjMExek4UBSaRy', '', '', 'ENABLED', 'SUPER_USER', '');
