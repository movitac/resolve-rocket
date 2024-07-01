-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2024 at 10:50 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resolve_rocket`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `username`) VALUES
(1, 'Administrator One', '', '202cb962ac59075b964b07152d234b70', 'admin1'),
(2, 'Administrator Two', '', '202cb962ac59075b964b07152d234b70', 'admin2'),
(3, 'Administrator Three', '', '202cb962ac59075b964b07152d234b70', 'admin3');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `feedback`, `userid`, `date`) VALUES
(1, 'send feedback here', 2, '2024-05-08 08:22:57'),
(2, 'tq admin for helping me!!', 1, '2024-05-08 14:16:10'),
(3, 'pls improve bla bla bla\r\n', 1, '2024-05-08 15:02:27'),
(4, 'xxcvvxc', 2, '2024-05-09 03:06:50');

-- --------------------------------------------------------

--
-- Table structure for table `knowledgebase`
--

CREATE TABLE `knowledgebase` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `problem_type` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `knowledge` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `knowledgebase`
--

INSERT INTO `knowledgebase` (`id`, `title`, `problem_type`, `knowledge`, `photo`) VALUES
(5, 'PHP', 'Troubleshooting', 'PHP is a general-purpose scripting language geared towards web development. It was originally created by Danish-Canadian programmer Rasmus Lerdorf in 1993 and released in 1995. The PHP reference implementation is now produced by the PHP Group.', '2895fd1fe3b501b1b979b8e49a92482a.jpg'),
(7, 'Java', 'Software', 'Java is a high-level, class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.', 'acer nitro 5.jpg'),
(11, 'JavaScript', 'Software', 'JavaScript (JS) is a lightweight interpreted (or just-in-time compiled) programming language with first-class functions. While it is most well-known as the scripting language for Web pages, many non-browser environments also use it, such as Node.js, Apache CouchDB and Adobe Acrobat.', 'color-image-cartoon-front-view-laptop-computer-vector-14668518.jpg'),
(12, 'Computer Lag s', 'Hardware', 'fqwfwfwfwfwf test 123 test 123', 'road_asphalt_marking_130996_1920x1080.jpg'),
(13, 'test', 'Troubleshooting', 'qwe', 'acer nitro 5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `ticket_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `userid` int NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `problem_type` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_problem_type` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `status_ticket` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_id` int DEFAULT NULL,
  `archive` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_id`, `status`, `userid`, `subject`, `department`, `problem_type`, `other_problem_type`, `message`, `date`, `priority`, `status_ticket`, `admin_id`, `archive`) VALUES
(22, '99d0b323d6081ff43707', 2, 1, 'Internet', 'Customer Support', 'Troubleshooting', NULL, 'please help me', '2024-06-10 03:14:44', 'normal', 'closed', 3, 'yes'),
(23, '23ed3fdb7ee280cdc87f', 2, 1, 'test', 'Billing', 'Software', '', 'test', '2024-06-10 03:21:31', 'normal', 'in progress', 1, NULL),
(24, 'a307c26108bffb4ac3b8', 2, 1, 'test', 'Sales', 'Others', 'testing more', 'help', '2024-06-10 03:24:00', 'normal', 'closed', 1, 'yes'),
(25, '233a7f47b29b7eb99a6c', 2, 2, 'Calendar', 'Human Resources', 'Hardware', '', 'Doesnt appear', '2024-06-11 07:05:08', 'medium', 'in progress', 3, NULL),
(26, '47bf224c5518b37763ec', 2, 2, 'Mouse ', 'Human Resources', 'Hardware', '', 'My mouse is not working anymore. ', '2024-06-12 16:26:48', 'normal', 'closed', 1, NULL),
(27, 'c08f4a26d0e699fad387', 2, 1, 'Internet', 'Sales', 'Troubleshooting', '', 'My computer took so much time to load a web browser. Used the chatbot to help me but it is still not working.', '2024-06-12 16:42:36', 'urgent', 'closed', 1, NULL),
(28, '4f61eaff776d870dc1b0', 2, 2, 'PC', 'Technical Support', 'Others', 'The pc is making sound', 'the sound seems like the fan is stuck', '2024-06-12 16:45:47', 'urgent', 'in progress', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_reply`
--

CREATE TABLE `ticket_reply` (
  `id` int NOT NULL,
  `ticket_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_by` int NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_reply`
--

INSERT INTO `ticket_reply` (`id`, `ticket_id`, `send_by`, `message`, `date`) VALUES
(33, '24', 0, 'ok', '2024-06-10 03:52:26'),
(34, '24', 1, 'test', '2024-06-10 03:52:39'),
(35, '24', 0, 'df', '2024-06-10 03:53:25'),
(36, '24', 1, 'ff', '2024-06-10 03:53:32'),
(37, '25', 1, 'Hello', '2024-06-11 07:35:31'),
(38, '25', 0, 'Hello there', '2024-06-11 07:36:11'),
(39, '25', 1, 'Test123', '2024-06-11 07:36:32'),
(40, '25', 0, 'Thank u', '2024-06-11 07:37:23'),
(41, '23', 1, 'test123', '2024-06-12 16:24:09'),
(42, '26', 1, 'Hello we will be sending new mouse to you , tq .', '2024-06-12 16:34:58'),
(43, '25', 1, 'test123', '2024-06-12 16:35:47'),
(44, '22', 1, 'yes?', '2024-06-12 16:39:49'),
(45, '27', 1, 'Okay coming there soon.', '2024-06-12 16:43:37'),
(46, '28', 1, 'okay coming there. ', '2024-06-12 16:50:07'),
(47, '27', 0, 'Okay thank you.', '2024-06-12 16:53:27'),
(48, '27', 1, 'Alright done . Please send us a feedback regarding our work . Thank you so much !!', '2024-06-12 16:55:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_user` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `username`, `status_user`) VALUES
(1, 'John Doe', 'jdoe@mail.com', '202cb962ac59075b964b07152d234b70', 'jdoe', 'active'),
(2, 'Mary Joe', 'mjoe@gmail.com', '202cb962ac59075b964b07152d234b70', 'mjoe', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `knowledgebase`
--
ALTER TABLE `knowledgebase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_reply`
--
ALTER TABLE `ticket_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `knowledgebase`
--
ALTER TABLE `knowledgebase`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `ticket_reply`
--
ALTER TABLE `ticket_reply`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
