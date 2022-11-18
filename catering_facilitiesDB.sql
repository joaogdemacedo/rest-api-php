-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 18, 2022 at 11:29 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering_facilities`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `facility_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `username`, `password`, `facility_id`) VALUES
(16, 'jackass', '$argon2id$v=19$m=65536,t=4,p=1$YXVXZWpLR0tQMy5kNkhjbQ$D/Je9pWb8htiquC4LsVLvfvgFCSBjEJwpczPbvbqHH4', 1),
(17, 'joao', '$argon2id$v=19$m=65536,t=4,p=1$S0JwV2dGZjRMNFlVcUZOMg$idvwp1hXD0WPT2w+M0B3knaCrgXS0HjLmiDvg0EwGfI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`id`, `name`, `created_at`, `location_id`) VALUES
(1, 'facility1', '2022-11-09 17:06:58', 2),
(6, 'nameAI', '2022-11-10 23:04:26', 1),
(7, 'nameAI2', '2022-11-10 23:04:46', 2),
(8, 'insertFAcility', '2022-11-10 23:23:58', 1),
(9, 'semid', '2022-11-10 23:27:14', 1),
(11, 'name10', '2022-11-10 23:35:49', 1),
(12, 'name12', '2022-11-10 23:38:09', 1),
(14, 'name13', '2022-11-10 23:38:35', 1),
(15, 'name15', '2022-11-10 23:45:48', 1),
(19, 'name18', '2022-11-10 23:51:24', 1),
(21, 'name20', '2022-11-10 23:54:09', 1),
(32, 'name111111', '2022-11-11 09:42:10', 1),
(33, 'name111d111', '2022-11-11 09:43:27', 1),
(35, 'name111d11ddd1', '2022-11-11 09:43:53', 1),
(36, 'name36', '2022-11-11 09:44:04', 1),
(37, 'name37', '2022-11-11 09:46:24', 1),
(45, 'name39', '2022-11-11 10:38:11', 1),
(46, 'name40', '2022-11-11 10:39:09', 1),
(49, 'name41', '2022-11-11 10:50:32', 2),
(51, 'namecarall', '2022-11-11 11:00:19', 3),
(52, 'name42', '2022-11-11 11:01:30', 3),
(53, 'name43', '2022-11-11 11:08:44', 3),
(55, 'joaozinho', '2022-11-11 15:56:48', 4),
(57, 'joaojoao', '2022-11-11 16:00:54', 3),
(60, 'joaojoaojoao', '2022-11-11 16:02:28', 3),
(63, 'filipemacedo', '2022-11-11 16:11:16', 1),
(65, 'filipemacedojjj', '2022-11-11 16:12:25', 1),
(67, 'filipemacekkkdojjj', '2022-11-11 16:12:53', 1),
(69, 'filipemacssekkkdojjj', '2022-11-11 16:14:16', 1),
(71, 'filipemacssekkkssdojjj', '2022-11-11 16:15:58', 1),
(73, 'filipemacssekkksmsdojjj', '2022-11-11 16:17:18', 1),
(74, 'pinheiro', '2022-11-11 16:18:52', 2),
(76, 'pinheiro2', '2022-11-11 16:19:09', 2),
(78, 'pinheiro3', '2022-11-11 16:19:50', 2),
(81, 'pinheiro4', '2022-11-11 16:21:22', 2),
(83, 'pinheiro4', '2022-11-11 16:22:44', 3),
(84, 'pinheiro5', '2022-11-11 16:23:24', 3),
(85, 'pinheiro6', '2022-11-11 16:24:38', 3),
(87, 'pinheiro69', '2022-11-11 17:45:04', 3),
(91, 'pinheiro00', '2022-11-11 17:48:15', 3),
(93, 'pinheiro01', '2022-11-11 17:50:56', 3),
(96, 'pinheiro02', '2022-11-11 17:52:25', 3),
(98, 'pinheiro03', '2022-11-11 17:52:59', 3),
(99, 'jackass', '2022-11-11 17:54:11', 4),
(103, 'pinheiro06', '2022-11-11 23:33:31', 4),
(105, 'pinheiro07', '2022-11-11 23:38:43', 4),
(107, 'pinheiro08', '2022-11-11 23:39:30', 4),
(110, 'pinheiro101', '2022-11-11 23:40:44', 4),
(111, 'pinheiro102', '2022-11-11 23:50:06', 4),
(113, 'pinheiro103', '2022-11-11 23:50:46', 4),
(114, 'pinheiro104', '2022-11-11 23:51:42', 4),
(116, 'pinheiro105', '2022-11-11 23:52:50', 4),
(117, 'pinheiro106', '2022-11-11 23:53:01', 4),
(119, 'pinheiro107', '2022-11-12 00:09:01', 4),
(120, 'pinheiro107', '2022-11-12 00:09:31', 1),
(122, 'update', '2022-11-12 00:47:07', 1),
(123, 'update2', '2022-11-12 00:49:54', 1),
(127, 'update126', '2022-11-16 10:02:01', 1),
(130, 'facility130', '2022-11-16 10:05:08', 1),
(131, 'facility131', '2022-11-16 11:14:39', 1),
(133, 'facility133', '2022-11-16 17:05:34', 1),
(134, 'Test', '2022-11-17 13:09:45', 1),
(136, 'Test11111', '2022-11-17 13:12:20', 1),
(138, 'Testpocrlh', '2022-11-17 13:27:36', 1),
(140, 'Testposssscrlh', '2022-11-17 13:28:40', 1),
(142, 'testecreated1', '2022-11-17 13:33:48', 1),
(143, 'testecreated3', '2022-11-17 13:34:16', 1),
(144, 'testecreated4', '2022-11-17 13:34:48', 1),
(146, '7GAqFRas3r', '2022-11-17 14:22:47', 1),
(147, 'AYd012UQaN', '2022-11-17 14:31:11', 1),
(148, 'ObA7k8K4W5', '2022-11-17 14:39:32', 1),
(149, 'wr4Lk05i0T', '2022-11-17 14:39:59', 1),
(150, 'mLHrXjGY72', '2022-11-17 14:40:54', 1),
(151, 'lsHkZpc6i2', '2022-11-17 14:46:03', 1),
(152, 'UW0Am94s6u', '2022-11-17 14:46:38', 1),
(153, 'ffDV27XZHO', '2022-11-17 14:49:08', 1),
(154, '0CxGmILY6P', '2022-11-17 15:00:02', 1),
(155, 'facility155', '2022-11-17 20:30:55', 1),
(159, 'Last Facility', '2022-11-18 10:59:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `facility_tag`
--

CREATE TABLE `facility_tag` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `facility_tag`
--

INSERT INTO `facility_tag` (`id`, `facility_id`, `tag_id`) VALUES
(124, 1, 61),
(125, 1, 62),
(126, 1, 63),
(19, 49, 16),
(18, 49, 32),
(21, 51, 16),
(20, 51, 32),
(82, 51, 45),
(83, 51, 46),
(81, 51, 47),
(23, 52, 16),
(22, 52, 32),
(25, 53, 16),
(24, 53, 32),
(79, 55, 45),
(80, 55, 46),
(78, 55, 47),
(31, 67, 37),
(33, 73, 16),
(32, 73, 37),
(35, 74, 16),
(34, 74, 37),
(37, 76, 16),
(36, 76, 37),
(39, 78, 16),
(38, 78, 37),
(41, 81, 16),
(40, 81, 37),
(142, 83, 2),
(141, 83, 3),
(43, 83, 16),
(42, 83, 37),
(44, 84, 37),
(45, 84, 38),
(46, 85, 37),
(47, 85, 38),
(76, 99, 45),
(77, 99, 46),
(75, 99, 47),
(49, 110, 38),
(50, 111, 38),
(51, 113, 38),
(52, 114, 38),
(53, 116, 38),
(55, 117, 38),
(54, 117, 39),
(57, 119, 38),
(56, 119, 39),
(59, 120, 38),
(58, 120, 40),
(60, 122, 41),
(62, 123, 41),
(84, 127, 43),
(85, 127, 48),
(89, 130, 49),
(88, 130, 50),
(98, 131, 45),
(99, 131, 46),
(96, 131, 49),
(95, 131, 50),
(97, 131, 54),
(101, 133, 49),
(100, 133, 50),
(102, 133, 55),
(103, 133, 56),
(104, 136, 50),
(105, 138, 50),
(106, 140, 50),
(107, 142, 50),
(108, 143, 50),
(109, 144, 50),
(111, 146, 50),
(112, 147, 50),
(113, 148, 50),
(114, 149, 50),
(115, 150, 50),
(116, 151, 50),
(117, 152, 50),
(118, 153, 50),
(127, 154, 1),
(128, 154, 2),
(119, 154, 50),
(134, 155, 2),
(133, 155, 3),
(135, 155, 65),
(144, 159, 2),
(143, 159, 3);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `city`, `address`, `zip_code`, `country_code`, `phone_number`) VALUES
(1, 'city1', 'address1', 'zipcode1', 'countrycode1', 'phonenumber1'),
(2, 'city2', 'ad2', 'zip2', 'code2', 'phone2'),
(3, 'city3AI', 'sdasd', 'asd', 'asda', 'asdad'),
(4, 'Cidade4', 'Add4', 'Zip4', 'Code4', 'Phone4');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`) VALUES
(62, 'CtU1cIjfP5'),
(40, 'jackass'),
(47, 'jackdddddass'),
(37, 'joaotag'),
(57, 'K48NtSMjqF'),
(12, 'mmm'),
(14, 'mmskkm'),
(13, 'mmsm'),
(63, 'okRtapfRlg'),
(38, 'pinheirotag'),
(59, 'pKcVoobS5z'),
(58, 's4Zo8wSVgq'),
(60, 'saTtNbx6Pg'),
(39, 'sexta'),
(36, 'tag filipe'),
(45, 'tag jigsaw'),
(46, 'tag pega pega'),
(44, 'tag tetra'),
(1, 'tag1'),
(2, 'tag2'),
(3, 'tag3'),
(50, 'tag50'),
(55, 'tag500'),
(51, 'tag51'),
(52, 'tag52'),
(53, 'tag53'),
(54, 'tag54'),
(64, 'tag64'),
(67, 'tag67'),
(4, 'tagAI'),
(5, 'tagAI2'),
(6, 'tagexist'),
(16, 'tagexiste'),
(8, 'tagexkkkkist'),
(15, 'taglastinserted'),
(7, 'tagnew'),
(48, 'tagnovanova'),
(49, 'tagnovanovaaaa'),
(65, 'tagultima'),
(41, 'tagupdate'),
(42, 'tagupdate2'),
(43, 'tagupdate222'),
(25, 'testar tags'),
(31, 'testar tags tag'),
(32, 'testar tags tag tag'),
(11, 'teste'),
(56, 'testesintegracao'),
(9, 'testingLastInsert'),
(10, 'testingLastInsert2'),
(33, 'update tag1'),
(35, 'update tag11'),
(34, 'update tag2'),
(61, 'w6Sc1FwcgT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `employees_ibfk_1` (`facility_id`);

--
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`location_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `facility_tag`
--
ALTER TABLE `facility_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facility_id_2` (`facility_id`,`tag_id`),
  ADD KEY `facility_id` (`facility_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `facility`
--
ALTER TABLE `facility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `facility_tag`
--
ALTER TABLE `facility_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facility` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facility`
--
ALTER TABLE `facility`
  ADD CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `facility_tag`
--
ALTER TABLE `facility_tag`
  ADD CONSTRAINT `facility_tag_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facility` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facility_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
