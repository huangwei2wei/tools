-- phpMyAdmin SQL Dump
-- version 4.1.0-alpha2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2013-12-24 16:06:56
-- 服务器版本： 5.1.67
-- PHP Version: 5.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `server_list`
--

-- --------------------------------------------------------

--
-- 表的结构 `operators`
--

CREATE TABLE IF NOT EXISTS `operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL COMMENT '运营商名字',
  `company` varchar(300) NOT NULL COMMENT '公司名称',
  `operators` varchar(60) NOT NULL COMMENT '运营商登录名',
  `purview` varchar(500) NOT NULL COMMENT '权限码',
  `identifier` varchar(64) NOT NULL COMMENT '运营商识别码',
  `platform` varchar(60) NOT NULL COMMENT '平台地址无 WWW',
  `pay` varchar(300) NOT NULL COMMENT '充值地址',
  `login` varchar(100) NOT NULL COMMENT '平台登录地址',
  `condition` tinyint(1) NOT NULL DEFAULT '0' COMMENT '条件分成模式  0否 1是',
  `sharing_ratio` double NOT NULL COMMENT '分成比例',
  `sharing_ratio_c` varchar(300) NOT NULL COMMENT '条件分成比例格式 小于值:1:分成比率,小于值:1:分成比率',
  `boss` bigint(16) NOT NULL COMMENT '运营商老板联系电话',
  `boss_qq` bigint(20) NOT NULL COMMENT '老板QQ',
  `boss_email` varchar(60) NOT NULL COMMENT '老板邮箱',
  `product_manager` bigint(16) NOT NULL COMMENT '运营总监联系电话',
  `product_manager_qq` bigint(20) NOT NULL COMMENT '运营总监QQ',
  `product_manager_mail` varchar(60) NOT NULL COMMENT '运营总监邮箱',
  `technical_director` bigint(16) NOT NULL COMMENT '技术总监联系电话',
  `technical_director_qq` bigint(20) NOT NULL COMMENT '技术总监QQ',
  `technical_director_email` varchar(60) NOT NULL COMMENT '技术总监邮箱',
  `server_mongo_ip` varchar(300) NOT NULL COMMENT 'mongodb服务器IP',
  `server_mongo_port` int(11) NOT NULL COMMENT 'mongodb服务器端口',
  `mongo_username` varchar(300) NOT NULL COMMENT 'mongodb库用户名',
  `mongo_password` varchar(300) NOT NULL COMMENT 'mongodb库密码',
  `default_mysql_db` varchar(30) NOT NULL COMMENT '默认数据库',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='运营商' AUTO_INCREMENT=201 ;

--
-- 转存表中的数据 `operators`
--

INSERT INTO `operators` (`id`, `name`, `company`, `operators`, `purview`, `identifier`, `platform`, `pay`, `login`, `condition`, `sharing_ratio`, `sharing_ratio_c`, `boss`, `boss_qq`, `boss_email`, `product_manager`, `product_manager_qq`, `product_manager_mail`, `technical_director`, `technical_director_qq`, `technical_director_email`, `server_mongo_ip`, `server_mongo_port`, `mongo_username`, `mongo_password`, `default_mysql_db`) VALUES
(1, '纤尚科技', '纤尚科技', '一将成名', '{"admin":{"purview":"0","pwd":"19856fb7f70222ab3dfbcfefe9bbf678"}}', '21232f297a57a5a743894a0e4a801fc3', 'zzql.com', 'PLATFORM."/recharge?gid=".CHANNEL."&sid=".SERVER', '', 0, 0.5, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '192.168.1.119', 8000, 'game', '123456', 'game2'),
(2, '纤尚科技', '纤尚科技', '三国X魂', '{"acxfexyf":{"purview":0},"admin":{"purview":"0","pwd":"19856fb7f70222ab3dfbcfefe9bbf678"}}', '21232f297a57a5a743894a0e4a801fc4', 'zzql.com', 'PLATFORM."/recharge?gid=".CHANNEL."&sid=".SERVER', '', 0, 0.5, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '192.168.1.119', 8000, 'game', '123456', 'game3'),
(3, 'qq', '腾讯', 'qq', '{"admin":{"purview":"0","pwd":"19856fb7f70222ab3dfbcfefe9bbf678"}}', '21232f297a57a5a743894a0e4a80grc2', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '183.60.118.143', 86, 'yjcmgame', 'Vp7uhcHH2bXYCBMF', 'game2'),
(4, 'qq1', '腾讯', 'qq1', '{"admin":{"purview":"0","pwd":"19856fb7f70222ab3dfbcfefe9bbf678"}}', '21232f297a57a5a743894a0e4a80grc5', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '183.60.118.143', 86, 'yjcmgame', 'Vp7uhcHH2bXYCBMF', 'game3'),
(5, '', '', '', '', '21232f297a57a5a743894a0e4a80grc6', '', '', '', 0, 0, '', 0, 0, '', 0, 0, 'r', 0, 0, '', '', 0, '', '', ''),
(6, '', '', '', '', '21232f297a57a5a743894a0e4a80grc7', '', '', '', 2, 2, '', 0, 2, '', 2, 2, '2', 2, 2, '2', '', 0, '', '', ''),
(7, '', '', '', '', '21232f297a57a5a743894a0e4a80grc8', '', '', '', 1, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(8, '', '', '', '', '21232f297a57a5a743894a0e4a80grc9', '', '', '', 0, 0, '', 0, 0, '', 0, 0, 'q', 0, 0, 'q', '', 0, '', '', ''),
(9, '', '', '', '', '21232f297a57a5a743894a0e4a80grc10', '', '', '', 0, 0, '', 0, 0, '', 0, 0, 'p6', 0, 0, '', '', 0, '', '', ''),
(10, '', '', '', '', '21232f297a57a5a743894a0e4a80grc11', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(11, '', '', '', '', '21232f297a57a5a743894a0e4a80grc12', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(12, '', '', '', '', '21232f297a57a5a743894a0e4a80grc13', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(13, '', '', '', '', '21232f297a57a5a743894a0e4a80grc14', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(14, '', '', '', '', '21232f297a57a5a743894a0e4a80grc15', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(15, '', '', '', '', '21232f297a57a5a743894a0e4a80grc16', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(16, '', '', '', '', '21232f297a57a5a743894a0e4a80grc17', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(17, '', '', '', '', '21232f297a57a5a743894a0e4a80grc18', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(18, '', '', '', '', '21232f297a57a5a743894a0e4a80grc19', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(19, '', '', '', '', '21232f297a57a5a743894a0e4a80grc20', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(35, '', '', '', '', '21232f297a57a5a743894a0e4a80grc78', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(36, '', '', '', '', '21232f297a57a5a743894a0e4a80grc21', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(30, '', '', '', '', '21232f297a57a5a743894a0e4a801fc22', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(31, '', '', '', '', '21232f297a57a5a743894a0e4a801fc23', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(32, '', '', '', '', '21232f297a57a5a743894a0e4a801fc24', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(33, '', '', '', '', '21232f297a57a5a743894a0e4a801fc25', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(34, '', '', '', '', '21232f297a57a5a743894a0e4a801fc27', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(20, '', '', '', '', '21232f297a57a5a743894a0e4a801fc26', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(21, '', '', '', '', '21232f297a57a5a743894a0e4a801fc28', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(22, '', '', '', '', '21232f297a57a5a743894a0e4a801fc29', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(23, '', '', '', '', '21232f297a57a5a743894a0e4a801fc30', '', '', '', 0, 0.5, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(24, '', '', '', '', '21232f297a57a5a743894a0e4a801fc31', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(25, '', '', '', '', '21232f297a57a5a743894a0e4a801fc32', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(26, '', '', '', '', '21232f297a57a5a743894a0e4a801fc33', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(27, '', '', '', '', '21232f297a57a5a743894a0e4a801fc34', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(28, '', '', '', '', '21232f297a57a5a743894a0e4a801fc35', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(29, '', '', '', '', '21232f297a57a5a743894a0e4a801fc36', 'zzql.com', '', '', 0, 0.5, '', 13711712356, 179893511, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', '', 0, '', '', ''),
(37, '', '', '', '', '21232f297a57a5a743894a0e4a80grc537', '', '', '', 0, 0, '', 0, 179893511, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(38, '', '', '', '', '21232f297a57a5a743894a0e4a80grc38', '', '', '', 0, 0, '', 0, 179893511, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(39, '', '', '', '', '21232f297a57a5a743894a0e4a801fc39', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(40, '', '', '', '', '21232f297a57a5a743894a0e4a801fc40', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(41, '', '', '', '', '21232f297a57a5a743894a0e4a801fc41', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(42, '', '', '', '', '21232f297a57a5a743894a0e4a801fc42', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(43, '', '', '', '', '21232f297a57a5a743894a0e4a801fc43', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(44, '', '', '', '', '21232f297a57a5a743894a0e4a801fc44', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(45, '', '', '', '', '21232f297a57a5a743894a0e4a801fc45', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(46, '', '', '', '', '21232f297a57a5a743894a0e4a801fc46', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(47, '', '', '', '', '21232f297a57a5a743894a0e4a801fc47', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(48, '', '', '', '', '21232f297a57a5a743894a0e4a801fc48', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(49, '', '', '', '', '21232f297a57a5a743894a0e4a801fc49', 'zzql.com', '', '', 0, 0, '', 12345678911, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(50, '', '', '', '', '21232f297a57a5a743894a0e4a801fc50', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(51, '', '', '', '', '21232f297a57a5a743894a0e4a801fc51', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(52, '', '', '', '', '21232f297a57a5a743894a0e4a801fc52', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(53, '', '', '', '', '21232f297a57a5a743894a0e4a801fc53', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(54, '', '', '', '', '21232f297a57a5a743894a0e4a801fc54', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(55, '', '', '', '', '21232f297a57a5a743894a0e4a801fc55', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(56, '', '', '', '', '21232f297a57a5a743894a0e4a801fc56', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(57, '', '', '', '', '21232f297a57a5a743894a0e4a801fc57', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(58, '', '', '', '', '21232f297a57a5a743894a0e4a801fc58', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(59, '', '', '', '', '21232f297a57a5a743894a0e4a801fc59', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(60, '', '', '', '', '21232f297a57a5a743894a0e4a801fc60', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(61, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc61', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(62, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc62', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(63, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc63', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(64, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc64', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(65, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc65', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(66, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc66', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(67, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc67', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(68, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc68', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(69, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc69', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(70, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc70', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(71, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc71', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(72, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc72', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(73, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc73', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(74, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc74', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(75, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc75', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(76, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc76', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', ''),
(77, '', '', 'admin', '', '21232f297a57a5a743894a0e4a801fc77', 'zzql.com', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `server_list`
--

CREATE TABLE IF NOT EXISTS `server_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operators_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '开服ID',
  `server_name` varchar(30) NOT NULL COMMENT '开服名称',
  `pay` int(11) NOT NULL DEFAULT '0' COMMENT '充值总量',
  `month_pay` int(11) NOT NULL DEFAULT '0' COMMENT '当月充值总量',
  `mongo_db` varchar(30) NOT NULL COMMENT 'mongodb数据库名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='游戏开服表' AUTO_INCREMENT=80 ;

