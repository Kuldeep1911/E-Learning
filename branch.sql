-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2024 at 02:13 AM
-- Server version: 8.0.36
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `astmskmy_gravityinstitute.org.in`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `notes`, `created_at`, `updated_at`) VALUES
(5, 'GANDHI COLONY OLD FARIDABAD', 'NEAR BUA JI MANDIR ', '2024-06-25 04:22:04', '2024-06-25 04:26:14'),
(4, 'PALLA CHOWK ', 'MAIN PALLA CHOWK NEAR BY KANHIYA MEDICAL STORE ', '2024-06-24 18:06:37', '2024-06-24 18:06:37'),
(6, 'LAL KUAN KUNAL SIR BRANCH', '', '2024-06-25 04:22:52', '2024-06-25 04:22:52'),
(7, 'RAKESH MARG BRANCH AMAN SIR', '', '2024-06-25 04:23:34', '2024-06-25 04:23:34'),
(8, 'VIPIN SIR 25 FEET BRANCH', '', '2024-06-25 04:24:03', '2024-06-25 04:24:03'),
(9, 'SHANTI NAGAR DEEPAK SIR BRANCH', '', '2024-06-25 04:24:59', '2024-06-25 04:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `branch_timings`
--

CREATE TABLE `branch_timings` (
  `id` int NOT NULL,
  `branch_id` int DEFAULT NULL,
  `opening_time` varchar(1000) DEFAULT NULL,
  `closing_time` varchar(1000) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch_timings`
--

INSERT INTO `branch_timings` (`id`, `branch_id`, `opening_time`, `closing_time`, `notes`, `created_at`, `updated_at`) VALUES
(5, 4, '09:00', '10:00', 'MORNING ', '2024-06-25 04:27:13', '2024-06-25 04:27:13'),
(6, 4, '10:00', '11:00', '', '2024-06-25 04:27:41', '2024-06-25 04:27:41'),
(7, 4, '11:00', '12:00', '', '2024-06-25 04:28:09', '2024-06-25 04:28:09'),
(8, 4, '12:00', '13:00', '', '2024-06-25 04:28:35', '2024-06-25 04:28:35'),
(10, 4, '13:00', '14:00', '', '2024-06-25 04:29:36', '2024-06-25 04:29:36'),
(11, 4, '14:00', '15:00', '', '2024-06-25 04:30:34', '2024-06-25 04:30:34'),
(12, 4, '15:00', '16:00', '', '2024-06-25 04:31:00', '2024-06-25 04:31:00'),
(13, 4, '16:00', '17:00', '', '2024-06-25 04:31:30', '2024-06-25 04:31:30'),
(14, 4, '17:00', '18:00', '', '2024-06-25 04:31:57', '2024-06-25 04:31:57'),
(15, 4, '18:00', '19:00', '', '2024-06-25 04:32:30', '2024-06-25 04:32:30'),
(16, 4, '07:00', '08:00', '', '2024-06-25 04:33:38', '2024-06-25 04:33:38'),
(17, 4, '08:00', '09:00', '', '2024-06-25 04:34:06', '2024-06-25 04:34:06'),
(18, 9, '12:00', '01:00', '', '2024-06-25 04:35:02', '2024-06-25 04:35:02'),
(19, 9, '01:00', '02:00', '', '2024-06-25 04:35:56', '2024-06-25 04:35:56'),
(20, 9, '02:00', '03:00', '', '2024-06-25 04:36:25', '2024-06-25 04:36:25'),
(21, 9, '03:00', '04:00', '', '2024-06-25 04:36:46', '2024-06-25 04:36:46'),
(22, 9, '04:00', '05:00', '', '2024-06-25 04:37:12', '2024-06-25 04:37:12'),
(23, 9, '05:00', '06:00', '', '2024-06-25 04:37:51', '2024-06-25 04:37:51'),
(24, 9, '06:00', '07:00', '', '2024-06-25 04:38:21', '2024-06-25 04:38:21'),
(25, 9, '08:00', '09:00', '', '2024-06-25 04:38:50', '2024-06-25 04:38:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_timings`
--
ALTER TABLE `branch_timings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `branch_timings`
--
ALTER TABLE `branch_timings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
