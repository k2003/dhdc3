/*
Navicat MySQL Data Transfer

Source Server         : localhost3309
Source Server Version : 50548
Source Host           : localhost:3309
Source Database       : dhdc3

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-28 00:01:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES ('OnlyOwnHosRule', 0x4F3A33303A22636F6D706F6E656E74735C726261635C4F6E6C794F776E486F7352756C65223A333A7B733A343A226E616D65223B733A31343A224F6E6C794F776E486F7352756C65223B733A393A22637265617465644174223B693A313530313136373638323B733A393A22757064617465644174223B693A313530313136373638323B7D, '1501167682', '1501167682');

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('Admin', 'Backend');
INSERT INTO `auth_item_child` VALUES ('Admin', 'Pm');
INSERT INTO `auth_item_child` VALUES ('Pm', 'User');

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('Admin', '1', 'กลุ่มผู้ดูแลระบบคอมพิวเตอร์', null, null, '1497069734', '1497106664');
INSERT INTO `auth_item` VALUES ('Backend', '2', 'จัดการระบบ', null, null, '1497106549', '1497106549');
INSERT INTO `auth_item` VALUES ('OnlyOwnHos', '2', 'เข้าถึงได้เฉพาะข้อมูลตนเอง', 'OnlyOwnHosRule', null, '1501168247', '1501170173');
INSERT INTO `auth_item` VALUES ('Pm', '1', 'กลุ่มผู้รับผิดชอบงานระดับอำเภอ', null, null, '1497069765', '1497069765');
INSERT INTO `auth_item` VALUES ('User', '1', 'กลุ่มผู้รับผิดชอบงานระดับหน่วยบริการ', null, null, '1497069784', '1501168277');

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('Admin', '1', '1500914005');
INSERT INTO `auth_assignment` VALUES ('Pm', '2', '1497073672');
INSERT INTO `auth_assignment` VALUES ('User', '3', '1497073662');
INSERT INTO `auth_assignment` VALUES ('User', '4', '1501172941');
