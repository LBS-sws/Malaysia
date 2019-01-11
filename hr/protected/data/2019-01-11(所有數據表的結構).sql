/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : hrdev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2019-01-11 16:08:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hr_agreement
-- ----------------------------
DROP TABLE IF EXISTS `hr_agreement`;
CREATE TABLE `hr_agreement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `docx_url` varchar(300) NOT NULL,
  `type` int(30) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `city` varchar(30) DEFAULT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='合同文檔';

-- ----------------------------
-- Table structure for hr_assess
-- ----------------------------
DROP TABLE IF EXISTS `hr_assess`;
CREATE TABLE `hr_assess` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `work_type` varchar(255) DEFAULT NULL COMMENT '工种',
  `service_effect` varchar(255) DEFAULT NULL COMMENT '服務效果',
  `service_process` varchar(255) DEFAULT NULL COMMENT '服务流程',
  `carefully` varchar(255) DEFAULT NULL COMMENT '細心度',
  `judge` varchar(255) DEFAULT NULL COMMENT '判斷力',
  `deal` varchar(255) DEFAULT NULL COMMENT '處理能力',
  `connects` varchar(255) DEFAULT NULL COMMENT '溝通能力',
  `obey` varchar(255) DEFAULT NULL COMMENT '服從度',
  `leadership` varchar(255) DEFAULT NULL COMMENT '領導力',
  `characters` text COMMENT '性格',
  `assess` text COMMENT '評估',
  `email_bool` int(2) DEFAULT '0' COMMENT '是否已經發送郵件0：無 1：有',
  `email_list` text,
  `staff_type` varchar(255) DEFAULT '3' COMMENT '工種',
  `overall_effect` varchar(255) DEFAULT NULL COMMENT '整體效果',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='員工評估表';

-- ----------------------------
-- Table structure for hr_attachment
-- ----------------------------
DROP TABLE IF EXISTS `hr_attachment`;
CREATE TABLE `hr_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path_url` varchar(255) NOT NULL COMMENT '附件地址',
  `file_name` varchar(255) NOT NULL COMMENT '附件名字',
  `lcu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='員工的附件表';

-- ----------------------------
-- Table structure for hr_audit_con
-- ----------------------------
DROP TABLE IF EXISTS `hr_audit_con`;
CREATE TABLE `hr_audit_con` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(100) NOT NULL,
  `audit_index` int(3) NOT NULL DEFAULT '3' COMMENT '1:一層  2：二層  3：三層',
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='加班、請假審核配置表';

-- ----------------------------
-- Table structure for hr_binding
-- ----------------------------
DROP TABLE IF EXISTS `hr_binding`;
CREATE TABLE `hr_binding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='員工綁定賬號';

-- ----------------------------
-- Table structure for hr_city
-- ----------------------------
DROP TABLE IF EXISTS `hr_city`;
CREATE TABLE `hr_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(20) NOT NULL,
  `z_index` int(2) NOT NULL DEFAULT '1' COMMENT '1:專業組  2：初階組',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='城市等級表（錦旗專用）';

