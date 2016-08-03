-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 13, 2012 at 12:30 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `opsmongui`
--

-- --------------------------------------------------------

--
-- Table structure for table `adapter`
--

CREATE TABLE IF NOT EXISTS `adapter` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `logintype` int(1) NOT NULL,
  `in_identifier` int(8) NOT NULL,
  `out_identifier` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `out_identifier` (`out_identifier`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1103 ;

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `alerttype` int(1) NOT NULL,
  `firmid` int(8) NOT NULL,
  `logintype` int(1) NOT NULL,
  `active` int(1) NOT NULL,
  `startconn` int(4) NOT NULL,
  `triggered` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `cfg`
--

CREATE TABLE IF NOT EXISTS `cfg` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `keyA` varchar(32) NOT NULL,
  `keyB` varchar(32) DEFAULT NULL,
  `valueA` varchar(256) DEFAULT NULL,
  `valueB` varchar(256) DEFAULT NULL,
  `timeA` datetime DEFAULT NULL,
  `timeB` datetime DEFAULT NULL,
  `locked` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `connection`
--

CREATE TABLE IF NOT EXISTS `connection` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `logintype` int(1) NOT NULL,
  `actiontype` int(1) NOT NULL,
  `node` varchar(256) NOT NULL,
  `process` varchar(256) NOT NULL,
  `userid` int(8) NOT NULL,
  `timestamp` datetime NOT NULL,
  `exp1` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3767 ;

-- --------------------------------------------------------

--
-- Table structure for table `firm`
--

CREATE TABLE IF NOT EXISTS `firm` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `symbol` varchar(64) DEFAULT NULL,
  `bu` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bu` (`bu`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=820 ;

-- --------------------------------------------------------

--
-- Table structure for table `historic_connection`
--

CREATE TABLE IF NOT EXISTS `historic_connection` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `firmname` varchar(256) NOT NULL,
  `firmsymbol` varchar(64) NOT NULL,
  `logintype` int(4) NOT NULL,
  `actiontype` int(4) NOT NULL,
  `node` varchar(256) NOT NULL,
  `process` varchar(256) NOT NULL,
  `timestamp` datetime NOT NULL,
  `exp1` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=349330 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_info`
--

CREATE TABLE IF NOT EXISTS `network_info` (
  `id` int(4) NOT NULL,
  `firmid` int(4) NOT NULL,
  `vendor` varchar(256) DEFAULT NULL,
  `loadbalancer` varchar(256) DEFAULT NULL,
  `switch` varchar(256) DEFAULT NULL,
  `firewall` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `firmid` (`firmid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `logintype` int(1) NOT NULL,
  `firmid` int(8) NOT NULL,
  `exp1` varchar(256) DEFAULT NULL,
  `exp2` varchar(256) DEFAULT NULL,
  `exp3` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3842 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
