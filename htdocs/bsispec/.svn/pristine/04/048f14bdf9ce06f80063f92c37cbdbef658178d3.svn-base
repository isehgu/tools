-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2011 at 10:38 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bsispec`
--

-- --------------------------------------------------------

--
-- Table structure for table `cfg`
--

CREATE TABLE IF NOT EXISTS `cfg` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `component` varchar(256) NOT NULL,
  `type` int(4) NOT NULL,
  `value` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `iors`
--

CREATE TABLE IF NOT EXISTS `iors` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `property_name` varchar(512) NOT NULL,
  `property_value` varchar(512) NOT NULL,
  PRIMARY KEY (`id`,`property_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=549 ;

-- --------------------------------------------------------

--
-- Table structure for table `iors_customrules`
--

CREATE TABLE IF NOT EXISTS `iors_customrules` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `rulename` varchar(256) NOT NULL,
  `actiontype` varchar(256) NOT NULL,
  `direction` varchar(256) NOT NULL,
  `fieldtag` varchar(256) NOT NULL,
  `matchcriteria` varchar(256) NOT NULL,
  `fieldtype` varchar(256) NOT NULL,
  `newvalue` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `precise`
--

CREATE TABLE IF NOT EXISTS `precise` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `property_name` varchar(512) NOT NULL,
  `property_value` varchar(512) NOT NULL,
  PRIMARY KEY (`id`,`property_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=339 ;

-- --------------------------------------------------------

--
-- Table structure for table `precise_amr`
--

CREATE TABLE IF NOT EXISTS `precise_amr` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `member` varchar(256) NOT NULL,
  `ird` varchar(256) NOT NULL,
  `cmta` varchar(256) NOT NULL,
  `account` varchar(256) NOT NULL,
  `default_cmta_flag` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2030 ;

-- --------------------------------------------------------

--
-- Table structure for table `precise_ird`
--

CREATE TABLE IF NOT EXISTS `precise_ird` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `member` varchar(256) NOT NULL,
  `exchange` varchar(256) NOT NULL,
  `irds` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1122 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
