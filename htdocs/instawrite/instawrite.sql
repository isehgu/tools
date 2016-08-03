-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2013 at 05:24 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `instawrite`
--

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `tag_id` bigint(20) NOT NULL,
  `writeup_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='links tag to article';

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`tag_id`, `writeup_id`) VALUES
(2, 2),
(3, 3),
(4, 3),
(5, 3),
(6, 4),
(7, 5),
(7, 6),
(8, 6),
(7, 7),
(8, 7),
(9, 7),
(10, 8),
(11, 9),
(12, 9),
(13, 9),
(14, 6),
(15, 2),
(16, 10),
(17, 10),
(18, 10),
(10, 10),
(19, 11),
(20, 11),
(21, 9),
(22, 9);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  `tag_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tag creation time',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`tag_id`, `tag_name`, `tag_time`) VALUES
(2, 'test tag', '2013-11-17 11:37:39'),
(3, 'woohoo', '2013-11-17 11:38:22'),
(4, 'ugly', '2013-11-17 11:38:22'),
(5, 'milestone', '2013-11-17 11:38:22'),
(6, 'special tag\\''s #$@#%^', '2013-11-17 19:45:19'),
(7, 'test1', '2013-11-17 21:40:49'),
(8, 'test2', '2013-11-17 21:40:58'),
(9, 'test3', '2013-11-17 21:41:09'),
(10, '', '2013-11-17 22:17:05'),
(11, 'wife', '2013-11-18 11:13:51'),
(12, ' love note', '2013-11-18 11:13:51'),
(13, ' christy', '2013-11-18 11:13:51'),
(14, 'again', '2013-11-19 10:51:11'),
(15, 'again again', '2013-11-19 11:29:44'),
(16, 'long', '2013-11-19 11:33:43'),
(17, 'html', '2013-11-19 11:33:43'),
(18, 'bootstrap', '2013-11-19 11:33:43'),
(19, 'line change', '2013-11-19 11:55:33'),
(20, 'test', '2013-11-19 11:55:33'),
(21, 'love note', '2013-11-20 18:52:37'),
(22, 'christy', '2013-11-20 18:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `writeup`
--

CREATE TABLE IF NOT EXISTS `writeup` (
  `writeup_content` text NOT NULL,
  `writeup_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'This is either the creation time or  modification time',
  `writeup_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`writeup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `writeup`
--

INSERT INTO `writeup` (`writeup_content`, `writeup_time`, `writeup_id`) VALUES
('first writeup -- modified\r\nUsing only html and css, how do I disable the blue (in Firefox) highlight color around an active input field. I''ve tried using input {outline:none;} but with no success. Thanks for the help! =)\r\n\r\nok,ignoring the previous code about outline, I chose the wrong property to change. What I''m trying to get is to simply remove the highlighting (whatever browser, its the bold and colored border that appears) around an active form input field, without changing or disabling the styling. Thanks =)', '2013-11-19 11:35:35', 2),
('another writeup', '2013-11-17 11:38:22', 3),
('It\\''s a special Tag with werid values liek !@#$%^&&', '2013-11-17 19:45:19', 4),
('This is test 1\r\n\r\nUsing only html and css, how do I disable the blue (in Firefox) highlight color around an active input field. I''ve tried using input {outline:none;} but with no success. Thanks for the help! =)\r\n\r\nok,ignoring the previous code about outline, I chose the wrong property to change. What I''m trying to get is to simply remove the highlighting (whatever browser, its the bold and colored border that appears) around an active form input field, without changing or disabling the styling. Thanks =)', '2013-11-19 11:35:41', 5),
('this is test2 -- modified\r\n\r\nUsing only html and css, how do I disable the blue (in Firefox) highlight color around an active input field. I''ve tried using input {outline:none;} but with no success. Thanks for the help! =)\r\n\r\nok,ignoring the previous code about outline, I chose the wrong property to change. What I''m trying to get is to simply remove the highlighting (whatever browser, its the bold and colored border that appears) around an active form input field, without changing or disabling the styling. Thanks =)\r\n\r\n', '2013-11-19 11:35:45', 6),
('this is test3\r\n\r\nUsing only html and css, how do I disable the blue (in Firefox) highlight color around an active input field. I''ve tried using input {outline:none;} but with no success. Thanks for the help! =)\r\n\r\nok,ignoring the previous code about outline, I chose the wrong property to change. What I''m trying to get is to simply remove the highlighting (whatever browser, its the bold and colored border that appears) around an active form input field, without changing or disabling the styling. Thanks =)', '2013-11-19 11:35:18', 7),
('', '2013-11-17 22:17:05', 8),
('this is my love note to christy au my wife', '2013-11-18 11:13:51', 9),
('This is modified --\r\n\r\n<div class="input-prepend input-append">rn  \r\n<div class="btn-group">rn    \r\n<button class="btn dropdown-toggle" data-toggle="dropdown">rn      Actionrn      <span class="caret"></span>rn    </button>rn    <ul class="dropdown-menu">rn      ...rn    </ul>rn  </div>rn  <input class="span2" id="appendedPrependedDropdownButton" type="text">rn  <div class="btn-group">rn    <button class="btn dropdown-toggle" data-toggle="dropdown">rn      Actionrn      <span class="caret"></span>rn    </button>rn    <ul class="dropdown-menu">rn      ...rn    </ul>rn  </div>rn</div>\r\n\r\n', '2013-11-19 12:03:23', 10),
('another article\r\n1. step1\r\n2. step2\r\n3. step3', '2013-11-20 11:24:04', 11);