-- ----------------------------
-- Table structure for hr_company
-- ----------------------------
DROP TABLE IF EXISTS `hr_company`;
CREATE TABLE `hr_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '公司名字',
  `agent` varchar(30) NOT NULL COMMENT '代理人',
  `agent_email` varchar(100) DEFAULT NULL,
  `head` varchar(30) NOT NULL COMMENT '負責人',
  `head_email` varchar(100) DEFAULT NULL,
  `legal` varchar(30) DEFAULT NULL COMMENT '法定負責人',
  `legal_email` varchar(100) DEFAULT NULL,
  `legal_city` varchar(100) DEFAULT NULL COMMENT '法人章所在城市',
  `address` varchar(255) NOT NULL COMMENT '公司地址',
  `postal` varchar(100) DEFAULT NULL COMMENT '郵政編碼',
  `address2` varchar(255) DEFAULT NULL,
  `postal2` varchar(255) DEFAULT NULL,
  `city` varchar(30) NOT NULL COMMENT '公司歸屬地區',
  `phone` varchar(255) DEFAULT NULL COMMENT '公司電話',
  `tacitly` varchar(11) DEFAULT '0' COMMENT '默認公司：0（否）1（是）',
  `organization_code` varchar(30) DEFAULT NULL COMMENT '組織機構代碼',
  `organization_time` varchar(60) DEFAULT NULL COMMENT '組織機構代碼發出時間',
  `security_code` varchar(30) DEFAULT NULL COMMENT '勞動保障代碼',
  `license_code` varchar(30) DEFAULT NULL COMMENT '證照編號',
  `license_time` varchar(60) DEFAULT NULL COMMENT '證照發出日期',
  `mie` varchar(10) DEFAULT NULL COMMENT '滅蟲執照級別',
  `taxpayer_num` varchar(100) DEFAULT NULL COMMENT '纳税人识别号',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='公司資料表';

-- ----------------------------
-- Table structure for hr_contract
-- ----------------------------
DROP TABLE IF EXISTS `hr_contract`;
CREATE TABLE `hr_contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='合同表';

-- ----------------------------
-- Table structure for hr_contract_docx
-- ----------------------------
DROP TABLE IF EXISTS `hr_contract_docx`;
CREATE TABLE `hr_contract_docx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) NOT NULL,
  `docx` int(10) NOT NULL,
  `index` int(10) DEFAULT NULL COMMENT '層級',
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT='合同與文檔的關連表';

-- ----------------------------
-- Table structure for hr_dept
-- ----------------------------
DROP TABLE IF EXISTS `hr_dept`;
CREATE TABLE `hr_dept` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `z_index` varchar(11) DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0:部門  1:職位',
  `city` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT '1' COMMENT '部門id',
  `dept_class` varchar(50) DEFAULT NULL COMMENT '職位類別',
  `manager` int(2) NOT NULL DEFAULT '0' COMMENT '0:不是經理  1：是經理',
  `technician` int(2) NOT NULL DEFAULT '0' COMMENT '0:不是技術員   1：技術員',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='工作部門';

-- ----------------------------
-- Table structure for hr_docx
-- ----------------------------
DROP TABLE IF EXISTS `hr_docx`;
CREATE TABLE `hr_docx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `docx_url` varchar(300) NOT NULL,
  `type` varchar(30) NOT NULL COMMENT '文檔可見類型（local：本地可見，default：全球可見）',
  `city` varchar(30) DEFAULT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='合同文檔';