--
-- 转存表中的数据 `server_list`
--

INSERT INTO `server_list` (`id`, `operators_id`, `server_id`, `server_name`, `pay`, `month_pay`, `mongo_db`) VALUES
(1, 1, 1, '测试1', 10, 10, 's_1_1'),
(3, 1, 2, '测试2', 100, 100, 's_1_2'),
(44, 3, 14, '双线6服', 0, 0, 's_3_14'),
(39, 3, 9, '双线1服', 0, 0, 's_3_9'),
(24, 2, 1, '封测第一服', 0, 0, 's_2_1'),
(77, 3, 25, '双线17服', 0, 0, 's_3_25'),
(45, 3, 15, '双线7服', 0, 0, 's_3_15'),
(40, 3, 10, '双线2服', 0, 0, 's_3_10'),
(41, 3, 11, '双线3服', 0, 0, 's_3_11'),
(42, 3, 12, '双线4服', 0, 0, 's_3_12'),
(43, 3, 13, '双线5服', 0, 0, 's_3_13'),
(46, 3, 16, '双线8服', 0, 0, 's_3_16'),
(47, 3, 17, '双线9服', 0, 0, 's_3_17'),
(48, 3, 18, '双线10服', 0, 0, 's_3_18'),
(49, 3, 19, '双线11服', 0, 0, 's_3_19'),
(50, 3, 20, '双线12服', 0, 0, 's_3_20'),
(51, 3, 21, '双线13服', 0, 0, 's_3_21'),
(76, 3, 24, '双线16服', 0, 0, 's_3_24'),
(53, 4, 35, '双线0服', 0, 0, 's_4_35'),
(54, 3, 22, '双线14服', 0, 0, 's_3_22'),
(75, 3, 23, '双线15服', 0, 0, 's_3_23'),
(78, 3, 26, '双线18服', 0, 0, 's_3_26'),
(79, 2, 2, '双线2服', 0, 0, 's_2_2');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
