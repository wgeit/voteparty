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
-- Table structure for table `newyearimagepath`
--

CREATE TABLE `newyearimagepath` (
  `id` int NOT NULL,
  `yearEvent` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `siteEvent` varchar(5) NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `newyearimagepath`
--

INSERT INTO `newyearimagepath` (`id`, `yearEvent`, `siteEvent`, `image_path`, `category`, `post_name`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(8, '2025', 'HQ', 'special_event_images/1768649411_696b72c36fa88.jpeg', 'แบบกลุ่ม', 'Ann Charinee', '2026-01-17 18:30:12', 'แสงชัย ใจน้อย', '2026-01-17 18:30:12', NULL),
(9, '2025', 'HQ', 'special_event_images/1768649478_696b730652ca3.jpeg', 'เดี่ยวหญิง', 'Ann Charinee', '2026-01-17 18:31:18', 'แสงชัย ใจน้อย', '2026-01-17 18:31:18', NULL),
(10, '2025', 'HQ', 'special_event_images/1768649683_696b73d390490.jpeg', 'เดี่ยวชาย', 'Narakorn Sungkhapoonya', '2026-01-17 18:34:43', 'แสงชัย ใจน้อย', '2026-01-17 18:34:43', NULL),
(11, '2025', 'HQ', 'special_event_images/1768649843_696b74739fbbe.jpeg', 'แบบกลุ่ม', 'Ratthanan Yuennan', '2026-01-17 18:37:23', 'แสงชัย ใจน้อย', '2026-01-17 18:37:23', NULL),
(12, '2025', 'HQ', 'special_event_images/1768649941_696b74d5dba56.jpeg', 'เดี่ยวหญิง', 'Wantana Musidang', '2026-01-17 18:39:01', 'แสงชัย ใจน้อย', '2026-01-17 18:39:01', NULL),
(14, '2025', 'HQ', 'special_event_images/1768650052_696b75440c846.jpeg', 'เดี่ยวหญิง', 'Zom Romorin', '2026-01-17 18:40:52', 'แสงชัย ใจน้อย', '2026-01-17 18:40:52', NULL),
(15, '2025', 'HQ', 'special_event_images/1768650155_696b75ab60680.jpeg', 'เดี่ยวชาย', 'จิ ลาหยุด', '2026-01-17 18:42:35', 'แสงชัย ใจน้อย', '2026-01-17 18:42:35', NULL),
(16, '2025', 'HQ', 'special_event_images/1768650580_696b7754e6d3f.jpeg', 'เดี่ยวหญิง', 'Walanchayanan Nakha', '2026-01-17 18:49:40', 'แสงชัย ใจน้อย', '2026-01-17 18:49:40', NULL),
(17, '2025', 'HQ', 'special_event_images/1768650779_696b781b7d1a3.jpeg', 'เดี่ยวหญิง', 'Preaw Panitpohn', '2026-01-17 18:52:59', 'แสงชัย ใจน้อย', '2026-01-17 18:52:59', NULL),
(18, '2025', 'HQ', 'special_event_images/1768652477_696b7ebd61269.jpeg', 'เดี่ยวหญิง', 'Smile Ramphai', '2026-01-17 19:21:17', 'แสงชัย ใจน้อย', '2026-01-17 19:21:17', NULL),
(19, '2025', 'HQ', 'special_event_images/1768653122_696b81427881b.jpeg', 'เดี่ยวชาย', 'Naboon Suphakrit', '2026-01-17 19:32:02', 'แสงชัย ใจน้อย', '2026-01-17 19:32:02', NULL),
(21, '2025', 'HQ', 'special_event_images/1768654427_696b865b3eac8.jpeg', 'แบบกลุ่ม', 'สุธินันท์ สายบุตร', '2026-01-17 19:53:47', 'แสงชัย ใจน้อย', '2026-01-17 19:53:47', NULL),
(22, '2025', 'HQ', 'special_event_images/1768654480_696b86906b3f6.jpeg', 'เดี่ยวหญิง', 'สุธินันท์ สายบุตร', '2026-01-17 19:54:40', 'แสงชัย ใจน้อย', '2026-01-17 19:54:40', NULL),
(23, '2025', 'HQ', 'special_event_images/1768654565_696b86e5df5e8.jpeg', 'เดี่ยวหญิง', 'Sugar Queen', '2026-01-17 19:56:05', 'แสงชัย ใจน้อย', '2026-01-17 19:56:05', NULL),
(27, '2025', 'SITE', 'special_event_images/1768903529_696f5369cf9cd.png', 'เดี่ยวชาย', 'เทส1', '2026-01-20 17:05:29', 'System', '2026-01-20 17:05:29', NULL),
(28, '2025', 'SITE', 'special_event_images/1768903541_696f53755a626.png', 'เดี่ยวหญิง', 'girl1', '2026-01-20 17:05:41', 'System', '2026-01-20 17:05:41', NULL),
(29, '2025', 'SITE', 'special_event_images/1768903553_696f5381e3c8f.jpg', 'แบบกลุ่ม', 'group1', '2026-01-20 17:05:53', 'System', '2026-01-20 17:05:53', NULL),
(30, '2025', 'SITE', 'special_event_images/1768903564_696f538c18860.png', 'เดี่ยวชาย', 'man2', '2026-01-20 17:06:04', 'System', '2026-01-20 17:06:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `newyearimagepath`
--
ALTER TABLE `newyearimagepath`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `newyearimagepath`
--
ALTER TABLE `newyearimagepath`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
