/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : hrdev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2019-10-21 17:10:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hr_vacation_type
-- ----------------------------
DROP TABLE IF EXISTS `hr_vacation_type`;
CREATE TABLE `hr_vacation_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vaca_code` varchar(255) NOT NULL COMMENT '假期種類編號（E：年假）',
  `vaca_name` varchar(255) NOT NULL COMMENT '假期種類名字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hr_vacation_type
-- ----------------------------
INSERT INTO `hr_vacation_type` VALUES ('1', 'E', '年假');
INSERT INTO `hr_vacation_type` VALUES ('2', 'A', '加班调休、特别调休');
INSERT INTO `hr_vacation_type` VALUES ('4', 'B', '婚假、丧假、护理假、产假、晚育假、哺乳假');
INSERT INTO `hr_vacation_type` VALUES ('5', 'C', '产前假、病假');
INSERT INTO `hr_vacation_type` VALUES ('6', 'D', '事假');
