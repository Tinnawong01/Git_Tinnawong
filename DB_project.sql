-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 07:16 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_booking`
--

CREATE TABLE `tb_booking` (
  `id_booking` int(5) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_status` int(1) NOT NULL DEFAULT 1,
  `id_stadium` int(2) NOT NULL,
  `id_number` int(2) NOT NULL,
  `id_time` int(1) NOT NULL,
  `id_member` int(3) NOT NULL,
  `booking_admin` int(1) DEFAULT 2,
  `present_date` date DEFAULT NULL,
  `present_time` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_booking`
--

INSERT INTO `tb_booking` (`id_booking`, `booking_date`, `booking_status`, `id_stadium`, `id_number`, `id_time`, `id_member`, `booking_admin`, `present_date`, `present_time`) VALUES
(369, '2024-09-22', 1, 69, 87, 1, 71, 1, '0000-00-00', ''),
(370, '2024-09-22', 1, 69, 87, 2, 71, 1, '0000-00-00', ''),
(371, '2024-09-22', 1, 69, 87, 3, 71, 1, '0000-00-00', ''),
(372, '2024-09-22', 1, 69, 87, 4, 71, 1, '0000-00-00', ''),
(373, '2024-09-22', 1, 69, 87, 5, 71, 1, '0000-00-00', ''),
(374, '2024-09-22', 1, 69, 87, 6, 71, 1, '0000-00-00', ''),
(375, '2024-09-22', 1, 69, 87, 7, 71, 1, '0000-00-00', ''),
(376, '2024-09-22', 1, 69, 87, 8, 71, 1, '0000-00-00', ''),
(377, '2024-09-02', 2, 64, 78, 1, 86574, 2, '0000-00-00', ''),
(378, '2024-09-03', 2, 64, 78, 4, 38900, 2, '0000-00-00', ''),
(379, '2024-10-10', 2, 64, 78, 5, 78261, 2, '0000-00-00', ''),
(380, '2024-09-19', 2, 64, 78, 5, 20259, 2, '0000-00-00', ''),
(382, '2024-01-01', 2, 64, 78, 1, 72391, 2, '0000-00-00', ''),
(383, '2024-01-02', 1, 64, 82, 3, 86574, 2, '0000-00-00', ''),
(384, '2024-01-03', 1, 64, 83, 7, 58852, 2, '0000-00-00', ''),
(385, '2024-02-05', 1, 64, 82, 3, 38900, 2, '0000-00-00', ''),
(386, '2024-03-10', 1, 64, 78, 3, 71529, 2, '0000-00-00', ''),
(387, '2024-03-11', 2, 64, 82, 7, 58852, 2, '0000-00-00', ''),
(388, '2024-05-04', 1, 64, 78, 6, 58852, 2, '0000-00-00', ''),
(389, '2024-05-05', 1, 64, 82, 7, 78261, 2, '0000-00-00', ''),
(390, '2024-05-22', 2, 64, 78, 3, 54182, 2, '0000-00-00', ''),
(391, '2024-05-23', 2, 64, 82, 1, 78261, 2, '0000-00-00', ''),
(392, '2024-04-16', 2, 64, 82, 3, 78261, 2, '0000-00-00', ''),
(393, '2024-01-17', 2, 64, 82, 3, 86574, 2, '0000-00-00', ''),
(394, '2024-02-06', 2, 64, 84, 5, 58852, 2, '0000-00-00', ''),
(395, '2024-04-10', 2, 64, 83, 2, 72391, 2, '0000-00-00', ''),
(396, '2024-05-19', 2, 64, 82, 6, 86574, 2, '0000-00-00', ''),
(397, '2024-05-16', 2, 64, 78, 4, 71529, 2, '0000-00-00', ''),
(398, '2024-06-08', 2, 64, 83, 8, 78261, 2, '0000-00-00', ''),
(399, '2024-08-01', 2, 64, 78, 1, 20259, 2, '0000-00-00', ''),
(400, '2024-08-05', 2, 64, 83, 5, 78261, 2, '0000-00-00', ''),
(401, '2024-08-06', 2, 64, 82, 6, 86574, 2, '0000-00-00', ''),
(402, '2024-07-01', 2, 64, 84, 7, 71529, 2, '0000-00-00', ''),
(403, '2024-03-23', 2, 64, 83, 4, 86574, 2, '0000-00-00', ''),
(404, '2024-03-26', 2, 64, 78, 8, 72391, 2, '0000-00-00', ''),
(405, '2024-04-18', 1, 64, 84, 2, 54182, 2, '0000-00-00', ''),
(406, '2024-02-29', 1, 64, 83, 6, 86574, 2, '0000-00-00', ''),
(407, '2024-06-11', 1, 64, 84, 7, 54182, 2, '0000-00-00', ''),
(408, '2024-01-28', 2, 64, 82, 3, 86574, 2, '0000-00-00', ''),
(409, '2024-01-29', 2, 64, 82, 4, 71529, 2, '0000-00-00', ''),
(410, '2024-02-06', 2, 64, 78, 6, 78261, 2, '0000-00-00', ''),
(411, '2024-02-07', 2, 64, 83, 3, 20259, 2, '0000-00-00', ''),
(412, '2024-04-09', 2, 64, 78, 1, 20259, 2, '0000-00-00', ''),
(413, '2024-04-06', 2, 64, 84, 6, 38900, 2, '0000-00-00', ''),
(414, '2024-07-06', 2, 64, 82, 6, 20259, 2, '0000-00-00', ''),
(415, '2024-08-06', 2, 64, 83, 8, 72391, 2, '0000-00-00', ''),
(416, '2024-08-09', 2, 64, 82, 3, 72391, 2, '0000-00-00', ''),
(417, '2024-07-10', 2, 64, 84, 4, 78261, 2, '0000-00-00', ''),
(418, '2024-09-02', 2, 64, 78, 5, 24853, 2, '0000-00-00', ''),
(431, '2024-09-03', 2, 64, 78, 8, 17762, 2, '2024-09-03', '13:44:52'),
(432, '2024-09-05', 1, 64, 84, 3, 71, 1, NULL, NULL),
(433, '2024-09-05', 1, 64, 84, 4, 71, 1, NULL, NULL),
(435, '2024-09-06', 2, 64, 78, 8, 29483, 2, '2024-09-03', '19:09:47'),
(437, '2024-09-05', 2, 65, 79, 3, 98270, 2, '2024-09-03', '21:59:17'),
(442, '2024-09-08', 2, 64, 78, 4, 72391, 2, '2024-09-06', '18:47:25'),
(464, '2024-10-16', 2, 64, 78, 4, 86574, 2, '2024-10-16', '15:48:58'),
(465, '2024-11-16', 1, 68, 86, 5, 24853, 2, '2024-10-16', '16:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `tb_facuty`
--

CREATE TABLE `tb_facuty` (
  `Facuty_id` varchar(10) NOT NULL,
  `Facuty_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_facuty`
--

INSERT INTO `tb_facuty` (`Facuty_id`, `Facuty_name`) VALUES
('01', 'คณะวิทยาศาสตร์และวิศวกรรมศาสตร์'),
('02', 'คณะศิลปศาสตร์และวิทยาการจัดการ'),
('03', 'คณะทรัพยากรธรรมชาติและอุตสาหกรรมเกษตร'),
('04', 'คณะสาธารณสุขศาสตร์');

-- --------------------------------------------------------

--
-- Table structure for table `tb_major`
--

CREATE TABLE `tb_major` (
  `Major_id` varchar(10) NOT NULL,
  `Major_name` varchar(100) NOT NULL,
  `Facuty_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_major`
--

INSERT INTO `tb_major` (`Major_id`, `Major_name`, `Facuty_id`) VALUES
('A5201', 'หลักสูตรเทคโนโลยีการอาหาร', '03'),
('A5301', 'หลักสูตรพืชศาสตร์', '03'),
('A5401', 'หลักสูตรอาหารปลอดภัยและโภชนาการ', '03'),
('A5501', 'หลักสูตรทรัพยากรเกษตรและการจัดการการผลิต', '03'),
('A5601', 'หลักสูตรสัตวศาสตร์', '03'),
('B5502', 'หลักสูตรวิศวกรรมเครื่องกลและการผลิต', '01'),
('B5602', 'หลักสูตรวิศวกรรมไฟฟ้า', '01'),
('B5702', 'หลักสูตรวิศวกรรมโยธา', '01'),
('B5801', 'หลักสูตรเคมีประยุกต์', '01'),
('B6001', 'หลักสูตรวิทยาการคอมพิวเตอร์', '01'),
('B6101', 'หลักสูตรวิศวกรรมคมพิวเตอร์', '01'),
('B6201', 'หลักสูตรวิศวกรรมอุตสาหการ', '01'),
('B6401', 'หลักสูตรวิศวกรรมสิ่งแวดล้อมเพื่อการพัฒนาอย่างยั่งยืน', '01'),
('B6501', 'หลักสูตรวิทยาการข้อมูล', '01'),
('B6601', 'หลักสูตรเทคโนโลยีพลังงานเพื่อความยั่งยืน', '01'),
('C5101', 'หลักสูตรการจัดการ', '02'),
('C5201', 'หลักสูตรการบัญชี', '02'),
('C5601', 'หลักสูตรภาษาอังกฤษ', '02'),
('C5701', 'หลักสูตรการบริหารการเงินและการลงทุน', '02'),
('C5801', 'หลักสูตรนิติศาสตร์', '02'),
('C5901', 'หลักสูตรรัฐประศาสนศาสตร์', '02'),
('C6101', 'หลักสูตรการจัดการอุตสาหกรรมการบริการ แขนงวิชาการจัดการโรงแรม', '02'),
('C6102', 'หลักสูตรการจัดการอุตสาหกรรมการบริการ แขนงวิชาการจัดการท่องเที่ยวร่วมสมัย', '02'),
('D5101', 'หลักสูตรสาธารณสุขศาสตร์', '04'),
('D5201', 'หลักสูตรอนามัยสิ่งแวดล้อม', '04'),
('D5301', 'หลักสูตรอาชีวอนามัยและความปลอดภัย', '04');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `id_member` int(3) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `prefix` varchar(5) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `sex` varchar(3) NOT NULL,
  `department` varchar(10) NOT NULL,
  `faculty_id` varchar(10) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `std_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`id_member`, `email`, `password`, `prefix`, `fname`, `lname`, `sex`, `department`, `faculty_id`, `role`, `std_id`) VALUES