-- ----------------------------
-- Table structure for hr_down_form
-- ----------------------------
DROP TABLE IF EXISTS `hr_down_form`;
CREATE TABLE `hr_down_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `docx_url` varchar(300) NOT NULL,
  `remark` text COMMENT '文檔說明',
  `city` varchar(30) DEFAULT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='合同文檔';

-- ----------------------------
-- Table structure for hr_employee
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee`;
CREATE TABLE `hr_employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '員工名字',
  `code` varchar(20) DEFAULT NULL COMMENT '員工編號',
  `sex` varchar(10) DEFAULT NULL,
  `city` varchar(20) NOT NULL,
  `staff_id` varchar(10) DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL COMMENT '公司id',
  `contract_id` int(10) unsigned NOT NULL COMMENT '合同id',
  `user_card` varchar(50) NOT NULL COMMENT '身份證號碼',
  `address` varchar(255) NOT NULL COMMENT '員工住址',
  `address_code` varchar(10) DEFAULT NULL COMMENT '郵政編碼',
  `contact_address` varchar(255) NOT NULL COMMENT '通訊地址',
  `contact_address_code` varchar(10) DEFAULT NULL COMMENT '郵政編碼',
  `phone` varchar(20) NOT NULL COMMENT '聯繫電話',
  `phone2` varchar(20) DEFAULT NULL COMMENT '緊急電話',
  `department` varchar(20) NOT NULL COMMENT '部門',
  `position` varchar(20) NOT NULL COMMENT '職位',
  `wage` varchar(20) DEFAULT NULL COMMENT '工資',
  `fix_time` varchar(11) NOT NULL DEFAULT 'fixation' COMMENT 'fixation：有固定期限  nofixed：無固定期限',
  `start_time` date NOT NULL COMMENT '合同開始時間',
  `end_time` varchar(50) DEFAULT NULL COMMENT '合同結束時間',
  `test_start_time` varchar(40) DEFAULT NULL COMMENT '試用期開始時間',
  `test_end_time` varchar(40) DEFAULT NULL COMMENT '試用期結束時間',
  `test_wage` varchar(20) DEFAULT NULL COMMENT '試用期工資',
  `test_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '試用期類型：0（無試用期）、 1（有試用期）',
  `test_length` varchar(10) DEFAULT NULL,
  `word_status` int(10) NOT NULL DEFAULT '0' COMMENT '是否已經生成合同：0（沒有）、1（有）',
  `word_url` varchar(300) DEFAULT NULL COMMENT '員工合同的地址',
  `staff_old_status` int(11) NOT NULL DEFAULT '1' COMMENT '員工的前一個狀態',
  `staff_status` int(11) NOT NULL DEFAULT '0' COMMENT '員工狀態：0（已經入職）',
  `entry_time` varchar(40) DEFAULT '2017-08-01' COMMENT '入職時間',
  `age` varchar(11) DEFAULT NULL COMMENT '年齡',
  `birth_time` varchar(40) DEFAULT NULL COMMENT '出生日期',
  `ld_card` varchar(40) DEFAULT NULL COMMENT '勞動保障卡號',
  `sb_card` varchar(40) DEFAULT NULL COMMENT '社保卡號',
  `jj_card` varchar(40) DEFAULT NULL COMMENT '公積金卡號',
  `house_type` varchar(20) DEFAULT NULL COMMENT '戶籍類型',
  `health` varchar(100) DEFAULT NULL COMMENT '身體狀態',
  `education` varchar(100) DEFAULT NULL COMMENT '學歷',
  `experience` varchar(100) DEFAULT NULL COMMENT '工作經驗',
  `english` text COMMENT '外語水平',
  `technology` text COMMENT '技術水平',
  `other` text COMMENT '其它',
  `year_day` varchar(11) DEFAULT NULL COMMENT '年假',
  `email` varchar(50) DEFAULT NULL COMMENT '員工郵箱',
  `ject_remark` text COMMENT '拒絕備註',
  `remark` text COMMENT '備註',
  `price1` varchar(20) DEFAULT NULL COMMENT '每月工資',
  `price2` varchar(20) DEFAULT NULL COMMENT '加班工資',
  `price3` varchar(255) DEFAULT NULL COMMENT '每月補貼',
  `image_user` varchar(255) DEFAULT NULL COMMENT '員工照片地址',
  `image_code` varchar(255) DEFAULT NULL COMMENT '員工身份證照片',
  `image_work` varchar(255) DEFAULT NULL COMMENT '工作證明照片',
  `image_other` varchar(255) DEFAULT NULL COMMENT '其它照片',
  `staff_type` varchar(50) DEFAULT NULL,
  `staff_leader` varchar(50) DEFAULT NULL,
  `attachment` text COMMENT '附件',
  `nation` varchar(100) DEFAULT NULL COMMENT '民族',
  `household` varchar(50) DEFAULT NULL COMMENT '户籍类型',
  `empoyment_code` varchar(100) DEFAULT NULL COMMENT '就业登记证号',
  `social_code` varchar(100) DEFAULT NULL COMMENT '社会保障卡号',
  `leave_time` varchar(255) DEFAULT NULL,
  `leave_reason` text,
  `user_card_date` varchar(100) DEFAULT NULL COMMENT '身份證號碼期限',
  `emergency_user` varchar(255) DEFAULT NULL COMMENT '緊急聯繫人',
  `emergency_phone` varchar(255) DEFAULT NULL COMMENT '緊急聯繫人電話',
  `code_old` varchar(255) DEFAULT NULL COMMENT '員工編號（旧)',
  `z_index` int(2) NOT NULL DEFAULT '1',
  `signed_bool` int(2) DEFAULT '0' COMMENT '是否發送簽約提示郵件 0：無  1：有',
  `lcu` varchar(20) DEFAULT NULL,
  `luu` varchar(20) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='員工表';

