/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : 127.0.0.1:3306
Source Database       : zendframework3

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-11-11 22:52:57
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
  `workyard_id` int(255) NOT NULL,
  `realname` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(24) NOT NULL DEFAULT '' COMMENT '用户电话',
  `status` varchar(255) NOT NULL DEFAULT '',
  `email-bin` varchar(255) NOT NULL DEFAULT '',
  `initial_password_bin` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'root', '$2y$10$ppSI1IiDKMBCwoBkpsfXG.0GKHQqZVD4rElKUCj4DNRV3hmY89ODW', '0', '', '', 'ENABLED', '', '', 'SUPER_ADMIN');
INSERT INTO `users` VALUES ('79', 'qinchong', '$2y$10$xzKBQNHVC7kdt4zuOk6sDuyZ//lMtXR33gxF25Vm6f5/4h8zxP3Gi', '1', '秦崇', '13001030857', 'ENABLED', '', 'DnSmNx', 'WORKYARD_ADMIN');
