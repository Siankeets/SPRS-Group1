-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql213.infinityfree.com
-- Generation Time: Nov 23, 2025 at 10:23 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40284661_sprs_mainredo`
--

-- --------------------------------------------------------

--
-- Table structure for table `eventparticipants`
--

CREATE TABLE `eventparticipants` (
  `eventID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `attended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventparticipants`
--

INSERT INTO `eventparticipants` (`eventID`, `studentID`, `id`, `attended`) VALUES
(15, 5, 5, 1),
(15, 7, 7, 1),
(16, 5, 5, 1),
(16, 6, 6, 1),
(18, 5, 5, 1),
(21, 5, 5, 1),
(21, 7, 7, 1),
(22, 5, 5, 1),
(23, 5, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `studentID`, `eventID`, `registered_at`) VALUES
(23, 5, 15, '2025-11-22 23:53:20'),
(24, 5, 16, '2025-11-23 00:06:09'),
(26, 5, 18, '2025-11-23 00:29:31'),
(28, 7, 15, '2025-11-23 01:28:23'),
(29, 7, 21, '2025-11-23 01:42:09'),
(30, 5, 22, '2025-11-23 03:37:12'),
(31, 5, 21, '2025-11-23 03:42:27'),
(32, 5, 23, '2025-11-23 15:56:52'),
(33, 6, 16, '2025-11-24 18:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `help_conversations`
--

CREATE TABLE `help_conversations` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `staffID` int(11) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `help_conversations`
--

INSERT INTO `help_conversations` (`id`, `studentID`, `staffID`, `status`, `last_updated`) VALUES
(33, 7, 1, 'open', '2025-11-20 07:49:46'),
(34, 5, 1, 'open', '2025-11-24 02:47:11'),
(35, 5, 2, 'open', '2025-11-20 15:22:49'),
(36, 10, 1, 'open', '2025-11-20 07:57:31'),
(37, 9, 1, 'open', '2025-11-20 08:03:19'),
(38, 14, 1, 'open', '2025-11-20 08:06:24'),
(39, 6, 2, 'open', '2025-11-21 03:51:49'),
(40, 6, 1, 'open', '2025-11-20 15:27:23'),
(41, 11, 1, '', '2025-11-21 03:37:39'),
(42, 7, 2, '', '2025-11-21 03:38:02'),
(43, 11, 2, '', '2025-11-21 03:38:03'),
(44, 9, 2, '', '2025-11-21 03:51:51'),
(45, 12, 1, 'open', '2025-11-22 07:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `help_messages`
--

CREATE TABLE `help_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender` enum('student','staff') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `help_messages`
--

INSERT INTO `help_messages` (`id`, `conversation_id`, `sender`, `message`, `created_at`) VALUES
(95, 33, 'student', 'hallo', '2025-11-20 07:49:11'),
(96, 33, 'student', 'hallo', '2025-11-20 07:49:12'),
(97, 33, 'staff', 'halo', '2025-11-20 07:49:46'),
(98, 34, 'student', 'amrica ya', '2025-11-20 07:53:29'),
(99, 34, 'staff', 'sup', '2025-11-20 07:53:54'),
(100, 34, 'student', 'hamburger', '2025-11-20 07:54:02'),
(101, 36, 'student', 'hello po', '2025-11-20 07:56:55'),
(102, 36, 'staff', 'yes po', '2025-11-20 07:57:04'),
(103, 36, 'student', 'this is not first message', '2025-11-20 07:57:28'),
(104, 36, 'staff', 'i know', '2025-11-20 07:57:31'),
(105, 34, 'staff', 'yes', '2025-11-20 08:02:15'),
(106, 37, 'student', 'need assistance', '2025-11-20 08:02:23'),
(107, 37, 'staff', 'understoood', '2025-11-20 08:03:19'),
(108, 38, 'student', 'test1', '2025-11-20 08:06:02'),
(109, 38, 'staff', 'america ya', '2025-11-20 08:06:24'),
(110, 34, 'student', 'hosting test 1 2 3', '2025-11-20 12:39:43'),
(111, 34, 'staff', 'confirmed hosting test 4 5 6', '2025-11-20 12:39:58'),
(112, 34, 'student', 'Confirmed helpdesk working, Adjustments were easy. I\'ve only done 1 set of hosting test and ill just assume it works as intended.', '2025-11-20 12:41:01'),
(113, 34, 'staff', 'confirmed: student -> bob santos and vice versa will not be tested. Only Admin Alice <-> Student Juan is tested tonight.  Date is 11/20/2025.', '2025-11-20 12:42:28'),
(114, 34, 'staff', 'Hello', '2025-11-20 12:53:47'),
(115, 35, 'student', 'halo', '2025-11-20 15:22:26'),
(116, 35, 'staff', 'america ya', '2025-11-20 15:22:49'),
(117, 39, 'student', 'Hello sir', '2025-11-20 15:24:39'),
(118, 39, 'staff', 'yes what\'s the problem?', '2025-11-20 15:24:54'),
(119, 39, 'student', 'There is a problem with my system interface, my item is lost', '2025-11-20 15:25:18'),
(120, 39, 'staff', 'will work on it immediately, please provide me with your user name?', '2025-11-20 15:26:05'),
(121, 39, 'student', 'It\'s student2', '2025-11-20 15:26:15'),
(122, 40, 'student', 'Hello maam', '2025-11-20 15:26:36'),
(123, 40, 'staff', 'yes what\'s the matter?', '2025-11-20 15:27:23'),
(124, 39, 'staff', 'please check again, thank you', '2025-11-21 03:51:49'),
(125, 34, 'student', 'hello again', '2025-11-22 06:57:34'),
(126, 34, 'staff', 'What\'s up', '2025-11-22 06:57:46'),
(127, 34, 'staff', 'I have a problem', '2025-11-22 06:58:03'),
(128, 45, 'student', 'first chat please sms', '2025-11-22 06:59:52'),
(129, 45, 'staff', 'Noice have sms', '2025-11-22 07:00:13'),
(130, 34, 'student', 'i have a problem', '2025-11-22 22:18:15'),
(131, 34, 'staff', 'what is your concern?', '2025-11-22 22:18:25'),
(132, 34, 'student', 'my system is not working', '2025-11-22 22:18:33'),
(133, 34, 'staff', 'i see', '2025-11-22 22:21:00'),
(134, 34, 'student', 'hello', '2025-11-22 23:58:04'),
(135, 34, 'staff', 'hello', '2025-11-22 23:58:19'),
(136, 34, 'student', 'hello', '2025-11-24 01:05:20'),
(137, 34, 'staff', 'test1', '2025-11-24 01:05:30'),
(138, 34, 'student', 'nice', '2025-11-24 01:05:38'),
(139, 34, 'staff', 'good', '2025-11-24 01:05:44'),
(140, 34, 'student', 'test 1', '2025-11-24 02:46:54'),
(141, 34, 'staff', 'reply 1', '2025-11-24 02:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `rewardID` int(11) NOT NULL,
  `rewardName` varchar(255) NOT NULL,
  `rewardDescription` text DEFAULT NULL,
  `rewardPointsRequired` int(11) NOT NULL,
  `rewardType` enum('Ticket','Supplies','Tshirts','IDs','Points') NOT NULL DEFAULT 'Supplies'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`rewardID`, `rewardName`, `rewardDescription`, `rewardPointsRequired`, `rewardType`) VALUES
(29, 'Hamburger', 'hamburger 1', 150, 'Ticket'),
(30, 'test 2', 'test 2', 150, 'Supplies'),
(31, 'test 4', 'test 4', 150, 'Ticket');

-- --------------------------------------------------------

--
-- Table structure for table `schoolevents`
--

CREATE TABLE `schoolevents` (
  `eventID` int(11) NOT NULL,
  `eventName` varchar(128) NOT NULL,
  `eventDescription` text DEFAULT NULL,
  `eventRewards` text DEFAULT NULL,
  `rewardType` varchar(20) NOT NULL DEFAULT 'Points',
  `eventDate` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schoolevents`
--

INSERT INTO `schoolevents` (`eventID`, `eventName`, `eventDescription`, `eventRewards`, `rewardType`, `eventDate`) VALUES
(15, 'Hamburger 1', 'test for attendance and registering', '200', 'Points', '2025-11-22'),
(16, 'hamburger2', 'test for nov25 date, qr codes has tto be distributed by admins so students can login, but take not that it will log attendance anyways if leaked to students', '200', 'Points', '2025-11-25'),
(18, 'test 5', 'register only', '200', 'Points', '2025-11-28'),
(21, 'FIN TEST V3', 'FIN TEST V3', '100', 'Points', '2025-11-22'),
(22, 'points 1 test', 'seeing if points get distributed', '500', 'Points', '2025-11-22'),
(23, 'points 2', 'test 2', '300', 'Points', '2025-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `student_activity_log`
--

CREATE TABLE `student_activity_log` (
  `logID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `type` enum('Reward Redeemed','Reward Used','Event Registered','Event Attended') NOT NULL,
  `description` varchar(255) NOT NULL,
  `logDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_activity_log`
--

INSERT INTO `student_activity_log` (`logID`, `studentID`, `type`, `description`, `logDate`) VALUES
(9, 5, 'Reward Used', 'Used \'test 3\'', '2025-11-17 11:32:33'),
(10, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-18 03:50:32'),
(11, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-18 13:14:52'),
(12, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-19 10:19:05'),
(13, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-19 10:50:04'),
(14, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-19 11:37:59'),
(15, 5, 'Reward Used', 'Used \'test2\'', '2025-11-19 11:38:22'),
(16, 5, 'Reward Used', 'Used \'test 3\'', '2025-11-19 11:38:25'),
(17, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-19 11:38:27'),
(18, 6, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-20 15:28:28'),
(19, 6, 'Reward Used', 'Used \'test2\'', '2025-11-20 15:30:33'),
(20, 5, 'Reward Used', 'Used \'test2\'', '2025-11-21 04:16:27'),
(21, 5, 'Reward Used', 'Used \'test 3\'', '2025-11-21 04:18:18'),
(22, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-21 04:18:30'),
(23, 6, 'Reward Used', 'Used \'test 3\'', '2025-11-21 05:03:56'),
(24, 5, 'Reward Used', 'Used \'test 3\'', '2025-11-22 05:46:48'),
(25, 5, 'Reward Used', 'Used \'test2\'', '2025-11-22 06:42:48'),
(26, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-22 23:56:22'),
(27, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-22 23:56:32'),
(28, 5, 'Reward Used', 'Used \'test 4\'', '2025-11-24 01:07:36'),
(29, 5, 'Reward Used', 'Used \'cheese\'', '2025-11-24 01:07:56'),
(30, 5, 'Reward Used', 'Used \'test 2\'', '2025-11-24 02:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `student_inventory`
--

CREATE TABLE `student_inventory` (
  `inventoryID` int(11) NOT NULL,
  `studentID` int(11) DEFAULT NULL,
  `rewardID` int(11) DEFAULT NULL,
  `dateRedeemed` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_inventory`
--

INSERT INTO `student_inventory` (`inventoryID`, `studentID`, `rewardID`, `dateRedeemed`) VALUES
(60, 5, 16, '2025-11-21 21:47:02'),
(63, 5, 16, '2025-11-22 15:55:41'),
(66, 5, 26, '2025-11-23 17:07:12'),
(67, 5, 16, '2025-11-23 17:07:15'),
(68, 5, 22, '2025-11-23 17:07:19'),
(69, 5, 21, '2025-11-23 17:07:21'),
(70, 5, 29, '2025-11-23 17:20:09'),
(72, 5, 31, '2025-11-23 17:20:17'),
(73, 5, 29, '2025-11-23 18:44:41'),
(74, 5, 30, '2025-11-23 18:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('student','admin') NOT NULL,
  `points` int(11) DEFAULT 0,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`, `role`, `points`, `name`, `department`, `program`, `major`) VALUES
(1, 'admin_01', 'admin123', NULL, 'admin', 0, 'Jane Admin', 'CICS', NULL, NULL),
(2, 'student_01', 'pass123', NULL, 'student', 120, 'John Student', 'CICS', 'BSIT', 'Service Management');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD PRIMARY KEY (`eventID`,`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studentID` (`studentID`,`eventID`);

--
-- Indexes for table `help_conversations`
--
ALTER TABLE `help_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_messages`
--
ALTER TABLE `help_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`rewardID`);

--
-- Indexes for table `schoolevents`
--
ALTER TABLE `schoolevents`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `student_activity_log`
--
ALTER TABLE `student_activity_log`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `student_inventory`
--
ALTER TABLE `student_inventory`
  ADD PRIMARY KEY (`inventoryID`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `rewardID` (`rewardID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `help_conversations`
--
ALTER TABLE `help_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `help_messages`
--
ALTER TABLE `help_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `schoolevents`
--
ALTER TABLE `schoolevents`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `student_activity_log`
--
ALTER TABLE `student_activity_log`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `student_inventory`
--
ALTER TABLE `student_inventory`
  MODIFY `inventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD CONSTRAINT `eventparticipants_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `schoolevents` (`eventID`);

--
-- Constraints for table `help_messages`
--
ALTER TABLE `help_messages`
  ADD CONSTRAINT `help_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `help_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_inventory`
--
ALTER TABLE `student_inventory`
  ADD CONSTRAINT `student_inventory_ibfk_2` FOREIGN KEY (`rewardID`) REFERENCES `rewards` (`rewardID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