-- ----------------------------
-- Table structure for hr_employee_history
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_history`;
CREATE TABLE `hr_employee_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL COMMENT '員工id',
  `history_id` varchar(10) DEFAULT NULL COMMENT '歷史id',
  `status` varchar(255) NOT NULL COMMENT '操作',
  `num` varchar(100) DEFAULT NULL COMMENT '續約次數',
  `remark` varchar(255) DEFAULT NULL COMMENT '備註',
  `lcu` varchar(255) DEFAULT NULL COMMENT '操作人',
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作時間',
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=343 DEFAULT CHARSET=utf8 COMMENT='員工被操作的歷史';

-- ----------------------------
-- Table structure for hr_employee_leave
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_leave`;
CREATE TABLE `hr_employee_leave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leave_code` varchar(255) DEFAULT NULL COMMENT '請假編號',
  `employee_id` varchar(200) NOT NULL COMMENT '員工id',
  `vacation_id` int(10) NOT NULL DEFAULT '0' COMMENT '請假類型的id',
  `leave_cause` text COMMENT '請假原因',
  `start_time` datetime DEFAULT NULL COMMENT '請假開始時間',
  `start_time_lg` varchar(10) DEFAULT 'AM',
  `end_time` datetime DEFAULT NULL COMMENT '請假結束時間',
  `end_time_lg` varchar(10) DEFAULT 'PM',
  `log_time` float(5,1) DEFAULT NULL COMMENT '請假總時長',
  `leave_cost` float(10,2) DEFAULT NULL COMMENT '請假費用',
  `z_index` int(10) DEFAULT '1' COMMENT '審核層級（1:部門審核、2：主管、3：總監、4：你）',
  `status` int(10) DEFAULT '0' COMMENT '審核的狀態(0:草稿、1：審核、2：審核通過、3：拒絕、4：完成）',
  `user_lcu` varchar(255) DEFAULT NULL,
  `user_lcd` varchar(255) DEFAULT NULL,
  `area_lcu` varchar(255) DEFAULT NULL,
  `area_lcd` varchar(255) DEFAULT NULL,
  `head_lcu` varchar(255) DEFAULT NULL,
  `head_lcd` varchar(255) DEFAULT NULL,
  `you_lcu` varchar(255) DEFAULT NULL,
  `you_lcd` varchar(255) DEFAULT NULL,
  `audit_remark` text,
  `reject_cause` text COMMENT '拒絕原因',
  `auto_cost` int(2) DEFAULT '1' COMMENT '費用是否自動計算（0：否、 1：是）',
  `city` varchar(255) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='員工請假表';

-- ----------------------------
-- Table structure for hr_employee_operate
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_operate`;
CREATE TABLE `hr_employee_operate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '員工名字',
  `code` varchar(20) DEFAULT NULL COMMENT '員工編號',
  `sex` varchar(10) DEFAULT NULL,
  `city` varchar(20) NOT NULL,
  `staff_id` varchar(10) DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL COMMENT '公司id',
  `contract_id` int(10) unsigned NOT NULL COMMENT '合同id',
  `user_card` varchar(50) NOT NULL COMMENT '身份證號碼',
  `address` varchar(255) NOT NULL COMMENT '員工住址',
  `address_code` varchar(10) DEFAULT NULL COMMENT '郵政編碼',
  `contact_address` varchar(255) NOT NULL COMMENT '通訊地址',
  `contact_address_code` varchar(10) DEFAULT NULL COMMENT '郵政編碼',
  `phone` varchar(20) NOT NULL COMMENT '聯繫電話',
  `phone2` varchar(20) DEFAULT NULL COMMENT '緊急電話',
  `department` varchar(20) NOT NULL COMMENT '部門',
  `position` varchar(20) NOT NULL COMMENT '職位',
  `wage` int(20) unsigned NOT NULL COMMENT '工資',
  `fix_time` varchar(10) NOT NULL DEFAULT 'fixation',
  `start_time` date NOT NULL COMMENT '合同開始時間',
  `end_time` varchar(100) DEFAULT NULL COMMENT '合同結束時間',
  `test_start_time` varchar(20) DEFAULT NULL COMMENT '試用期開始時間',
  `test_end_time` varchar(20) DEFAULT NULL COMMENT '試用期結束時間',
  `test_wage` varchar(20) DEFAULT NULL COMMENT '試用期工資',
  `test_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '試用期類型：0（無試用期）、 1（有試用期）',
  `test_length` varchar(10) DEFAULT NULL,
  `word_status` int(10) NOT NULL DEFAULT '0' COMMENT '是否已經生成合同：0（沒有）、1（有）',
  `word_url` varchar(300) DEFAULT NULL COMMENT '員工合同的地址',
  `staff_old_status` int(11) NOT NULL DEFAULT '1' COMMENT '員工的前一個狀態',
  `operation` varchar(255) NOT NULL DEFAULT 'update' COMMENT '操作原因',
  `opr_type` varchar(255) DEFAULT NULL COMMENT '合同變更類型',
  `finish` int(10) NOT NULL DEFAULT '0' COMMENT '是否完成。1：是，0：否',
  `staff_status` int(11) NOT NULL DEFAULT '0' COMMENT '員工狀態：0（已經入職）',
  `entry_time` varchar(20) DEFAULT '2017-08-01' COMMENT '入職時間',
  `age` varchar(11) DEFAULT NULL COMMENT '年齡',
  `birth_time` varchar(20) DEFAULT NULL COMMENT '出生日期',
  `ld_card` varchar(40) DEFAULT NULL COMMENT '勞動保障卡號',
  `sb_card` varchar(40) DEFAULT NULL COMMENT '社保卡號',
  `jj_card` varchar(40) DEFAULT NULL COMMENT '公積金卡號',
  `house_type` varchar(20) DEFAULT NULL COMMENT '戶籍類型',
  `health` varchar(100) DEFAULT NULL COMMENT '身體狀態',
  `education` varchar(100) DEFAULT NULL COMMENT '學歷',
  `experience` varchar(100) DEFAULT NULL COMMENT '工作經驗',
  `english` text COMMENT '外語水平',
  `technology` text COMMENT '技術水平',
  `other` text COMMENT '其它',
  `year_day` varchar(11) DEFAULT NULL COMMENT '年假',
  `email` varchar(50) DEFAULT NULL COMMENT '員工郵箱',
  `ject_remark` text COMMENT '拒絕備註',
  `remark` text COMMENT '備註',
  `update_remark` text COMMENT '變更說明',
  `price1` varchar(20) DEFAULT NULL COMMENT '每月工資',
  `price2` varchar(20) DEFAULT NULL COMMENT '加班工資',
  `price3` varchar(255) DEFAULT NULL COMMENT '每月補貼',
  `image_user` varchar(255) DEFAULT NULL COMMENT '員工照片地址',
  `image_code` varchar(255) DEFAULT NULL COMMENT '員工身份證照片',
  `image_work` varchar(255) DEFAULT NULL COMMENT '工作證明照片',
  `image_other` varchar(255) DEFAULT NULL COMMENT '其它照片',
  `staff_type` varchar(50) DEFAULT NULL,
  `staff_leader` varchar(50) DEFAULT NULL,
  `attachment` text COMMENT '附件',
  `nation` varchar(100) DEFAULT NULL,
  `household` varchar(50) DEFAULT NULL,
  `empoyment_code` varchar(100) DEFAULT NULL,
  `social_code` varchar(100) DEFAULT NULL,
  `leave_time` varchar(100) DEFAULT NULL COMMENT '離職時間',
  `leave_reason` text COMMENT '離職原因',
  `user_card_date` varchar(100) DEFAULT NULL,
  `emergency_user` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(255) DEFAULT NULL,
  `change_city` varchar(255) DEFAULT NULL COMMENT '調職城市',
  `code_old` varchar(255) DEFAULT NULL,
  `z_index` int(11) NOT NULL DEFAULT '1',
  `signed_bool` int(2) DEFAULT '1',
  `effect_time` date DEFAULT NULL COMMENT '生效日期',
  `lcu` varchar(20) DEFAULT NULL,
  `luu` varchar(20) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='員工表';

