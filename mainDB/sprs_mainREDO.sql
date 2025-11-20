-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 09:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sprs_mainredo`
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
(34, 5, 1, 'open', '2025-11-20 08:02:15'),
(35, 5, 2, 'open', '2025-11-20 07:56:25'),
(36, 10, 1, 'open', '2025-11-20 07:57:31'),
(37, 9, 1, 'open', '2025-11-20 08:03:19'),
(38, 14, 1, 'open', '2025-11-20 08:06:24');

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
(109, 38, 'staff', 'america ya', '2025-11-20 08:06:24');

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
(15, 'test2', 'hamburger', 20, 'Supplies'),
(16, 'test 3', 'test 3', 30, 'Tshirts'),
(19, '20% Voucher', 'for any school supply purchases', 200, 'Ticket');

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
(8, 'test1', 'test1', '200 points', 'Points', '2025-11-25'),
(9, 'test 2', 'test 2', '500', 'Points', '2025-11-19');

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
(17, 5, 'Reward Used', 'Used \'20% Voucher\'', '2025-11-19 11:38:27');

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
(52, 5, 19, '2025-11-19 19:38:40'),
(53, 5, 16, '2025-11-19 19:38:43'),
(54, 5, 15, '2025-11-19 19:38:47');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `help_conversations`
--
ALTER TABLE `help_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `help_messages`
--
ALTER TABLE `help_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `schoolevents`
--
ALTER TABLE `schoolevents`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_activity_log`
--
ALTER TABLE `student_activity_log`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `student_inventory`
--
ALTER TABLE `student_inventory`
  MODIFY `inventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
  ADD CONSTRAINT `eventparticipants_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `schoolevents` (`eventID`),
  ADD CONSTRAINT `eventparticipants_ibfk_2` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

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
