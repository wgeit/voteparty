-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 21, 2026 at 02:28 AM
-- Server version: 8.0.42
-- PHP Version: 8.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wgeintra`
--

-- --------------------------------------------------------

--
-- Table structure for table `newyearopenvote`
--

CREATE TABLE `newyearopenvote` (
  `statusOpen` int NOT NULL DEFAULT '0',
  `yearEvent` varchar(5) NOT NULL,
  `SiteEvent` varchar(5) NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `TimeStart` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `newyearopenvote`
--

INSERT INTO `newyearopenvote` (`statusOpen`, `yearEvent`, `SiteEvent`, `status`, `TimeStart`, `updated_at`, `updated_by`) VALUES
(0, '2025', 'HQ', 0, '2026-01-20 16:21:05', '0000-00-00 00:00:00', ''),
(0, '2025', 'SITE', 1, '2026-01-20 17:38:38', '0000-00-00 00:00:00', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