-- ----------------------------
-- Table structure for hr_employee_reward
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_reward`;
CREATE TABLE `hr_employee_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_code` varchar(100) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `reward_name` varchar(255) NOT NULL,
  `reward_money` varchar(255) DEFAULT NULL,
  `reward_goods` varchar(255) DEFAULT NULL,
  `remark` text,
  `reject_remark` text,
  `status` int(10) NOT NULL DEFAULT '0',
  `city` varchar(255) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='員工獲獎列表';

-- ----------------------------
-- Table structure for hr_employee_wages
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_wages`;
CREATE TABLE `hr_employee_wages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(40) DEFAULT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `wages_arr` text,
  `wages_date` date DEFAULT NULL,
  `wages_status` int(11) NOT NULL DEFAULT '0' COMMENT '0:草稿  1：發送 2：拒絕 3：完成',
  `just_remark` varchar(255) DEFAULT NULL,
  `apply_time` date DEFAULT NULL COMMENT '申請時間',
  `sum` varchar(50) DEFAULT NULL COMMENT '實際發放工資',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='員工的工資表';

-- ----------------------------
-- Table structure for hr_employee_word_info
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_word_info`;
CREATE TABLE `hr_employee_word_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `work_id` int(11) NOT NULL COMMENT '加班單id',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='員工加班額外擴充的時間表';

-- ----------------------------
-- Table structure for hr_employee_work
-- ----------------------------
DROP TABLE IF EXISTS `hr_employee_work`;
CREATE TABLE `hr_employee_work` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `work_code` varchar(255) DEFAULT NULL COMMENT '加班編號',
  `employee_id` varchar(200) NOT NULL COMMENT '員工id',
  `work_type` int(10) NOT NULL DEFAULT '0' COMMENT '加班類型',
  `work_cause` text COMMENT '加班原因',
  `work_address` varchar(255) DEFAULT NULL COMMENT '加班地點',
  `start_time` datetime DEFAULT NULL COMMENT '加班開始時間',
  `end_time` datetime DEFAULT NULL COMMENT '加班結束時間',
  `log_time` float(10,1) DEFAULT NULL COMMENT '加班總時長',
  `work_cost` float(10,2) DEFAULT NULL COMMENT '加班費用',
  `z_index` int(10) DEFAULT '1' COMMENT '審核層級（1:部門審核、2：主管、3：總監、4：你）',
  `status` int(10) DEFAULT '0' COMMENT '審核的狀態(0:草稿、1：審核、2：審核通過、3：拒絕、4：完成）',
  `user_lcu` varchar(255) DEFAULT NULL,
  `user_lcd` varchar(255) DEFAULT NULL,
  `area_lcu` varchar(255) DEFAULT NULL,
  `area_lcd` varchar(255) DEFAULT NULL,
  `head_lcu` varchar(255) DEFAULT NULL,
  `head_lcd` varchar(255) DEFAULT NULL,
  `you_lcu` varchar(255) DEFAULT NULL,
  `you_lcd` varchar(255) DEFAULT NULL,
  `reject_cause` text COMMENT '拒絕原因',
  `auto_cost` int(2) DEFAULT '1' COMMENT '費用是否自動計算（0：否、 1：是）',
  `audit_remark` text COMMENT '審核備註',
  `city` varchar(255) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='員工加班表（新）';

-- ----------------------------
-- Table structure for hr_fete
-- ----------------------------
DROP TABLE IF EXISTS `hr_fete`;
CREATE TABLE `hr_fete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '節假日名字',
  `start_time` date DEFAULT NULL,
  `end_time` date DEFAULT NULL,
  `log_time` int(11) DEFAULT NULL COMMENT '假日天數',
  `cost_num` int(11) DEFAULT '0' COMMENT '費用倍率（0：兩倍工資、1：三倍工資）',
  `city` varchar(255) DEFAULT NULL,
  `only` varchar(255) DEFAULT 'local',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='節假日配置';

