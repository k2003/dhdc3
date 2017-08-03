/*
Navicat MySQL Data Transfer

Source Server         : localhost3309
Source Server Version : 50548
Source Host           : localhost:3309
Source Database       : dhdc3

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-08-03 12:35:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dhdc_adhoc
-- ----------------------------
DROP TABLE IF EXISTS `dhdc_adhoc`;
CREATE TABLE `dhdc_adhoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `sql_sum` longtext,
  `desc_sum` varchar(255) DEFAULT NULL,
  `sql_indiv` longtext,
  `desc_indiv` varchar(255) DEFAULT NULL,
  `date_begin` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `note1` varchar(255) DEFAULT NULL,
  `note2` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dhdc_adhoc
-- ----------------------------
INSERT INTO `dhdc_adhoc` VALUES ('1', '1.1 รอยละของประชากรไทยอายุ 35-74 ป ไดรับการคัดกรองเบาหวาน โดยการตรวจวัดระดับน้ําตาลในเลือด', 'SELECT h.hoscode,h.hosname,tt.B,tt.A,ROUND(tt.A*100/tt.B,2) RATE FROM  (\r\nSELECT  t.HOSPCODE,COUNT(t.HOSPCODE) B , COUNT(if(t.`CHECK` is NULL ,NULL,t.HOSPCODE)) A from\r\n(\r\nSELECT p.HOSPCODE,p.PID,p.CID,concat(p.`NAME`,\' \',p.LNAME) NAME,p.SEX,p.age_y AGE,p.TYPEAREA \r\n,GROUP_CONCAT(a.BSLEVEL) BSLEVEL\r\n,GROUP_CONCAT(a.DATE_SERV) DATE_SERV\r\n,if(GROUP_CONCAT(a.BSLEVEL) is not NULL,\'Y\',NULL) \'CHECK\'\r\nFROM t_person_cid p\r\nLEFT JOIN t_ncdscreen a ON p.CID = a.CID AND a.DATE_SERV BETWEEN \'2016-10-01\' AND \'2017-09-30\'\r\nWHERE p.age_y BETWEEN 35 AND 74 AND  p.TYPEAREA IN (1,3)\r\nGROUP BY p.CID\r\n)  t  GROUP BY t.HOSPCODE\r\n) tt RIGHT JOIN chospital_amp h ON h.hoscode = tt.HOSPCODE', 'B=ประชากรไทยอายุ 35-74 ป , A=ได้รับการคัดกรอง', 'SELECT p.HOSPCODE,p.PID,p.CID,concat(p.`NAME`,\' \',p.LNAME) NAME,p.SEX,p.age_y AGE,p.TYPEAREA \r\n,GROUP_CONCAT(a.BSLEVEL) BSLEVEL\r\n,GROUP_CONCAT(a.DATE_SERV) DATE_SERV\r\n,if(GROUP_CONCAT(a.BSLEVEL) is not NULL,\'Y\',NULL) \'CHECK\'\r\nFROM t_person_cid p\r\nLEFT JOIN t_ncdscreen a ON p.CID = a.CID AND a.DATE_SERV BETWEEN \'2016-10-01\' AND \'2017-09-30\'\r\nWHERE p.age_y BETWEEN 35 AND 74 AND  p.TYPEAREA IN (1,3)\r\nGROUP BY p.CID\r\n', 'Y=ผลงาน', null, null, 'qof2560', null, null, '2017-08-01 11:55:19', '1', '2017-08-03 12:34:03', '1');
INSERT INTO `dhdc_adhoc` VALUES ('2', '1.2 รอยละของประชากรไทยอายุ 35-74 ป ที่ไดรับการคัดกรองและวินิจฉัยเปน เบาหวาน', 'SELECT h.hoscode,h.hosname,tt.B,tt.A,ROUND(tt.A*100/tt.B,2) RATE FROM (\r\nSELECT t.HOSPCODE,COUNT(t.HOSPCODE) B , COUNT(if(t.`CHECK` is NULL ,NULL,t.HOSPCODE)) A from\r\n(\r\nSELECT p.*,a.min_date_dx_dm DATE_DX_DM\r\n,if(a.min_date_dx_dm is null,NULL,\'Y\') \'CHECK\'\r\n from (\r\nSELECT p.HOSPCODE,p.PID,p.CID,concat(p.`NAME`,\' \',p.LNAME) NAME,p.SEX,p.age_y AGE,p.TYPEAREA \r\n,GROUP_CONCAT(a.BSLEVEL) BSLEVEL\r\n,GROUP_CONCAT(a.DATE_SERV) DATE_SCREEN\r\nFROM t_person_cid p\r\nINNER JOIN t_ncdscreen a ON p.CID = a.CID AND a.DATE_SERV BETWEEN \'2016-10-01\' AND \'2017-09-30\' AND a.BSLEVEL is not NULL\r\nWHERE p.age_y BETWEEN 35 AND 74 AND p.TYPEAREA IN (1,3)\r\nGROUP BY p.CID \r\n) p  LEFT JOIN t_dmht  a ON p.CID = a.cid AND a.min_date_dx_dm BETWEEN \'2016-10-01\' AND \'2017-09-30\'\r\n\r\n) t GROUP BY t.HOSPCODE\r\n) tt RIGHT JOIN chospital_amp h ON h.hoscode = tt.HOSPCODE', 'B=ประชากรไทยอายุ 35-74 ป ที่ไดรับการคัดกรอง , A=วินิจฉัยเปน เบาหวาน', 'SELECT p.*,a.min_date_dx_dm DATE_DX_DM\r\n,if(a.min_date_dx_dm is null,NULL,\'Y\') \'CHECK\'\r\n from (\r\nSELECT p.HOSPCODE,p.PID,p.CID,concat(p.`NAME`,\' \',p.LNAME) NAME,p.SEX,p.age_y AGE,p.TYPEAREA \r\n,GROUP_CONCAT(a.BSLEVEL) BSLEVEL\r\n,GROUP_CONCAT(a.DATE_SERV) DATE_SCREEN\r\nFROM t_person_cid p\r\nINNER JOIN t_ncdscreen a ON p.CID = a.CID AND a.DATE_SERV BETWEEN \'2016-10-01\' AND \'2017-09-30\' AND a.BSLEVEL is not NULL\r\nWHERE p.age_y BETWEEN 35 AND 74 AND p.TYPEAREA IN (1,3)\r\nGROUP BY p.CID \r\n) p  LEFT JOIN t_dmht  a ON p.CID = a.cid AND a.min_date_dx_dm BETWEEN \'2016-10-01\' AND \'2017-09-30\'', 'Y=ผลงาน', null, null, 'qof2560', null, null, '2017-08-03 11:24:31', '1', '2017-08-03 12:34:31', '1');

-- ----------------------------
-- Table structure for dhdc_adhoc_transform
-- ----------------------------
DROP TABLE IF EXISTS `dhdc_adhoc_transform`;
CREATE TABLE `dhdc_adhoc_transform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sql` longtext,
  `date_begin` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dhdc_adhoc_transform
-- ----------------------------
INSERT INTO `dhdc_adhoc_transform` VALUES ('2', 'select * from person limit 10;', null, null, '2017-08-01 13:45:19', '1', '2017-08-01 13:51:55', '1');
