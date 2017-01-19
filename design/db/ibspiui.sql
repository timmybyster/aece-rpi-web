-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 16, 2015 at 03:38 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ibspiui`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `node_serial` varchar(45) DEFAULT NULL COMMENT 'Using serial, rather than link to db, because node configuration might change. The serial can always be searched through the node database, but if id is no longer valid it does not work anymore.',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `message`, `node_serial`, `created`, `modified`) VALUES
(8, 'ggg', '1', '2015-02-04 15:28:29', '2015-02-10 15:28:29'),
(9, 'hhh', '3', '2015-02-10 15:28:36', '2015-02-10 15:28:36'),
(10, 'jjj', '6', '2015-02-10 15:28:44', '2015-02-10 15:28:44'),
(11, 'aaa', '6', '2015-02-10 15:29:24', '2015-02-10 15:29:24'),
(13, 'kk', '2', '2015-02-10 15:33:01', '2015-02-10 15:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `x` int(11) DEFAULT NULL COMMENT 'Position on the system status page',
  `y` int(11) DEFAULT NULL COMMENT 'Position on the system status page',
  `serial` varchar(45) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `key_switch_status` int(11) DEFAULT NULL,
  `communication_status` int(11) DEFAULT NULL,
  `temperature` int(11) DEFAULT NULL,
  `blast_armed` int(11) DEFAULT NULL,
  `fire_button` int(11) DEFAULT NULL,
  `isolation_relay` int(11) DEFAULT NULL,
  `cable_fault` int(11) DEFAULT NULL,
  `earth_leakage` int(11) DEFAULT NULL,
  `detonator_status` int(11) DEFAULT NULL,
  `partial_blast_lfs` int(11) DEFAULT NULL,
  `full_blast_lfs` int(11) DEFAULT NULL,
  `booster_fired_lfs` int(11) DEFAULT NULL,
  `missing_pulse_detected_lfs` int(11) DEFAULT NULL,
  `AC_supply_voltage_lfs` int(11) DEFAULT NULL,
  `DC_supply_voltage` int(11) DEFAULT NULL,
  `DC_supply_voltage_status` int(11) DEFAULT NULL,
  `mains` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`id`, `x`, `y`, `serial`, `type_id`, `key_switch_status`, `communication_status`, `temperature`, `blast_armed`, `fire_button`, `isolation_relay`, `cable_fault`, `earth_leakage`, `detonator_status`, `partial_blast_lfs`, `full_blast_lfs`, `booster_fired_lfs`, `missing_pulse_detected_lfs`, `AC_supply_voltage_lfs`, `DC_supply_voltage`, `DC_supply_voltage_status`, `mains`, `parent_id`, `created`, `modified`) VALUES
(1, 217, -465, '112', 0, 1, 1, 999, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2015-01-26 10:58:56', '2015-02-13 10:59:30'),
(2, -77, -523, '113', 1, 1, 1, 55, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2015-01-26 14:50:43', '2015-02-13 11:27:49'),
(3, -80, -357, '115', 1, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2015-01-31 10:28:02', '2015-02-13 10:59:26'),
(4, -286, -249, '116', 2, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 1, 230, 12, 1, NULL, 3, '2015-01-31 10:28:14', '2015-02-13 10:59:22'),
(5, -310, -494, '133425', 2, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 0, 196, 11, 0, NULL, 2, '2015-02-02 21:47:10', '2015-02-13 10:59:24'),
(6, 243, -12, '12349', 1, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2015-02-02 21:49:13', '2015-02-13 10:59:16'),
(7, 10, -229, '987', 2, 0, 1, 777, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, 200, 12, 0, NULL, 6, '2015-02-02 21:50:17', '2015-02-13 11:02:00'),
(8, 242, 202, '254', 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 212, 13, 1, NULL, 6, '2015-02-03 13:56:20', '2015-02-13 10:59:20'),
(9, -41, -34, '253', 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 1, 201, 14, 1, NULL, 6, '2015-02-03 13:56:57', '2015-02-13 11:01:15'),
(10, 345, -346, '555', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2015-02-12 09:48:44', '2015-02-13 10:59:29');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `value`, `created`, `modified`) VALUES
(6, 'log_time_to_keep', '4', '2015-02-10 15:32:14', '2015-02-10 15:32:14'),
(7, 'warning_dismiss_delay', '20', '2015-02-12 11:30:49', '2015-02-12 11:30:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `contact_number` varchar(45) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `contact_number`, `role_id`, `created`, `modified`) VALUES
(1, 'dewalddp@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', 'Dewald', '0839911648', 99, '2015-01-26 10:50:43', '2015-01-26 10:54:32'),
(2, 'testadmin@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', 'TestAdmin', '', 1, '2015-01-26 15:25:43', '2015-02-03 14:53:49'),
(3, 'testsupervisor@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', 'Test Supervisor', '', 3, '2015-01-26 15:26:38', '2015-02-09 14:14:03'),
(4, 'testtechnition@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', 'Test Technition', '', 2, '2015-01-26 15:27:16', '2015-02-09 14:13:52'),
(5, 'abc@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', '', '', 99, '2015-02-02 14:45:09', '2015-02-03 14:56:39'),
(6, 'testadmin222@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', '', '', 1, '2015-02-04 13:48:00', '2015-02-04 13:48:00'),
(7, 'testadmin223@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', '', '', 1, '2015-02-04 13:50:48', '2015-02-04 13:54:11'),
(9, 'dewalddp5554@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', '', '', 1, '2015-02-04 16:32:17', '2015-02-04 16:32:17'),
(10, 'dewalddp555444@gmail.com', '1e78c1287d71bd2f3c38d1a68af591bfaebbab02', '111', '', 1, '2015-02-05 15:02:01', '2015-02-05 15:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE IF NOT EXISTS `warnings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `acknowledged` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `warnings`
--

INSERT INTO `warnings` (`id`, `message`, `acknowledged`, `user_id`, `created`, `modified`) VALUES
(1, 'Unit 123 was connected to blaster without Key Switch armed', 1, 1, '2015-02-06 13:00:11', '2015-02-06 14:34:07'),
(2, 'Some other warning', 1, 1, '2015-02-06 13:51:42', '2015-02-12 11:45:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