-- ----------------------------
-- Table structure for hr_holiday
-- ----------------------------
DROP TABLE IF EXISTS `hr_holiday`;
CREATE TABLE `hr_holiday` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `z_index` varchar(50) DEFAULT NULL,
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '0：假期 1：加班',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='假期配置表';

-- ----------------------------
-- Table structure for hr_prize
-- ----------------------------
DROP TABLE IF EXISTS `hr_prize`;
CREATE TABLE `hr_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prize_date` date DEFAULT NULL COMMENT '嘉许日期',
  `prize_num` int(5) DEFAULT NULL COMMENT '参与人数',
  `employee_id` int(11) NOT NULL,
  `prize_pro` varchar(50) DEFAULT NULL COMMENT '嘉许项目',
  `customer_name` varchar(100) DEFAULT NULL COMMENT '客戶名稱',
  `contact` varchar(50) DEFAULT NULL COMMENT '聯繫人',
  `phone` varchar(50) DEFAULT NULL COMMENT '聯繫人電話',
  `posi` varchar(100) DEFAULT NULL COMMENT '聯繫人職務',
  `photo1` varchar(255) DEFAULT NULL COMMENT '表揚信（獨照）',
  `photo2` varchar(255) DEFAULT NULL COMMENT '與客戶合照',
  `prize_type` int(2) NOT NULL DEFAULT '0' COMMENT '0：表揚信  1：錦旗',
  `type_num` int(11) NOT NULL DEFAULT '0' COMMENT '錦旗數量',
  `status` int(5) DEFAULT '0' COMMENT '0:草稿  1：發送  2：拒絕  3：完成',
  `remark` text COMMENT '備註',
  `reject_remark` text COMMENT '拒絕原因',
  `city` varchar(100) DEFAULT NULL,
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='錦旗表';

