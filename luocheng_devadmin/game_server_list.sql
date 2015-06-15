-- phpMyAdmin SQL Dump
-- version 3.4.6-rc1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 01 月 10 日 19:51
-- 服务器版本: 5.0.77
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `game_server_list`
--

-- --------------------------------------------------------

--
-- 表的结构 `operators`
--

CREATE TABLE IF NOT EXISTS `operators` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(300) NOT NULL COMMENT '运营商名字',
  `company` varchar(300) NOT NULL COMMENT '公司名称',
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `password` varchar(128) NOT NULL COMMENT '密码',
  `identifier` varchar(64) NOT NULL COMMENT '运营商识别码',
  `platform` varchar(60) NOT NULL COMMENT '平台地址无 WWW',
  `pay` varchar(300) NOT NULL COMMENT '充值地址',
  `login` varchar(100) NOT NULL COMMENT '平台登录地址',
  `condition` tinyint(1) NOT NULL default '0' COMMENT '条件分成模式  0否 1是',
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='运营商' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `operators`
--

INSERT INTO `operators` (`id`, `name`, `company`, `username`, `password`, `identifier`, `platform`, `pay`, `login`, `condition`, `sharing_ratio`, `sharing_ratio_c`, `boss`, `boss_qq`, `boss_email`, `product_manager`, `product_manager_qq`, `product_manager_mail`, `technical_director`, `technical_director_qq`, `technical_director_email`) VALUES
(1, '某某玩', '某某科技有限公司', 'admin', '21232f297a57a5a743894a0e4a801fc3', '21232f297a57a5a743894a0e4a801fc3', 'zzql.com', 'PLATFORM."/recharge?gid=".CHANNEL."&sid=".SERVER', 'PLATFORM."/api/login.php?gid=".CHANNEL."&sid=".SERVER', 0, 0.5, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com'),
(2, '某玩', '某某科技有限公司', '37admin', '21232f297a57a5a743894a0e4a801fc3', '21232f297a57a5a743894a0e4a801fc3', 'zzql.com', 'PLATFORM."/recharge?gid=".CHANNEL."&sid=".SERVER', 'PLATFORM."/api/login.php?gid=".CHANNEL."&sid=".SERVER', 0, 0.5, '', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com', 13711712356, 179893511, 'jd808@qq.com');

-- --------------------------------------------------------

--
-- 表的结构 `server_list`
--

CREATE TABLE IF NOT EXISTS `server_list` (
  `operators_id` int(11) NOT NULL default '0' COMMENT '运营商ID',
  `server_id` int(11) NOT NULL default '0' COMMENT '开服ID',
  `server_name` varchar(30) NOT NULL COMMENT '开服名称',
  `game_web` varchar(128) NOT NULL COMMENT '游戏地址',
  `pay` int(11) NOT NULL default '0' COMMENT '充值总量',
  `month_pay` int(11) NOT NULL default '0' COMMENT '当月充值总量',
  `server_ip` varchar(30) NOT NULL COMMENT '服务器IP',
  `server_root` varchar(6) NOT NULL default 'root' COMMENT '服务器root帐号',
  `server_pwd` varchar(30) NOT NULL default '123456' COMMENT '服务器root帐号密码',
  `server_mongo_ip` varchar(64) NOT NULL COMMENT '芒果服务器IP',
  `server_mongo_port` int(11) NOT NULL default '11211' COMMENT '服务器芒果端口',
  `mongo_username` varchar(30) NOT NULL COMMENT '芒果服务器用户名',
  `mongo_password` varchar(30) NOT NULL COMMENT '芒果服务器密码',
  `mongo_db` varchar(30) NOT NULL COMMENT '芒果数据库名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游戏开服表';

--
-- 转存表中的数据 `server_list`
--

INSERT INTO `server_list` (`operators_id`, `server_id`, `server_name`, `game_web`, `pay`, `month_pay`, `server_ip`, `server_root`, `server_pwd`, `server_mongo_ip`, `server_mongo_port`, `mongo_username`, `mongo_password`, `mongo_db`) VALUES
(1, 1, '神龙定海', 's1.qq.zzql.com', 0, 0, '192.168.4.6', 'root', '123456', '127.0.0.1', 27017, '', '', 'qq'),
(2, 1, '神龙定海', '117.25.131.173', 0, 0, '117.25.131.173', 'root', '123456', '117.25.131.173', 27017, 'qqgame', 'pH65EYLJhb2vdnKL', 'qq');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
