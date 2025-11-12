-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 05:51 AM
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
  `id` int(11) NOT NULL,
  `attended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Full Test Complete', 'Full Rewards Testing at 3:45PM (og desc & 20  pts req)\r\nedit: name (Full Test 11/10/2025) -> Full Test Complete + changing pts req -> 40.\r\nedit#2: not gonna test delete a 2nd time, it works 1st time so its probably good already. Create, Read and Update is tested a 2nd time with Full Test Complete.', 20, 'Supplies'),
(4, 'Test1', 'Description for test 1\r\n', 200, 'Supplies'),
(5, 'test2', 'test2', 200, 'Supplies'),
(6, 'test 4', 'changed test 3 edit part of crud', 200, 'Tshirts'),
(7, '50% discount ticket for CICS event week', 'For behaved students only', 200, 'Ticket'),
(8, 'Hamburger', '2 hamburgers for best students', 350, 'Supplies'),
(9, 'hamrbuer', 'qw2134', 500, 'Ticket'),
(10, 'test 5', 'test 5', 35, 'IDs'),
(11, 'test 6 ', 'test 6', 55, 'Ticket'),
(12, 'test 7 ', 'test 7', 10, 'Points');

-- --------------------------------------------------------

--
-- Table structure for table `schoolevents`
--

CREATE TABLE `schoolevents` (
  `eventID` int(11) NOT NULL,
  `eventName` varchar(128) NOT NULL,
  `eventDescription` text DEFAULT NULL,
  `eventCreatorID` int(11) NOT NULL,
  `eventMinCap` int(11) NOT NULL,
  `eventMaxCap` int(11) NOT NULL,
  `eventStartDate` datetime NOT NULL,
  `eventEndDate` datetime NOT NULL,
  `eventCreationDate` datetime DEFAULT current_timestamp(),
  `eventRewards` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 5, 3, '2025-11-12 12:09:48'),
(3, 5, 3, '2025-11-12 12:14:13'),
(4, 5, 3, '2025-11-12 12:18:46'),
(5, 5, 3, '2025-11-12 12:21:16'),
(6, 5, 7, '2025-11-12 12:31:54'),
(7, 5, 6, '2025-11-12 12:31:56'),
(8, 5, 3, '2025-11-12 12:42:00'),
(9, 5, 3, '2025-11-12 12:43:58'),
(10, 11, 3, '2025-11-12 12:44:34'),
(11, 11, 12, '2025-11-12 12:46:04'),
(12, 11, 11, '2025-11-12 12:46:11'),
(13, 11, 10, '2025-11-12 12:46:15');

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
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`rewardID`);

--
-- Indexes for table `schoolevents`
--
ALTER TABLE `schoolevents`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `eventCreatorID` (`eventCreatorID`);

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
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `schoolevents`
--
ALTER TABLE `schoolevents`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_inventory`
--
ALTER TABLE `student_inventory`
  MODIFY `inventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `schoolevents`
--
ALTER TABLE `schoolevents`
  ADD CONSTRAINT `schoolevents_ibfk_1` FOREIGN KEY (`eventCreatorID`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_inventory`
--
ALTER TABLE `student_inventory`
  ADD CONSTRAINT `student_inventory_ibfk_2` FOREIGN KEY (`rewardID`) REFERENCES `rewards` (`rewardID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