-- ----------------------------
-- Table structure for hr_queue
-- ----------------------------
DROP TABLE IF EXISTS `hr_queue`;
CREATE TABLE `hr_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rpt_desc` varchar(250) NOT NULL,
  `req_dt` datetime DEFAULT NULL,
  `fin_dt` datetime DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  `rpt_type` varchar(10) NOT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rpt_content` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_queue_param
-- ----------------------------
DROP TABLE IF EXISTS `hr_queue_param`;
CREATE TABLE `hr_queue_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `param_field` varchar(50) NOT NULL,
  `param_value` varchar(500) DEFAULT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_queue_user
-- ----------------------------
DROP TABLE IF EXISTS `hr_queue_user`;
CREATE TABLE `hr_queue_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_reward
-- ----------------------------
DROP TABLE IF EXISTS `hr_reward`;
CREATE TABLE `hr_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '獎勵名字',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '獎勵類型：0（僅獎金）、1（僅物品）、2（獎金加物品）',
  `money` varchar(100) DEFAULT NULL COMMENT '獎金',
  `goods` varchar(255) DEFAULT NULL COMMENT '獎勵物品',
  `city` varchar(100) DEFAULT NULL,
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='獎勵表';

-- ----------------------------
-- Table structure for hr_staff
-- ----------------------------
DROP TABLE IF EXISTS `hr_staff`;
CREATE TABLE `hr_staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(15) DEFAULT NULL,
  `name` varchar(250) NOT NULL COMMENT '用戶名',
  `position` varchar(250) DEFAULT NULL COMMENT '崗位',
  `staff_type` varchar(15) DEFAULT NULL COMMENT '員工類別',
  `leader` varchar(15) DEFAULT NULL COMMENT '队长/组长',
  `join_dt` datetime DEFAULT NULL COMMENT '入职日期 ',
  `ctrt_start_dt` datetime DEFAULT NULL COMMENT '合同开始日期',
  `ctrt_period` tinyint(4) DEFAULT '0' COMMENT '合同簽訂年限',
  `ctrt_renew_dt` datetime DEFAULT NULL COMMENT '合同续签日期',
  `email` varchar(255) DEFAULT NULL COMMENT '郵箱',
  `leave_dt` datetime DEFAULT NULL COMMENT '离职/调职日期',
  `leave_reason` varchar(1000) DEFAULT NULL COMMENT '離職原因',
  `remarks` varchar(1000) DEFAULT NULL COMMENT '離職備註',
  `city` char(5) NOT NULL COMMENT '城市',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_staff_copy
-- ----------------------------
DROP TABLE IF EXISTS `hr_staff_copy`;
CREATE TABLE `hr_staff_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(15) DEFAULT NULL,
  `name` varchar(250) NOT NULL COMMENT '用戶名',
  `position` varchar(250) DEFAULT NULL COMMENT '崗位',
  `staff_type` varchar(15) DEFAULT NULL COMMENT '員工類別',
  `leader` varchar(15) DEFAULT NULL COMMENT '队长/组长',
  `join_dt` datetime DEFAULT NULL COMMENT '入职日期 ',
  `ctrt_start_dt` datetime DEFAULT NULL COMMENT '合同开始日期',
  `ctrt_period` tinyint(4) DEFAULT '0' COMMENT '合同簽訂年限',
  `contract_img` varchar(1000) DEFAULT NULL COMMENT '合同圖片',
  `change_img` varchar(1000) DEFAULT NULL COMMENT '變更之後的合同圖片',
  `change_type` varchar(20) DEFAULT NULL COMMENT '合同變更類型',
  `email` varchar(255) DEFAULT NULL COMMENT '郵箱',
  `change_time` datetime DEFAULT NULL COMMENT '變更日期',
  `change_reason` varchar(1000) DEFAULT NULL COMMENT '變更原因',
  `remarks` varchar(1000) DEFAULT NULL COMMENT '離職備註',
  `city` char(5) NOT NULL COMMENT '城市',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_staff_year