(71, 'admin@gmail.com', '$2y$10$NFc2ui8.7TZX6UT1p/NBP.G045OVBFGYaxY4V2xw58JG0Gpdi8gCm', 'นาย', 'admin1', 'พึ่งสุขแดง', '1', 'C5101', '02', 'admin', '0'),
(17762, 'test7@gmail.com', '$2y$10$YZDo90oWp34pPlJvqvEl0O6vJiuTrhRfqrSSKbBYFrJgSdmnSYVdK', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'B6601', '01', 'user', '0'),
(20259, 'test5@gmail.com', '$2y$10$/VXPyilvJZhZaIJJmbdFRu59waP8c4wi24XOqrEdFhLugDT1eKfw2', 'นาย', 'ทดสอบ5', 'ทดสอบ5', '1', 'B6501', '01', 'user', '0'),
(24853, 'test6@gmail.com', '$2y$10$Ir4tSA/renyJ.new7O0JsO81vp4zpMZplw7EGRl8Q2a.7n/eUqBm.', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'B6601', '01', 'user', '0'),
(29483, 'test8@gmail.com', '$2y$10$49b/WxuxjMz7GVmTrzIC1OVZjOyrtnHXAk8tqp2FL4B5WDjtjF3DW', 'นาย', 'ทินวงศ์', 'บอส', '1', 'B6601', '01', 'user', '0'),
(38900, 'test@gmail.com', '$2y$10$RD6bRp72xUmzut3GhKQEPeFO6b4gMgu4m5JqNEu.GoCq1UdkK8v.q', 'นาย', 'บอส', 'บอส', '1', 'B6001', '01', 'user', '0'),
(54182, 'user@gmail.com', '$2y$10$I0XNnsdBhaE.k4QzborU5.aGnKD5yyakm5B0sV/gzD0Na8tkjlkHW', 'นาย', 'ทดสอบ1', 'ทดสอบ1', '1', 'C5901', '02', 'user', '0'),
(58852, 'user1@gmail.com', '$2y$10$8T129w.QkAtX6bG6SZCJD.P4sB7O7BaRMeQ6QH08JNwnWxoCUI7LO', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'C6101', '02', 'user', '0'),
(71529, 'user3@gmail.com', '$2y$10$Y907VchNu2bugjk3ugmzYuxJmufFbT07AAhCsYQxHXC8s3U01BHeC', 'นาย', 'ทดสอบ2', 'ทดสอบ2', '1', 'B6401', '01', 'user', '0'),
(72391, 'aaa@gmail.com', '$2y$10$PkeRYHl.KxvtfgHHnHX.S.WmfvWJ12k0KpRREfSRLrVjQl3aAgqky', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'B6501', '01', 'user', '0'),
(78261, 'test3@gmail.com', '$2y$10$pb0jbIcazsqCSqvW5LTBJOzdIkVvBAsuiaYbcSTTvM/gQZrS2mG.O', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'B6601', '01', 'user', '0'),
(86574, 'test1@gmail.com', '$2y$10$wac2B1/iAn0MXOdI5HSuTu40digC.RJ01gFik/QxBnlKeU4AauGPu', 'นาย', 'บูม', 'พึ่งสุขแดง', '1', 'C6101', '02', 'user', '0'),
(98270, 'test9@gmail.com', '$2y$10$NdEvEvLdLqvTwuWCG6JCuuFhrsE8./ah/zQPhdUnLqpFLje6B3jPG', 'นาย', 'ทินวงศ์', 'พึ่งสุขแดง', '1', 'B5602', '01', 'user', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tb_number`
--

CREATE TABLE `tb_number` (
  `id_number` int(3) NOT NULL,
  `number_name` varchar(255) NOT NULL,
  `id_stadium` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_number`
--

INSERT INTO `tb_number` (`id_number`, `number_name`, `id_stadium`) VALUES
(78, 'V01', 64),
(79, 'B01', 65),
(82, 'V02', 64),
(83, 'V03', 64),
(84, 'V04', 64),
(85, 'F01', 67),
(86, 'N01', 68),
(87, 'P01', 69),
(89, 'T01', 66),
(90, 'T02', 66),
(91, 'T03', 66),
(92, 'T04', 66),
(93, 'B02', 65),
(94, 'N02', 68),
(95, 'N03', 68),
(96, 'P02', 69);

-- --------------------------------------------------------

--
-- Table structure for table `tb_stadium`
--

CREATE TABLE `tb_stadium` (
  `id_stadium` int(2) NOT NULL,
  `stadium_name` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `info_stadium` varchar(255) NOT NULL,
  `path_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_stadium`
--

INSERT INTO `tb_stadium` (`id_stadium`, `stadium_name`, `location`, `info_stadium`, `path_img`) VALUES
(64, 'สนามวอลเลย์บอล', 'ชั้น 1 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c951c7921.png'),
(65, 'สนามบาสเกตบอล', 'ชั้น 2 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c96839f87.png'),
(66, 'สนามแบดมินตัน', 'ชั้น 3 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c96ea23c4.png'),
(67, 'สนามฟุตบอล', 'ชั้น 4 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c9734edd7.png'),
(68, 'สนามเทนนิส', 'ชั้น 5 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c977f1073.png'),
(69, 'สนามเปตอง', 'ชั้น 6 อาคารปฏิบัติการวิทยาศาสตร์การกีฬา', 'ไซส์ขนาด 33 x 53 เมตร หรือ 1,749 ตารางเมตร', '6661c97c96b79.png');

-- --------------------------------------------------------

--
-- Table structure for table `tb_time_slot`
--

CREATE TABLE `tb_time_slot` (
  `id_time` int(1) NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_time_slot`
--

INSERT INTO `tb_time_slot` (`id_time`, `time`) VALUES
(1, '08:00 - 09:00'),
(2, '09:00 - 10:00'),
(3, '10:00 - 11:00'),
(4, '11:00 - 12:00'),
(5, '12:00 - 13:00'),
(6, '13:00 - 14:00'),
(7, '14:00 - 15:00'),
(8, '15:00 - 16:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_booking`
--
ALTER TABLE `tb_booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `stadium_id_booking` (`id_stadium`),
  ADD KEY `time_id_booking` (`id_time`),
  ADD KEY `member_id_booking` (`id_member`),
  ADD KEY `number_id_booking` (`id_number`);

--
-- Indexes for table `tb_facuty`
--
ALTER TABLE `tb_facuty`
  ADD PRIMARY KEY (`Facuty_id`);

--
-- Indexes for table `tb_major`
--
ALTER TABLE `tb_major`
  ADD PRIMARY KEY (`Major_id`),
  ADD KEY `fore_facury` (`Facuty_id`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id_member`),
  ADD KEY `tb_member_fk_tb_facuty` (`faculty_id`),
  ADD KEY `tb_member_fk_tb_major` (`department`);

--
-- Indexes for table `tb_number`
--
ALTER TABLE `tb_number`
  ADD PRIMARY KEY (`id_number`),
  ADD KEY `stadium_id_number` (`id_stadium`);

--
-- Indexes for table `tb_stadium`
--
ALTER TABLE `tb_stadium`
  ADD PRIMARY KEY (`id_stadium`);

--
-- Indexes for table `tb_time_slot`
--
ALTER TABLE `tb_time_slot`
  ADD PRIMARY KEY (`id_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_booking`
--
ALTER TABLE `tb_booking`
  MODIFY `id_booking` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=470;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id_member` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `tb_number`
--
ALTER TABLE `tb_number`
  MODIFY `id_number` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `tb_stadium`
--
ALTER TABLE `tb_stadium`
  MODIFY `id_stadium` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tb_time_slot`
--
ALTER TABLE `tb_time_slot`
  MODIFY `id_time` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_booking`
--
ALTER TABLE `tb_booking`
  ADD CONSTRAINT `member_id_booking` FOREIGN KEY (`id_member`) REFERENCES `tb_member` (`id_member`),
  ADD CONSTRAINT `number_id_booking` FOREIGN KEY (`id_number`) REFERENCES `tb_number` (`id_number`),
  ADD CONSTRAINT `stadium_id_booking` FOREIGN KEY (`id_stadium`) REFERENCES `tb_stadium` (`id_stadium`),
  ADD CONSTRAINT `time_id_booking` FOREIGN KEY (`id_time`) REFERENCES `tb_time_slot` (`id_time`);

--
-- Constraints for table `tb_major`
--
ALTER TABLE `tb_major`
  ADD CONSTRAINT `fore_facury` FOREIGN KEY (`Facuty_id`) REFERENCES `tb_facuty` (`Facuty_id`);

--
-- Constraints for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD CONSTRAINT `tb_member_fk_tb_facuty` FOREIGN KEY (`faculty_id`) REFERENCES `tb_facuty` (`Facuty_id`),
  ADD CONSTRAINT `tb_member_fk_tb_major` FOREIGN KEY (`department`) REFERENCES `tb_major` (`Major_id`);

--
-- Constraints for table `tb_number`
--
ALTER TABLE `tb_number`
  ADD CONSTRAINT `stadium_id_number` FOREIGN KEY (`id_stadium`) REFERENCES `tb_stadium` (`id_stadium`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
