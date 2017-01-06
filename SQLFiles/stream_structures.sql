-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2017 at 02:44 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simvo`
--

-- --------------------------------------------------------

--
-- Table structure for table `stream_structures`
--

CREATE TABLE IF NOT EXISTS `stream_structures` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stream_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `program_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stream_structures`
--

INSERT INTO `stream_structures` (`id`, `stream_name`, `program_id`, `version`, `created_at`, `updated_at`) VALUES(1, 'Software Engineering Curriculum Gegep Entry', 100533, 3, '2016-08-24 03:51:35', '2016-08-24 03:51:35');
INSERT INTO `stream_structures` (`id`, `stream_name`, `program_id`, `version`, `created_at`, `updated_at`) VALUES(2, 'Software Engineering Curriculum Non-Gegep Entry', 100533, 3, '2016-08-24 03:51:36', '2016-08-24 03:51:36');
INSERT INTO `stream_structures` (`id`, `stream_name`, `program_id`, `version`, `created_at`, `updated_at`) VALUES(6, 'Computer Engineering Curriculum Non-Cegep Entry', 100282, 3, '2016-08-24 00:01:54', '2016-08-24 00:01:54');
INSERT INTO `stream_structures` (`id`, `stream_name`, `program_id`, `version`, `created_at`, `updated_at`) VALUES(7, 'Electrical Engineering Curriculum Non-Cegep Entry', 100284, 4, '2016-08-24 00:01:54', '2016-08-24 00:01:54');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