-- ----------------------------
DROP TABLE IF EXISTS `hr_staff_year`;
CREATE TABLE `hr_staff_year` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `year` int(11) NOT NULL COMMENT '年',
  `add_num` float(10,1) NOT NULL COMMENT '年假累積的天數',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hr_vacation
-- ----------------------------
DROP TABLE IF EXISTS `hr_vacation`;
CREATE TABLE `hr_vacation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `vaca_type` varchar(10) NOT NULL DEFAULT 'A' COMMENT '假期種類',
  `log_bool` int(11) DEFAULT '0' COMMENT '是否有最大天數限制 0:無 1：有',
  `max_log` int(11) DEFAULT NULL COMMENT '最大天數限制',
  `sub_bool` int(11) DEFAULT '0' COMMENT '是否扣減工資  0：否  1：是',
  `sub_multiple` int(11) DEFAULT '0' COMMENT '扣減倍數（0-100）%',
  `city` varchar(255) DEFAULT NULL,
  `only` varchar(100) DEFAULT 'local',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT NULL,
  `lud` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='請假配置表';

-- ----------------------------
-- Table structure for hr_wages
-- ----------------------------
DROP TABLE IF EXISTS `hr_wages`;
CREATE TABLE `hr_wages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wages_name` varchar(30) NOT NULL COMMENT '工資組成名稱',
  `city` varchar(30) NOT NULL COMMENT '所在城市',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='工資配置表';

-- ----------------------------
-- Table structure for hr_wages_con
-- ----------------------------
DROP TABLE IF EXISTS `hr_wages_con`;
CREATE TABLE `hr_wages_con` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wages_id` int(10) unsigned NOT NULL COMMENT '工資id',
  `type_name` varchar(30) NOT NULL COMMENT '屬性名稱',
  `compute` int(11) NOT NULL DEFAULT '0' COMMENT '計算方式：0（固定工資）、1（每小時工資）、2（提成百分比）',
  `z_index` int(11) NOT NULL DEFAULT '0' COMMENT '層級',
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='工資組合表(配置)';
