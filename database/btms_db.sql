-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2025 at 06:13 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `btms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `circular`
--

CREATE TABLE `circular` (
  `ID` int NOT NULL,
  `DeptCode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval1` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval2` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval3` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval4` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval5` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval6` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Approval7` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `circular`
--

INSERT INTO `circular` (`ID`, `DeptCode`, `Approval1`, `Approval2`, `Approval3`, `Approval4`, `Approval5`, `Approval6`, `Approval7`) VALUES
(1, 'JP', 'Mr. OKI', 'Mr.Muramoto', '', '', '', '', ''),
(3, '31', 'Mr. OKI', 'Mr.Muramoto', '', '', '', '', ''),
(5, '38', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Yoga', 'Mr.Yudia Eka', '', '', ''),
(6, '39', 'Mr. OKI', 'Mr.Muramoto', 'Ms.Takimoto', 'Mr.Manuarang', 'Mr.Hidayah', '', ''),
(7, '41', 'Mr. OKI', 'Mr.Muramoto', 'Ms.Takimoto', 'Mrs. Santy', '', '', ''),
(10, '44', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Sujaka Bravo', '', '', '', ''),
(16, '58', 'Mr. OKI', 'Mr. Muramoto', 'Mr. Sujaka Bravo', 'Mr.Muin Sujudi', '', '', ''),
(17, '62', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Taufik', '', '', '', ''),
(20, '67', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Bravo', 'Mr.Putu P', '', '', ''),
(22, '70', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Shigeru Sonoda', 'Mr. Ivan Imanuddin', '', '', ''),
(23, '71', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Shigeru Sonoda', 'Mr. Ivan Imanuddin', '', '', ''),
(24, '72', 'Mr. OKI', 'Mr.Muramoto', 'Mr. Sujaka Bravo', 'Mr.Putu Pangkat', '', '', ''),
(33, '78', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Bravo', 'Mr.Sujaka Bravo', '', '', ''),
(38, '34', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Bravo', 'Mr.Sujaka Bravo', '', '', ''),
(39, '66', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Bravo', 'Mr.Sujaka Bravo', '', '', ''),
(40, '73', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Bravo', 'Mr.Putu Pangkat', '', '', ''),
(41, '77', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Made Joko', '', '', '', ''),
(44, '81', 'Mr. OKI', 'Mr.Muramoto', '', '', '', '', ''),
(45, '86', 'Mr. OKI', 'Mr.Muramoto', 'Mr.Sujaka Brav', '', '', '', ''),
(47, '84', 'Mr. OKI', 'Mr.Muramoto', 'Ms.Takimoto', 'Mr.Nugroho', '', '', ''),
(49, 'JG', 'Mr. OKI', 'Mr.Muramoto', '', '', '', '', ''),
(50, '54', 'Mr.Oki', 'Mr.Muramoto', 'Mr.Cindy', '', '', '', ''),
(51, '56', 'Mr.Oki', 'Mr.Muramoto', 'Mr.Ery Edy Syam', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `clasificcode`
--

CREATE TABLE `clasificcode` (
  `Code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Classification` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clasificcode`
--

INSERT INTO `clasificcode` (`Code`, `Classification`) VALUES
('1', 'Operator'),
('10', 'Expatriate'),
('2', 'NON OT'),
('3', 'MANAGER'),
('4', 'STAFF'),
('5', 'Non Operator'),
('6', 'Driver'),
('7', 'OFFICE'),
('8', 'NON OPERATOR SHIFT'),
('9', 'ASMEN OT');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `dept_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_code`, `name`) VALUES
(64, '39', 'General Affairs'),
(65, '90', 'Manufacturing SMD'),
(66, '71', 'Maint. Engineering'),
(67, '67', 'Packing'),
(68, '69', 'Manufacturing 2'),
(69, '38', 'Env & Utility Control'),
(70, '73', 'SMD YRS'),
(71, '87', 'ASSY YRS'),
(72, '54', 'Production Control'),
(73, '77', 'Adm 2'),
(74, '70', 'Process Engineering'),
(75, '86', 'Manufacturing Dept'),
(76, '80', 'Engineering'),
(77, '45', 'Inspection'),
(78, '58', 'Shipping Control'),
(79, '91', 'Accounting'),
(80, '56', 'Quality'),
(81, '50', 'Material Control'),
(82, '41', 'Human Resources'),
(83, '62', 'System Engineering'),
(84, '93', 'Japanese');

-- --------------------------------------------------------

--
-- Table structure for table `desigcode`
--

CREATE TABLE `desigcode` (
  `Code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `desigcode`
--

INSERT INTO `desigcode` (`Code`, `Description`) VALUES
('1', 'Asst. Mgr'),
('10', 'Operator'),
('15', 'Technician'),
('16', 'Driver'),
('2', 'Cleaner'),
('29', 'Group Leader'),
('3', 'Clerk'),
('35', 'SSO'),
('36', 'Financial Advisor'),
('4', 'Engineer'),
('42', 'Research & Development Advisor'),
('43', 'Director'),
('5', 'General Manager'),
('6', 'Leader'),
('8', 'Manager'),
('9', 'Officer');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `created_at`) VALUES
(1, 'Staff', '2025-08-19 07:17:49'),
(2, 'Supervisor', '2025-08-19 07:17:49'),
(3, 'Manager', '2025-08-19 07:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `EmpNo` varchar(20) NOT NULL,
  `Name` varchar(512) NOT NULL,
  `DeptCode` varchar(10) DEFAULT NULL,
  `ClasCode` varchar(10) DEFAULT NULL,
  `DestCode` varchar(10) DEFAULT NULL,
  `Sex` varchar(1) DEFAULT NULL,
  `Resigned` varchar(512) DEFAULT NULL,
  `GrpCode` varchar(10) DEFAULT NULL,
  `JoinDate` date DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `designation_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `EmpNo`, `Name`, `DeptCode`, `ClasCode`, `DestCode`, `Sex`, `Resigned`, `GrpCode`, `JoinDate`, `department_id`, `designation_id`) VALUES
(1, '2001785', 'Julizal', '39', '1', '6', 'M', NULL, '2', '2000-02-01', NULL, NULL),
(2, '2001786', 'Boy Karawan', '39', '1', '2', 'M', NULL, '2', '2000-02-01', NULL, NULL),
(3, '2004790', 'Titik Pujiati', '90', '8', '3', 'F', NULL, '3', '2000-04-08', NULL, NULL),
(4, '2005814', 'Manuarang Tua Manalu', '39', '9', '8', 'M', NULL, '3', '2000-05-08', NULL, NULL),
(5, '2006833', 'Trubus Sulistiyo', '71', '8', '15', 'M', NULL, '49', '2000-06-05', NULL, NULL),
(6, '2006839', 'Neneng Ekawati', '67', '1', '10', 'F', NULL, '8', '2000-06-06', NULL, NULL),
(7, '2009901', 'Ani Yusita', '69', '2', '1', 'F', NULL, '3', '2000-09-04', NULL, NULL),
(8, '2009923', 'Yudia Eka Putra', '38', '2', '1', 'M', NULL, '3', '2000-09-04', NULL, NULL),
(9, '2010973', 'Ida Afrita', '73', '1', '29', 'F', NULL, '8', '2000-10-09', NULL, NULL),
(10, '2011015', 'Muslim', '39', '6', '16', 'M', NULL, '7', '2000-11-01', NULL, NULL),
(11, '2102069', 'Polmen Sihombing', '87', '1', '6', 'M', NULL, '8', '2001-02-02', NULL, NULL),
(12, '2206106', 'Cindy Cahyadi', '54', '3', '5', 'M', NULL, '3', '2002-06-17', NULL, NULL),
(13, '2206162', 'Made Joko Santosa', '77', '2', '1', 'M', NULL, '3', '2002-06-24', NULL, NULL),
(14, '2206164', 'Manatap', '70', '4', '4', 'M', NULL, '3', '2002-06-24', NULL, NULL),
(15, '2206172', 'Sujaka Bravo', '86', '3', '5', 'M', NULL, '3', '2002-06-24', NULL, NULL),
(16, '2207180', 'Robi Salam', '38', '8', '10', 'M', NULL, '49', '2002-07-01', NULL, NULL),
(17, '2207197', 'Ivan Imaduddin', '80', '3', '5', 'M', NULL, '3', '2002-07-29', NULL, NULL),
(18, '2209216', 'Erni Rosana Purba', '87', '5', '3', 'F', NULL, '3', '2002-09-24', NULL, NULL),
(19, '2209244', 'Sri Nirwani Harahap', '73', '1', '10', 'F', NULL, '8', '2002-09-24', NULL, NULL),
(20, '2309314', 'Musrifan', '39', '1', '2', 'M', NULL, '4', '2003-09-30', NULL, NULL),
(21, '2404389', 'Julita Lastiur Simarmata', '45', '1', '6', 'F', NULL, '8', '2004-04-19', NULL, NULL),
(22, '2410268', 'Ari Susanto', '38', '8', '6', 'M', NULL, '8', '2004-10-04', NULL, NULL),
(23, '2502310', 'Masruri', '73', '1', '6', 'M', NULL, '8', '2005-02-01', NULL, NULL),
(24, '2502314', 'Saiful Bahri', '69', '1', '15', 'M', NULL, '8', '2005-02-01', NULL, NULL),
(25, '2603382', 'M. Hidayah', '39', '2', '1', 'M', NULL, '3', '2006-03-01', NULL, NULL),
(26, '2609462', 'Haidil', '73', '1', '6', 'M', NULL, '8', '2006-09-18', NULL, NULL),
(27, '2809772', 'M. Muin Sujudi', '58', '3', '1', 'M', NULL, '3', '2008-09-02', NULL, NULL),
(28, '2809786', 'Imam Edi Raharjo', '38', '1', '6', 'M', NULL, '49', '2008-09-22', NULL, NULL),
(29, '3100101', 'M. Dwi Nugroho', '91', '3', '8', 'M', NULL, '3', '2010-01-04', NULL, NULL),
(30, '3100562', 'Igral Eliyas', '56', '4', '4', 'M', NULL, '49', '2010-05-24', NULL, NULL),
(31, '3110517', 'Wili Brodus Oni Meku', '39', '1', '2', 'M', NULL, '8', '2011-05-19', NULL, NULL),
(32, '31107140', 'Santia Br. Sibuea', '73', '1', '6', 'F', NULL, '8', '2011-07-27', NULL, NULL),
(33, '31107143', 'Rudy Priyantoro', '87', '1', '6', 'M', NULL, '8', '2011-08-01', NULL, NULL),
(34, '3110852', 'Dedy Heryanto', '87', '1', '15', 'M', NULL, '8', '2011-08-25', NULL, NULL),
(35, '3110903', 'Henda Astalia', '73', '1', '6', 'F', NULL, '8', '2011-09-04', NULL, NULL),
(36, '3111033', 'Medriko', '73', '1', '35', 'M', NULL, '8', '2011-10-17', NULL, NULL),
(37, '3111053', 'Tiodin Marince', '69', '1', '6', 'F', NULL, '8', '2011-10-25', NULL, NULL),
(38, '3111138', 'Tri Ayu Lestari', '87', '1', '35', 'F', NULL, '8', '2011-11-16', NULL, NULL),
(39, '3120525', 'Wiwi Hetiamala', '87', '1', '35', 'F', NULL, '8', '2012-05-22', NULL, NULL),
(40, '3120534', 'Peni Roy', '87', '1', '35', 'M', NULL, '8', '2012-05-28', NULL, NULL),
(41, '3120705', 'Agus Ryanto', '87', '1', '10', 'M', NULL, '8', '2012-07-10', NULL, NULL),
(42, '3120806', 'Tetty Herlina Br Tamba', '73', '1', '6', 'F', NULL, '8', '2012-08-21', NULL, NULL),
(43, '3120905', 'Yol Anabrang Panggabean', '54', '7', '9', 'M', NULL, '3', '2012-09-10', NULL, NULL),
(44, '3121042', 'Aprilyani Kertina Siagian', '73', '1', '10', 'F', NULL, '8', '2012-10-29', NULL, NULL),
(45, '3121201', 'Lidia Simanjuntak', '73', '1', '10', 'F', NULL, '8', '2012-12-06', NULL, NULL),
(46, '3121207', 'Hotria Purba', '73', '1', '10', 'F', NULL, '8', '2012-12-12', NULL, NULL),
(47, '3121215', 'Juni Elfrida Hasugian', '73', '1', '10', 'F', NULL, '8', '2012-12-14', NULL, NULL),
(48, '3130101', 'Riko Andreas Ginting', '87', '1', '10', 'M', NULL, '8', '2013-01-02', NULL, NULL),
(49, '3130104', 'Marnikana Silalahi', '73', '1', '35', 'F', NULL, '8', '2013-01-03', NULL, NULL),
(50, '3130115', 'Lishabri', '73', '1', '35', 'M', NULL, '8', '2013-01-10', NULL, NULL),
(51, '3130125', 'Christyan Kuheba', '73', '1', '6', 'M', NULL, '8', '2013-01-15', NULL, NULL),
(52, '3130140', 'Mascko Gultom', '73', '1', '10', 'M', NULL, '8', '2013-01-25', NULL, NULL),
(53, '3130149', 'Jarot Iswanto', '87', '1', '10', 'M', NULL, '8', '2013-01-28', NULL, NULL),
(54, '3130201', 'Fadjar Sapta Putro', '87', '1', '10', 'M', NULL, '8', '2013-02-01', NULL, NULL),
(55, '3130309', 'Zeronikho Caniago', '73', '1', '35', 'M', NULL, '8', '2013-03-22', NULL, NULL),
(56, '3130410', 'Robin Sarimujie', '73', '1', '35', 'M', NULL, '8', '2013-04-16', NULL, NULL),
(57, '3130505', 'Rismaida Yulynar Sihaloho', '87', '5', '10', 'F', NULL, '3', '2013-05-02', NULL, NULL),
(58, '3130515', 'Wahyudi Sugara', '87', '1', '35', 'M', NULL, '8', '2013-05-21', NULL, NULL),
(59, '3130523', 'Jepri', '73', '1', '35', 'M', NULL, '8', '2013-05-31', NULL, NULL),
(60, '3130705', 'Al Ghapur', '87', '1', '35', 'M', NULL, '8', '2013-07-13', NULL, NULL),
(61, '3130708', 'Berdikari Gultom', '50', '1', '10', 'M', NULL, '8', '2013-07-16', NULL, NULL),
(62, '3130718', 'Yezriel Hamonangan Ginting', '73', '1', '10', 'M', NULL, '8', '2013-07-31', NULL, NULL),
(63, '3130819', 'Syafrizal', '50', '1', '6', 'M', NULL, '8', '2013-08-20', NULL, NULL),
(64, '3130829', 'Yuli Ernawati', '73', '1', '10', 'F', NULL, '8', '2013-08-19', NULL, NULL),
(65, '3130848', 'Elpriaty Samosir', '69', '1', '10', 'F', NULL, '8', '2013-08-30', NULL, NULL),
(66, '3130871', 'Naomi Ida Irawati Simangunsong', '73', '1', '10', 'F', NULL, '8', '2013-08-30', NULL, NULL),
(67, '3130906', 'Andri Wijaya', '87', '1', '10', 'M', NULL, '8', '2013-09-04', NULL, NULL),
(68, '3130916', 'Naqiuddin Muslimin', '73', '1', '10', 'M', NULL, '8', '2013-09-05', NULL, NULL),
(69, '3130927', 'Mediani Restia', '50', '7', '9', 'F', NULL, '3', '2013-09-10', NULL, NULL),
(70, '3130945', 'Kiki Fatimah', '73', '1', '10', 'F', NULL, '8', '2013-09-16', NULL, NULL),
(71, '3130987', 'Dian Ekowati', '73', '1', '6', 'F', NULL, '8', '2013-09-24', NULL, NULL),
(72, '3131006', 'Yupi Andriyani', '69', '1', '6', 'F', NULL, '8', '2013-10-04', NULL, NULL),
(73, '3131025', 'Vivi Imayani Simanjuntak', '73', '1', '10', 'F', NULL, '8', '2013-10-07', NULL, NULL),
(74, '3131036', 'Hardiati', '73', '1', '10', 'F', NULL, '8', '2013-10-14', NULL, NULL),
(75, '3131042', 'Riki Apriyan', '73', '1', '10', 'M', NULL, '8', '2013-10-18', NULL, NULL),
(76, '3131045', 'Dewita Tampubolon', '58', '5', '10', 'F', NULL, '3', '2013-10-28', NULL, NULL),
(77, '3131051', 'Norita Lance Br Sitorus', '87', '1', '6', 'F', NULL, '8', '2013-10-28', NULL, NULL),
(78, '3131101', 'Muammar Kadavie', '73', '1', '10', 'M', NULL, '8', '2013-11-01', NULL, NULL),
(79, '3131135', 'Husni Tamrin', '73', '1', '10', 'M', NULL, '8', '2013-11-21', NULL, NULL),
(80, '3131218', 'Edo Zulputra', '67', '1', '10', 'M', NULL, '8', '2013-12-02', NULL, NULL),
(81, '3131222', 'Bisri Mukhojin', '73', '1', '35', 'M', NULL, '8', '2013-12-05', NULL, NULL),
(82, '3131225', 'Ari Widayanto', '73', '1', '6', 'M', NULL, '8', '2013-12-10', NULL, NULL),
(83, '3131238', 'Surti', '87', '1', '10', 'F', NULL, '8', '2013-12-10', NULL, NULL),
(84, '3140104', 'Okky Hariabi', '50', '1', '10', 'M', NULL, '8', '2014-01-11', NULL, NULL),
(85, '3140108', 'Lermi Silalahi', '73', '1', '10', 'F', NULL, '8', '2014-01-17', NULL, NULL),
(86, '3140113', 'Suprijal', '50', '1', '10', 'M', NULL, '3', '2014-01-20', NULL, NULL),
(87, '3140127', 'Oktavianus Tarigan', '73', '1', '10', 'M', NULL, '8', '2014-01-28', NULL, NULL),
(88, '3140202', 'Riki Martono', '73', '1', '6', 'M', NULL, '8', '2014-02-03', NULL, NULL),
(89, '3140212', 'Mustonginah', '73', '1', '10', 'F', NULL, '8', '2014-02-14', NULL, NULL),
(90, '3140215', 'Franita', '87', '1', '10', 'F', NULL, '8', '2014-02-24', NULL, NULL),
(91, '3140304', 'Agustynah. S', '73', '1', '10', 'F', NULL, '8', '2014-03-03', NULL, NULL),
(92, '3140508', 'Kiki Rizki Sintia Devy', '73', '1', '10', 'F', NULL, '8', '2014-05-05', NULL, NULL),
(93, '3140526', 'Ulil Albab', '69', '1', '35', 'M', NULL, '8', '2014-05-26', NULL, NULL),
(94, '3141004', 'Dian Degusty', '41', '4', '14', 'F', NULL, '3', '2014-10-01', NULL, NULL),
(95, '3141010', 'Sofyan Yusuf', '73', '1', '10', 'M', NULL, '8', '2014-10-01', NULL, NULL),
(96, '3141012', 'Chairunnisa', '41', '4', '14', 'F', NULL, '3', '2014-10-29', NULL, NULL),
(97, '3150106', 'Sry Andayani Br. Sembiring', '73', '1', '10', 'F', NULL, '8', '2015-01-12', NULL, NULL),
(98, '3150210', 'Lilis Karlina', '73', '1', '10', 'F', NULL, '8', '2015-02-25', NULL, NULL),
(99, '3150901', 'Dedi Susanto', '56', '4', '4', 'M', NULL, '3', '2015-09-01', NULL, NULL),
(100, '3150908', 'Hotnita H. Sijabat', '73', '1', '10', 'F', NULL, '8', '2015-09-07', NULL, NULL),
(101, '3160103', 'Meliza', '73', '1', '10', 'F', NULL, '8', '2016-01-11', NULL, NULL),
(102, '3160115', 'Yuliana Safitri', '38', '1', '10', 'F', NULL, '3', '2016-01-11', NULL, NULL),
(103, '3160301', 'Desiani', '73', '1', '10', 'F', NULL, '8', '2016-03-07', NULL, NULL),
(104, '3160902', 'M. Ery Edy S', '56', '3', '8', 'M', NULL, '3', '2016-09-22', NULL, NULL),
(105, '3161209', 'Mia Karuniadini', '45', '1', '10', 'F', NULL, '8', '2016-12-07', NULL, NULL),
(106, '3170533', 'Imelda Purba', '87', '1', '10', 'F', NULL, '8', '2017-05-15', NULL, NULL),
(107, '3180101', 'Angelina Puspa Sari', '91', '4', '14', 'F', NULL, '3', '2018-01-22', NULL, NULL),
(108, '3190302', 'Siti Pasaribu', '45', '1', '10', 'F', NULL, '8', '2019-03-05', NULL, NULL),
(109, '3190501', 'Apriwansyah', '73', '1', '10', 'M', NULL, '8', '2019-05-23', NULL, NULL),
(110, '3190506', 'Febri Nur Fatimah', '73', '1', '10', 'F', NULL, '8', '2019-05-23', NULL, NULL),
(111, '3190602', 'Nurhayati', '73', '1', '10', 'F', NULL, '8', '2019-06-19', NULL, NULL),
(112, '3190604', 'Purnama Sari Siregar', '73', '1', '10', 'F', NULL, '8', '2019-06-19', NULL, NULL),
(113, '3190608', 'Rizki Kurniawan', '69', '1', '35', 'M', NULL, '8', '2019-06-19', NULL, NULL),
(114, '3190609', 'Robet Panjaitan', '87', '1', '10', 'M', NULL, '8', '2019-06-19', NULL, NULL),
(115, '3190701', 'Much Bayu Aji', '70', '4', '4', 'M', NULL, '3', '2019-07-04', NULL, NULL),
(116, '3220501', 'Taufik Eko Saputro', '62', '2', '1', 'M', NULL, '3', '2022-05-20', NULL, NULL),
(117, '3221004', 'Ekananda Gusra Cahya', '87', '1', '10', 'M', NULL, '8', '2022-10-18', NULL, NULL),
(118, '3221006', 'Mehwani Sri Kartika Silitonga', '69', '1', '35', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(119, '3221009', 'Angelina Riyani Agustin', '69', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(120, '3221010', 'Stevan Yoland Henry', '69', '1', '10', 'M', NULL, '8', '2022-10-18', NULL, NULL),
(121, '3221011', 'Muharni', '87', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(122, '3221021', 'Azri Amirudin', '69', '1', '35', 'M', NULL, '8', '2022-10-18', NULL, NULL),
(123, '3221022', 'Rizki Astria', '69', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(124, '3221023', 'Siska Wahyuni Putri', '69', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(125, '3221024', 'Zahda Piki Arjuka', '69', '1', '10', 'M', NULL, '8', '2022-10-18', NULL, NULL),
(126, '3221025', 'Ali Napia', '69', '1', '10', 'M', NULL, '8', '2022-10-18', NULL, NULL),
(127, '3221027', 'Aida Kurnia Putri', '56', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(128, '3221028', 'Sarina Wati', '56', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(129, '3221029', 'Fitriah', '69', '1', '10', 'F', NULL, '8', '2022-10-18', NULL, NULL),
(130, '3221101', 'Faizal Yusuf', '39', '1', '2', 'M', NULL, '4', '2022-11-01', NULL, NULL),
(131, '3230102', 'Abdul Aziz', '69', '5', '15', 'M', NULL, '8', '2023-01-16', NULL, NULL),
(132, '3230104', 'Misanta Uli Asrina Br Sembiring', '69', '1', '35', 'F', NULL, '8', '2023-01-16', NULL, NULL),
(133, '3230201', 'Siti Hartina Marbun', '73', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(134, '3230202', 'Hafriza Hasanah', '87', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(135, '3230203', 'Paridawati Gultom', '69', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(136, '3230205', 'Valentina Hutabarat', '69', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(137, '3230206', 'Tirmasari', '69', '1', '35', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(138, '3230211', 'Jagad Oktafrianto', '56', '1', '10', 'M', NULL, '8', '2023-02-23', NULL, NULL),
(139, '3230212', 'Muhammad Nur Sidiq', '56', '1', '10', 'M', NULL, '8', '2023-02-23', NULL, NULL),
(140, '3230213', 'Hotmasi Sihombing', '56', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(141, '3230214', 'Meliwati Sagala', '56', '1', '10', 'F', NULL, '8', '2023-02-23', NULL, NULL),
(142, '3230301', 'Muhamad Ferry Irawan', '69', '5', '15', 'M', NULL, '8', '2023-03-13', NULL, NULL),
(143, '3230305', 'Novia Nuzulyani', '69', '1', '10', 'F', NULL, '8', '2023-03-13', NULL, NULL),
(144, '3230309', 'Khesia Santa Maria Sianipar', '69', '1', '10', 'F', NULL, '8', '2023-03-13', NULL, NULL),
(145, '3230312', 'Yovanka Nikita', '69', '1', '10', 'F', NULL, '8', '2023-03-13', NULL, NULL),
(146, '3230313', 'Mimi Arianti', '73', '1', '10', 'F', NULL, '8', '2023-03-13', NULL, NULL),
(147, '3230316', 'Latifa Ramandes', '69', '1', '10', 'M', NULL, '8', '2023-03-13', NULL, NULL),
(148, '3230317', 'Primta Richardo', '69', '1', '10', 'M', NULL, '8', '2023-03-13', NULL, NULL),
(149, '3230402', 'Windi Saputri', '69', '1', '10', 'F', NULL, '8', '2023-04-17', NULL, NULL),
(150, '3230406', 'Trinanda Wahyudi', '69', '1', '10', 'M', NULL, '8', '2023-04-17', NULL, NULL),
(151, '3230409', 'Muhammad Rayan Andhika', '69', '1', '10', 'M', NULL, '8', '2023-04-17', NULL, NULL),
(152, '3230416', 'Rendi Yanis', '69', '1', '10', 'M', NULL, '8', '2023-04-17', NULL, NULL),
(153, '3230601', 'Yeriko Pratama Riandro Sianturi', '69', '1', '10', 'M', NULL, '8', '2023-06-02', NULL, NULL),
(154, '3230603', 'Putri Sofia Ramadani', '69', '1', '10', 'F', NULL, '8', '2023-06-02', NULL, NULL),
(155, '3230604', 'Satria Rizqi Ramadhan', '69', '1', '10', 'M', NULL, '8', '2023-06-02', NULL, NULL),
(156, '3230801', 'Salsabila Desti Darma', '70', '4', '4', 'F', NULL, '3', '2023-08-07', NULL, NULL),
(157, '3230802', 'Takako Takimoto', '93', '10', '36', 'F', NULL, '3', '2023-08-10', NULL, NULL),
(158, '2010008', 'Hisafumi Muramoto', '93', '10', '36', 'M', NULL, '3', '2015-08-01', NULL, NULL),
(159, '2019042', 'Shigeru Sonoda', '93', '10', '42', 'M', NULL, '3', '2019-04-01', NULL, NULL),
(160, '2023041', 'Kazuyasu Oki', '93', '10', '43', 'M', NULL, '3', '2023-04-01', NULL, NULL),
(161, '3231002', 'Hasti Janu Aulia', '69', '1', '10', 'F', NULL, '8', '2023-10-18', NULL, NULL),
(162, '3231003', 'Yeneti', '69', '1', '10', 'F', NULL, '8', '2023-10-18', NULL, NULL),
(163, '3231004', 'Putri Aida Lestari', '69', '1', '10', 'F', NULL, '8', '2023-10-18', NULL, NULL),
(164, '3231102', 'Boby Toba Siregar', '69', '5', '15', 'M', NULL, '8', '2023-11-15', NULL, NULL),
(165, '3240101', 'Daniel Demak Berkat Parulian Panjaitan', '69', '1', '10', 'M', NULL, '8', '2024-01-15', NULL, NULL),
(166, '3240107', 'Rindi Dwi Permata', '69', '1', '10', 'F', NULL, '8', '2024-01-19', NULL, NULL),
(167, '3240201', 'Rani Rusmawati', '73', '1', '10', 'F', NULL, '8', '2024-02-19', NULL, NULL),
(168, '3240202', 'Yasi Marwanti', '73', '1', '10', 'F', NULL, '8', '2024-02-19', NULL, NULL),
(169, '3240203', 'Gracia Romasta Sitompul', '69', '1', '10', 'F', NULL, '8', '2024-02-19', NULL, NULL),
(170, '3240204', 'Desi Muliana Br Sidabutar', '73', '1', '10', 'F', NULL, '8', '2024-02-19', NULL, NULL),
(171, '3240205', 'Desi Aryani Sihombing', '69', '1', '10', 'F', NULL, '8', '2024-02-19', NULL, NULL),
(172, '3240208', 'Kumaratih Kumaratungga Dewi', '69', '4', '4', 'F', NULL, '3', '2024-02-19', NULL, NULL),
(173, '3240501', 'Lasya Eriani', '56', '1', '10', 'F', NULL, '8', '2024-05-13', NULL, NULL),
(174, '3240502', 'Hendri Purnawidijaya', '56', '1', '10', 'M', NULL, '8', '2024-05-13', NULL, NULL),
(175, '3240508', 'Daffa Amalia Damayanti', '69', '1', '10', 'F', NULL, '8', '2024-05-13', NULL, NULL),
(176, '3240603', 'Rozalina', '69', '1', '10', 'F', NULL, '8', '2024-06-06', NULL, NULL),
(177, '3240604', 'Esti Ratnasari', '69', '1', '10', 'F', NULL, '8', '2024-06-06', NULL, NULL),
(178, '3240605', 'Enny Frisdawati', '69', '1', '10', 'F', NULL, '8', '2024-06-19', NULL, NULL),
(179, '3240606', 'Mega Oktapia', '69', '1', '10', 'F', NULL, '8', '2024-06-19', NULL, NULL),
(180, '3240701', 'Maulina Sentari', '73', '1', '10', 'F', NULL, '8', '2024-07-01', NULL, NULL),
(181, '3240702', 'Nayla Azzahra Syahputri', '73', '1', '10', 'F', NULL, '8', '2024-07-01', NULL, NULL),
(182, '3240703', 'Ismail Sembiring', '69', '1', '10', 'M', NULL, '8', '2024-07-01', NULL, NULL),
(183, '3240704', 'Dhivo Ananda', '69', '1', '10', 'M', NULL, '8', '2024-07-01', NULL, NULL),
(184, '3240705', 'Herlambang Gustian', '73', '1', '10', 'M', NULL, '8', '2024-07-01', NULL, NULL),
(185, '3240706', 'Hafuza Bazla', '69', '1', '10', 'F', NULL, '8', '2024-07-01', NULL, NULL),
(186, '3240708', 'Nia Dewi Kartika', '73', '1', '10', 'F', NULL, '8', '2024-07-01', NULL, NULL),
(187, '3240711', 'Puput Sharifatun Nisaq', '73', '1', '10', 'F', NULL, '8', '2024-07-18', NULL, NULL),
(188, '3240714', 'Enita Roza', '73', '1', '10', 'F', NULL, '8', '2024-07-18', NULL, NULL),
(189, '3240715', 'Jascia Adelia', '69', '1', '10', 'F', NULL, '8', '2024-07-18', NULL, NULL),
(190, '3240801', 'Nada Rahmi Zamra', '56', '4', '4', 'F', NULL, '3', '2024-08-05', NULL, NULL),
(191, '3240802', 'Yoky Adi Saputro', '67', '1', '10', 'M', NULL, '8', '2024-08-05', NULL, NULL),
(192, '3240902', 'Wahyudi', '71', '4', '4', 'M', NULL, '3', '2024-09-09', NULL, NULL),
(193, '3240903', 'Faris Rizki Ramadhani', '69', '4', '4', 'M', NULL, '3', '2024-09-09', NULL, NULL),
(194, '3241001', 'Zul Ibrahim', '73', '1', '10', 'M', NULL, '8', '2024-10-02', NULL, NULL),
(195, '3241002', 'Susi Rania Manurung', '69', '1', '10', 'F', NULL, '8', '2024-10-02', NULL, NULL),
(196, '3241003', 'Abdee Mekha Permana', '73', '1', '10', 'M', NULL, '8', '2024-10-02', NULL, NULL),
(197, '3241101', 'Ananda Oktavia Ramadani', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(198, '3241103', 'Anisa Nur\'aini Putri', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(199, '3241106', 'Devina Larasati', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(200, '3241107', 'Dhea Ananda Valentini', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(201, '3241108', 'E Agus Susilawati', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(202, '3241109', 'Frisilia Sipahutar', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(203, '3241110', 'Khaila Islami Putri', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(204, '3241112', 'Nanang Dandy Saputra', '69', '1', '10', 'M', NULL, '8', '2024-11-01', NULL, NULL),
(205, '3241113', 'Selly Kusniati', '69', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(206, '3241115', 'Christin Amelia Manalu', '73', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(207, '3241116', 'Della Miftahul Raudhatul Jannah', '73', '1', '10', 'F', NULL, '8', '2024-11-01', NULL, NULL),
(208, '3241117', 'Merro Daniel Lintonio', '56', '1', '10', 'M', NULL, '8', '2024-11-01', NULL, NULL),
(209, '3241118', 'Alexandro Rinaldi Muri', '69', '5', '15', 'M', NULL, '8', '2024-11-01', NULL, NULL),
(210, '3241119', 'M. Riziq Zulvan', '69', '5', '15', 'M', NULL, '8', '2024-11-01', NULL, NULL),
(211, '3241120', 'Aulia Yuniswati', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(212, '3241124', 'Joice Marshefin Mananeke', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(213, '3241126', 'Natasya Ayu Syahputri', '73', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(214, '3241128', 'Nurrahmawati', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(215, '3241130', 'Patricia Olivia', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(216, '3241131', 'Rita Putri Mawarni', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(217, '3241132', 'Riyan Sipayung', '69', '1', '10', 'M', NULL, '8', '2024-11-18', NULL, NULL),
(218, '3241135', 'Lisa Widhiyanti', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(219, '3241136', 'Desi Ratnawati', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(220, '3241137', 'Rumanti Siboro', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(221, '3241138', 'Leni', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(222, '3241139', 'Supriyadi', '69', '1', '10', 'M', NULL, '8', '2024-11-18', NULL, NULL),
(223, '3241140', 'Intan Kumala Sari', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(224, '3241141', 'Riska', '69', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(225, '3241142', 'Sumiati', '56', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(226, '3241143', 'Azura Safitri', '56', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(227, '3241144', 'Tiara Putri Kinanti', '50', '1', '10', 'F', NULL, '8', '2024-11-18', NULL, NULL),
(228, '3241202', 'Ardiansyah', '69', '5', '15', 'M', NULL, '8', '2024-12-02', NULL, NULL),
(229, '3241204', 'Muhammad Fauzan Hadi', '71', '5', '15', 'M', NULL, '8', '2024-12-02', NULL, NULL),
(230, '3241205', 'Regi Wijaya', '69', '5', '15', 'M', NULL, '8', '2024-12-17', NULL, NULL),
(231, '3241206', 'Febrian Arnandes', '69', '5', '15', 'M', NULL, '8', '2024-12-17', NULL, NULL),
(232, '3250101', 'Afriliya Fratiwi', '91', '4', '14', 'F', NULL, '3', '2025-01-02', NULL, NULL),
(233, '3250201', 'Seprika Samsi', '69', '1', '10', 'F', NULL, '8', '2025-02-07', NULL, NULL),
(234, '3250202', 'Junita Wulan Sari', '69', '1', '10', 'F', NULL, '8', '2025-02-07', NULL, NULL),
(235, '3250203', 'Octavia Rindani', '69', '1', '10', 'F', NULL, '8', '2025-02-07', NULL, NULL),
(236, '3250204', 'Qhairunnisa', '71', '4', '4', 'F', NULL, '3', '2025-02-07', NULL, NULL),
(237, '3250205', 'Ahmad Wahyu', '69', '1', '10', 'M', NULL, '8', '2025-02-19', NULL, NULL),
(238, '3250206', 'Andika Ferdiansyah', '69', '1', '10', 'M', NULL, '8', '2025-02-19', NULL, NULL),
(239, '3250207', 'Anisa Yulia Fitri', '69', '1', '10', 'F', NULL, '8', '2025-02-19', NULL, NULL),
(240, '3250210', 'Mega Safitri', '69', '1', '10', 'F', NULL, '8', '2025-02-19', NULL, NULL),
(241, '3250211', 'Mely Rizkiana Putri', '69', '1', '10', 'F', NULL, '8', '2025-02-19', NULL, NULL),
(242, '3250213', 'Qorri uyuni', '69', '1', '10', 'F', NULL, '8', '2025-02-19', NULL, NULL),
(243, '3250214', 'Ridho Ilahi Pratama', '69', '1', '10', 'M', NULL, '8', '2025-02-19', NULL, NULL),
(244, '3250217', 'Violina Rianada Mayora', '69', '1', '10', 'F', NULL, '8', '2025-02-19', NULL, NULL),
(245, '3250218', 'Yandika Binanda', '69', '1', '10', 'M', NULL, '8', '2025-02-19', NULL, NULL),
(246, '3250301', 'Hotlin Meriati Simamora', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(247, '3250302', 'Rohilawati', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(248, '3250303', 'Meriana Novita Saragih', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(249, '3250304', 'Wisma Delila', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(250, '3250305', 'Destya Purnama', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(251, '3250306', 'Melva Chaterina', '69', '1', '10', 'F', NULL, '8', '2025-03-13', NULL, NULL),
(252, '3250401', 'Fidya Kusuma Ranti', '69', '1', '10', 'F', NULL, '8', '2025-04-17', NULL, NULL),
(253, '3250405', 'Muhammad Fachrezy Geniamal', '69', '1', '10', 'M', NULL, '8', '2025-04-17', NULL, NULL),
(254, '3250407', 'Rizki Putra Ramadhan', '56', '1', '10', 'M', NULL, '8', '2025-04-17', NULL, NULL),
(255, '3250408', 'Sumari', '69', '5', '15', 'M', NULL, '8', '2025-04-21', NULL, NULL),
(256, '3250501', 'Muhammad Farel', '69', '1', '10', 'M', NULL, '8', '2025-05-05', NULL, NULL),
(257, '3250504', 'Winda Riau Wulandari', '73', '1', '10', 'F', NULL, '8', '2025-05-05', NULL, NULL),
(258, '3250506', 'Akhirrudin Ariansyah', '69', '5', '15', 'M', NULL, '8', '2025-05-05', NULL, NULL),
(259, '3250507', 'Ronal Sinulingga', '69', '5', '15', 'M', NULL, '8', '2025-05-05', NULL, NULL),
(260, '3250508', 'Reza Junius Putra', '69', '5', '15', 'M', NULL, '8', '2025-05-05', NULL, NULL),
(261, '3250509', 'Bagus Widodo', '71', '1', '35', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(262, '3250510', 'Ahmad Muhaimin', '71', '1', '35', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(263, '3250511', 'Risa Adistiana', '69', '1', '10', 'F', NULL, '8', '2025-05-19', NULL, NULL),
(264, '3250512', 'Yuni Rahayu', '69', '1', '10', 'F', NULL, '8', '2025-05-19', NULL, NULL),
(265, '3250513', 'Edo Ramadhan', '69', '1', '10', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(266, '3250515', 'Gerry Lyman Tambunan', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(267, '3250516', 'Muhammad Subakti', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(268, '3250517', 'Rivand Zumara', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(269, '3250518', 'Muhammad Al Fajri', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(270, '3250519', 'Naufal Iqbal Anwar Pulungan', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(271, '3250520', 'Roni Afriadi', '69', '5', '15', 'M', NULL, '8', '2025-05-19', NULL, NULL),
(272, '3250521', 'Fauzi Syarief', '56', '4', '4', 'M', NULL, '3', '2025-05-19', NULL, NULL),
(273, '3250602', 'Fauzia Zahro Siregar', '69', '1', '10', 'F', NULL, '8', '2025-06-11', NULL, NULL),
(274, '3250603', 'Muhammad Ibnu Syahputra', '69', '1', '10', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(275, '3250604', 'Syahrul Ramadhan', '73', '1', '10', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(276, '3250605', 'Alya Dwi Khairunnisa', '73', '1', '10', 'F', NULL, '8', '2025-06-11', NULL, NULL),
(277, '3250606', 'Fadhillah Zikry', '69', '1', '10', 'F', NULL, '8', '2025-06-11', NULL, NULL),
(278, '3250607', 'Irangga Rahmatullah', '69', '1', '10', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(279, '3250608', 'Muhammad Wahyu Hidayat', '69', '1', '10', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(280, '3250609', 'Diky Hermawan', '69', '5', '15', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(281, '3250610', 'Abdul Latif', '69', '5', '15', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(282, '3250611', 'Tegas Dihabonaran Siagian', '69', '5', '15', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(283, '3250612', 'Muhammad Hefrizaldi', '69', '5', '15', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(284, '3250613', 'Irvantoni Ilham', '69', '7', '9', 'M', NULL, '8', '2025-06-11', NULL, NULL),
(285, '3250614', 'Iqbal Wahyu Pratama', '69', '5', '15', 'M', NULL, '8', '2025-06-18', NULL, NULL),
(286, '3250615', 'Muhammad Fadhel Zaini', '69', '5', '15', 'M', NULL, '8', '2025-06-18', NULL, NULL),
(287, '3250616', 'Yusral Muhammad Ilyas', '69', '5', '15', 'M', NULL, '8', '2025-06-18', NULL, NULL),
(288, '3250617', 'Mhd Sabaruddin Nasution', '69', '5', '15', 'M', NULL, '8', '2025-06-18', NULL, NULL),
(289, '3250701', 'Muliono', '69', '1', '10', 'M', NULL, '8', '2025-07-10', NULL, NULL),
(290, '3250702', 'Maya Dwi Cahyani', '69', '1', '10', 'F', NULL, '8', '2025-07-10', NULL, NULL),
(291, '3250703', 'Mailinda Simatupang', '69', '1', '10', 'F', NULL, '8', '2025-07-10', NULL, NULL),
(292, '3250704', 'Jasni', '69', '1', '10', 'F', NULL, '8', '2025-07-10', NULL, NULL),
(293, '3250705', 'Samuel Ginting', '69', '1', '10', 'M', NULL, '8', '2025-07-10', NULL, NULL),
(294, '3250706', 'Aji Ilham', '73', '1', '10', 'M', NULL, '8', '2025-07-10', NULL, NULL),
(295, '3250801', 'Zikri', '39', '6', '16', 'M', NULL, '8', '2025-07-08', NULL, NULL),
(296, '3250802', 'Muhammad Daffa Al Fatih', '69', '1', '10', 'M', NULL, '8', '2025-08-13', NULL, NULL),
(297, '3250803', 'Shafina Charent', '69', '1', '10', 'F', NULL, '8', '2025-08-13', NULL, NULL),
(298, '3250804', 'Raja Kurniawan', '73', '1', '10', 'M', NULL, '8', '2025-08-13', NULL, NULL),
(299, '3250805', 'Muhammad Elwan Setiawan', '69', '1', '10', 'M', NULL, '8', '2025-08-13', NULL, NULL),
(300, '3250806', 'Fitri Natalia Panjaitan', '69', '1', '10', 'F', NULL, '8', '2025-08-13', NULL, NULL),
(301, '3250807', 'Josia Sembiring', '73', '1', '10', 'M', NULL, '8', '2025-08-13', NULL, NULL),
(302, '3250808', 'Muhamad Raihan', '69', '1', '10', 'M', NULL, '8', '2025-08-13', NULL, NULL),
(303, '3250809', 'Karien Prihatini', '69', '1', '10', 'F', NULL, '8', '2025-08-13', NULL, NULL),
(304, '3250810', 'Saharika Imelda', '73', '1', '10', 'F', NULL, '8', '2025-08-13', NULL, NULL),
(305, '9703002', 'Elva Susanti', '41', '3', '8', 'F', NULL, '3', '1997-03-15', NULL, NULL),
(306, '9706019', 'I Putu Pangkat', '86', '9', '8', 'M', NULL, '3', '1997-06-15', NULL, NULL),
(307, '9706034', 'Sunarmadi', '71', '2', '1', 'M', NULL, '3', '1997-06-15', NULL, NULL),
(308, '9709079', 'Kasiman', '71', '8', '6', 'M', NULL, '8', '1997-09-17', NULL, NULL),
(309, '9709083', 'Nurhayati', '73', '1', '29', 'F', NULL, '8', '1997-09-17', NULL, NULL),
(310, '9709097', 'Sochifudin', '69', '1', '29', 'M', NULL, '8', '1997-09-17', NULL, NULL),
(311, '9709107', 'I Putu Yoga Sugama', '38', '3', '5', 'M', NULL, '3', '1997-09-17', NULL, NULL),
(312, '9802136', 'James Winter P', '38', '8', '15', 'M', NULL, '8', '1998-02-10', NULL, NULL),
(313, '9802137', 'Jon Poni Irianto', '73', '8', '15', 'M', NULL, '8', '1998-02-10', NULL, NULL),
(314, '9802141', 'Mardiana', '70', '4', '4', 'F', NULL, '3', '1998-02-10', NULL, NULL),
(315, '9802171', 'Zuli Adam', '38', '8', '29', 'M', NULL, '49', '1998-02-10', NULL, NULL),
(316, '9802176', 'Sapari Sri Indarti', '54', '2', '1', 'F', NULL, '3', '1998-02-10', NULL, NULL),
(317, '9807223', 'Suparman', '71', '8', '15', 'M', NULL, '8', '1998-07-07', NULL, NULL),
(318, '9807228', 'Yusi Andri', '50', '2', '1', 'M', NULL, '3', '1998-07-07', NULL, NULL),
(319, '9812245', 'Slamet Supriyadi', '39', '1', '2', 'M', NULL, '2', '1998-12-04', NULL, NULL),
(320, '9902263', 'Norma Linda Pakpahan', '56', '1', '29', 'F', NULL, '8', '1999-02-08', NULL, NULL),
(321, '9902269', 'Mirshad', '70', '4', '4', 'M', NULL, '49', '1999-02-15', NULL, NULL),
(322, '9902285', 'M. Nurhadi', '73', '1', '10', 'M', NULL, '8', '1999-02-15', NULL, NULL),
(323, '9902302', 'Zaenal', '56', '5', '15', 'M', NULL, '3', '1999-02-15', NULL, NULL),
(324, '9903335', 'Nugrahanto Himawan', '67', '1', '10', 'M', NULL, '8', '1999-03-01', NULL, NULL),
(325, '9903373', 'Agus Suyamto', '73', '1', '6', 'M', NULL, '8', '1999-03-15', NULL, NULL),
(326, '9903386', 'Kamidi', '67', '1', '10', 'M', NULL, '3', '1999-03-15', NULL, NULL),
(327, '9903424', 'Rosmawati', '56', '5', '3', 'F', NULL, '3', '1999-03-15', NULL, NULL),
(328, '9903430', 'Samsuddin', '38', '1', '10', 'M', NULL, '8', '1999-03-15', NULL, NULL),
(329, '9904440', 'Andi Catur Prasetyo', '67', '5', '29', 'M', NULL, '3', '1999-04-05', NULL, NULL),
(330, '9904449', 'Wardi', '50', '5', '29', 'M', NULL, '3', '1999-04-05', NULL, NULL),
(331, '9904477', 'Rini Wulandari', '73', '5', '15', 'F', NULL, '3', '1999-04-05', NULL, NULL),
(332, '9905535', 'Jimper Sirait', '56', '1', '6', 'M', NULL, '8', '1999-05-03', NULL, NULL),
(333, '9905575', 'Andiski Novalyna Sinambela', '56', '1', '6', 'F', NULL, '8', '1999-05-17', NULL, NULL),
(334, '9905584', 'Duma Sari Pane', '70', '5', '15', 'F', NULL, '3', '1999-05-17', NULL, NULL),
(335, '9905595', 'Erma Yeni', '87', '1', '35', 'F', NULL, '8', '1999-05-17', NULL, NULL),
(336, '9905654', 'Tutioma Ambarita', '87', '1', '6', 'F', NULL, '8', '1999-05-17', NULL, NULL),
(337, '9907686', 'Suyanto', '39', '1', '6', 'M', NULL, '4', '1999-07-07', NULL, NULL),
(338, '9910704', 'Basuki Rachmat', '87', '1', '29', 'M', NULL, '8', '1999-10-11', NULL, NULL),
(339, '9911765', 'Sudiyanti', '87', '1', '10', 'F', NULL, '8', '1999-11-22', NULL, NULL),
(340, '2015071', 'Hiroo Kubo', 'JD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(341, '2017041', 'Kodama Hidenobu', 'JG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groupcode`
--

CREATE TABLE `groupcode` (
  `GrpCode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Group` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groupcode`
--

INSERT INTO `groupcode` (`GrpCode`, `Group`) VALUES
('2', 'group operator'),
('3', 'Normal'),
('4', 'Op GA Grp.1'),
('49', 'Production Staff'),
('5', 'Non Operator'),
('7', 'Driver GA'),
('8', 'group operator');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created_at`) VALUES
(1, 'Group A', '2025-08-19 07:17:49'),
(2, 'Group B', '2025-08-19 07:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `japanemployees`
--

CREATE TABLE `japanemployees` (
  `id` int NOT NULL,
  `EmpNo` varchar(20) NOT NULL,
  `Name` varchar(512) NOT NULL,
  `DeptCode` varchar(10) DEFAULT NULL,
  `ClasCode` varchar(10) DEFAULT NULL,
  `DestCode` varchar(10) DEFAULT NULL,
  `Sex` varchar(1) DEFAULT NULL,
  `Resigned` varchar(512) DEFAULT NULL,
  `GrpCode` varchar(10) DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `japanemployees`
--

INSERT INTO `japanemployees` (`id`, `EmpNo`, `Name`, `DeptCode`, `ClasCode`, `DestCode`, `Sex`, `Resigned`, `GrpCode`, `department_id`, `designation_id`, `created_at`) VALUES
(3, '2017041', 'Kodama Hidenobu', 'JG', NULL, '4', 'M', NULL, NULL, NULL, NULL, '2025-08-19 01:47:24'),
(4, '2023041', 'Kazuyasu Ohki', 'JP', NULL, '22', 'M', NULL, NULL, NULL, NULL, '2025-08-19 01:47:24'),
(10, '2010008', 'Hisafumi Muramoto', 'JG', NULL, '5', 'M', NULL, NULL, NULL, NULL, '2025-08-19 01:47:24'),
(11, '2015071', 'Hiroo Kubo', 'JD', NULL, '22', 'M', NULL, NULL, NULL, NULL, '2025-08-19 01:47:24'),
(13, '2019042', 'Shigeru Sonoda', 'JG', NULL, '5', 'M', NULL, NULL, NULL, NULL, '2025-08-19 01:47:24'),
(6974, '3230802', 'Takako Takimoto', '91', '010', '05', 'F', '', '003', NULL, NULL, '2025-08-19 00:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `settlements`
--

CREATE TABLE `settlements` (
  `id` int NOT NULL,
  `trip_id` int NOT NULL,
  `total_realisasi` decimal(12,2) NOT NULL DEFAULT '0.00',
  `bukti_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `variance` decimal(14,2) DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'submitted',
  `remaining_cash` decimal(14,2) DEFAULT '0.00',
  `settlement_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settlement_items`
--

CREATE TABLE `settlement_items` (
  `id` int NOT NULL,
  `settlement_id` int DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount_idr` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amount_sgd` decimal(15,2) DEFAULT NULL,
  `amount_yen` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settlement_items`
--

INSERT INTO `settlement_items` (`id`, `settlement_id`, `category`, `description`, `amount_idr`, `created_at`, `amount_sgd`, `amount_yen`) VALUES
(1, 0, 'Airplane', '', '1000000.00', '2025-08-20 01:54:33', NULL, NULL),
(2, 0, 'Ferry / Speed', '', '150000.00', '2025-08-20 01:54:33', NULL, NULL),
(3, 0, 'Taxi 1', '', '300000.00', '2025-08-20 01:54:33', NULL, NULL),
(4, 0, 'Ferry / Speed', '', '25000.00', '2025-08-20 02:19:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `employee_source` enum('local','japan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `emp_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tujuan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `period_from` date DEFAULT NULL,
  `period_to` date DEFAULT NULL,
  `biaya_estimasi` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_entered` enum('IDR','SGN','YEN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'IDR',
  `temp_payment_idr` decimal(12,2) DEFAULT '0.00',
  `temp_payment_sgn` decimal(12,2) DEFAULT '0.00',
  `temp_payment_yen` decimal(12,2) DEFAULT '0.00',
  `keperluan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `data_for_collection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_approvals`
--

CREATE TABLE `trip_approvals` (
  `id` int NOT NULL,
  `trip_id` int NOT NULL,
  `circular_id` int DEFAULT NULL,
  `approver_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_level` tinyint NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','manager','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `employee_id` int DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `employee_id`, `must_change_password`, `created_at`) VALUES
(1, '2001785', '$2y$10$Kg5YCpzQV5iDsChzA0oG3OJmFz6CnCg4hpnRWH4TeC27up/f.e/yu', 'employee', 1, 1, '2025-08-21 05:53:47'),
(2, '2001786', '$2y$10$2k2ymfj3aHZymCCMbEM5T.I7qDnJxpbQPqUOI7SU3Z25QJjsla9MS', 'employee', 2, 1, '2025-08-21 05:53:47'),
(3, '2004790', '$2y$10$9Ek89x/9lf7yO5b9HDsaqOV6j3Dag3iy.WzwqByVB9xqwmCV6nu.C', 'employee', 3, 1, '2025-08-21 05:53:47'),
(4, '2005814', '$2y$10$./ctNnxkO.d7rnSSeNKR/u/jIUXD8KqjpIdPwirnFMML4KeTWEWZm', 'employee', 4, 1, '2025-08-21 05:53:47'),
(5, '2006833', '$2y$10$3Z1OMxP8PUBBjUZxIb1QOuhDKvROWH868XWKZl9pMuOkauw7DYubW', 'employee', 5, 1, '2025-08-21 05:53:47'),
(6, '2006839', '$2y$10$uD/rOzDAcfgGomtqJN10Ge3q4bsech6r0XChB3gdhPo0uP3T0rRC6', 'employee', 6, 1, '2025-08-21 05:53:47'),
(7, '2009901', '$2y$10$jPF33mP8kZNUzDUZ53Y28ufRW1NnxoCZn/ZtVpIxIV3SkyktScniS', 'employee', 7, 1, '2025-08-21 05:53:47'),
(8, '2009923', '$2y$10$iDj4wxWx.C9uC4knhKoVB.wRoB5CrCkbEY8XXDnzroTysjcCgp/7G', 'employee', 8, 1, '2025-08-21 05:53:48'),
(9, '2010008', '$2y$10$Xr6AM1y5r8RDlP2aoCvzlePsAaIQqzFwK2pdLWIYRqb98cjc8ZB1W', 'employee', 158, 1, '2025-08-21 05:53:48'),
(10, '2010973', '$2y$10$tgFU5GcBXI1IP1liGBaMK.Oatw4RHp5baCcy6KHfnmzqspZLBhI6S', 'employee', 9, 1, '2025-08-21 05:53:48'),
(11, '2011015', '$2y$10$0rjehbBVsyK9.c5hByan6umhHfkXyqYSmxEKET4ttQ5ds50PVVof6', 'employee', 10, 1, '2025-08-21 05:53:48'),
(12, '2015071', '$2y$10$.314SQsKXpa7Oxau0u264O9WXogWpeIwBjN2nxQiUx4miy64q.nne', 'employee', 340, 1, '2025-08-21 05:53:48'),
(13, '2017041', '$2y$10$DztpHwPygqx4VQhLrdaYD.olu8WYEiiXn1jtKbmzBO9oOd182FK4G', 'employee', 341, 1, '2025-08-21 05:53:48'),
(14, '2019042', '$2y$10$MQlS3uYZir1GXNhWe2816OuIfBcyILoE6W4xIr7oFTZuzuiMwrZFy', 'employee', 159, 1, '2025-08-21 05:53:48'),
(15, '2023041', '$2y$10$buAZw4eTl/2s44pAfpOzOurISJYVe5M7emH4iwR.F7VS5gl7qkRoK', 'employee', 160, 1, '2025-08-21 05:53:48'),
(16, '2102069', '$2y$10$C1XEwiHqlUy1DeSkVurnau0xysJ44Pud4.Rzt.AsBUqu0DHx1X9QG', 'employee', 11, 1, '2025-08-21 05:53:48'),
(17, '2206106', '$2y$10$w.stxMK2SyF76oyWfMCrTOnKtZrZMHE2xGMvH.49aBr4YmOJgaPcy', 'employee', 12, 1, '2025-08-21 05:53:48'),
(18, '2206162', '$2y$10$AOlBRYNlRI.RPV1sIpsQBOhsEy6aInajMHT6znZBjtQcX2vJYQHR2', 'employee', 13, 1, '2025-08-21 05:53:48'),
(19, '2206164', '$2y$10$tHh39ugww4YNWdlOdl/lLOTYMoTkThVD9hLq6NPTTcphIJDzxZr9C', 'employee', 14, 1, '2025-08-21 05:53:48'),
(20, '2206172', '$2y$10$bzEVG/l0BjkK0zn/Gf8fPOgQ5Fg9jyxJ8lJiO8/XPk9xx7vIFIDnC', 'employee', 15, 1, '2025-08-21 05:53:48'),
(21, '2207180', '$2y$10$Pb4BCZCckFBoP6pYEKpVneSZNgEgNxCvddMcZ6nuGSvMCsypdg.Ty', 'employee', 16, 1, '2025-08-21 05:53:48'),
(22, '2207197', '$2y$10$xOWZN89Kq2w/VwRY41dbC.kSgSDRoyKFRWyJOIZKyCZE4ShyzkrGW', 'employee', 17, 1, '2025-08-21 05:53:48'),
(23, '2209216', '$2y$10$R9ZkjdfXJiHSXXM0AcxE4OaEW4FGJaHc3R0mqQOmumMOiOvfX9dBC', 'employee', 18, 1, '2025-08-21 05:53:48'),
(24, '2209244', '$2y$10$pYRC1C4Ctk/Yb8PT322OleVMIXj.WE9w.zCC9A4brjcl7BrrBV/gm', 'employee', 19, 1, '2025-08-21 05:53:48'),
(25, '2309314', '$2y$10$soAWZmfl.TUZhv8robSZ1.WCffnOgbmupHlkGcbda80OxtQ.cXQMq', 'employee', 20, 1, '2025-08-21 05:53:48'),
(26, '2404389', '$2y$10$oh5gqTTm8UsvWHVB9olGKOVN6GN71LQNekUexPF7JaLfD9gUTxjJ.', 'employee', 21, 1, '2025-08-21 05:53:48'),
(27, '2410268', '$2y$10$gl9CR4b8vSUEhoIYOhnHyO9ggxA6lxcfHxsv4mw1xnwYlECz4GVn2', 'employee', 22, 1, '2025-08-21 05:53:49'),
(28, '2502310', '$2y$10$JzN60.RzFXTYp3XEDAA7r.IS798qVnn/FlMpy6AQd80uT7VcBcRoq', 'employee', 23, 1, '2025-08-21 05:53:49'),
(29, '2502314', '$2y$10$PNcfcdfoh61RSIoYLwszCeLYJgmCituUM7w0ZQx8K9byXp1nGJh0u', 'employee', 24, 1, '2025-08-21 05:53:49'),
(30, '2603382', '$2y$10$pgQ0.sGYvlltYibSCsZiMuDkodrihvDro3LRiAv6.6Er1s.jaBITG', 'employee', 25, 1, '2025-08-21 05:53:49'),
(31, '2609462', '$2y$10$KXFvI3wR///8nzAgVJ/JxugbsdRn/l3gA7bXioYE/X8yVmIRxTElW', 'employee', 26, 1, '2025-08-21 05:53:49'),
(32, '2809772', '$2y$10$5BBQo5nZAWCmBhpvZUz8uePE0tAHjx8RYOAVg/yGiO/2/gAxAHIXS', 'employee', 27, 1, '2025-08-21 05:53:49'),
(33, '2809786', '$2y$10$cHS1K1yKbK/yr9bu1POiDuqP9dgwFLgRDR5CbAy9uvZoohFE/kMva', 'employee', 28, 1, '2025-08-21 05:53:49'),
(34, '3100101', '$2y$10$/evR/jJllQbJt2bN5KBggONDJ5.ZB1pRzvv0EUBfH/XCJ.cNOKcVm', 'employee', 29, 1, '2025-08-21 05:53:49'),
(35, '3100562', '$2y$10$CZc2P8FI78.a0hETp0H01.k2BRsWQnKrL8t1pup7.IXFLRsLCgbS2', 'employee', 30, 1, '2025-08-21 05:53:49'),
(36, '3110517', '$2y$10$0eb2lbw3wBDtroFqjo6NYuL2rAOJPnlsqFzB.LfpQYbJaz2L1IvGe', 'employee', 31, 1, '2025-08-21 05:53:49'),
(37, '31107140', '$2y$10$arsLbeo9ixhJv1..aI5Iou5dRWlAAizo7lUO4bJq/ShkNKTbOClIe', 'employee', 32, 1, '2025-08-21 05:53:49'),
(38, '31107143', '$2y$10$dHM15JZOV7AlN4w7UBbo2eGU7kVtH/U.oAtDdWJnYvrxj96hEbOGy', 'employee', 33, 1, '2025-08-21 05:53:49'),
(39, '3110852', '$2y$10$5AjMw50oa2L4wfafPdShUez9BNzWLgWTbX0ws2VoRCp3/iP3seMT2', 'employee', 34, 1, '2025-08-21 05:53:49'),
(40, '3110903', '$2y$10$Mf9mZLxkbdFVeWF5DbNCau5yhM3lU4f3rJlq7wQ76RzROHGrDlgD2', 'employee', 35, 1, '2025-08-21 05:53:49'),
(41, '3111033', '$2y$10$rIgK3vxHkVZlQ4ovdINceeo.YJzE1z7igQaebkDr36YLaTWRAAdi.', 'employee', 36, 1, '2025-08-21 05:53:49'),
(42, '3111053', '$2y$10$M/gYX3UdIVkYsZx9TbNK7.JYi1YgGfJM.FY70ZE9z8ux//E8.UqIG', 'employee', 37, 1, '2025-08-21 05:53:49'),
(43, '3111138', '$2y$10$vr754HOk7RH31n3oBIylf.uBBgw63h.z5kWV6LP7lPzJprgSPAdpW', 'employee', 38, 1, '2025-08-21 05:53:49'),
(44, '3120525', '$2y$10$h5eOwAtyVVBgVterh/DfG..TwgZeSVGRNSSmQc/3dIxLuysB5noja', 'employee', 39, 1, '2025-08-21 05:53:49'),
(45, '3120534', '$2y$10$vQKSIHu9darOa7XrxBFkvOiRKid0MdpoYUDq4rDWwnX9oI9bUbjVy', 'employee', 40, 1, '2025-08-21 05:53:49'),
(46, '3120705', '$2y$10$QTe2L64ii84D7/ZpkhmWfuyHLR8PLNg7vgDHx8jPXV0Z4mlx4ooj2', 'employee', 41, 1, '2025-08-21 05:53:49'),
(47, '3120806', '$2y$10$SGpyABwQUNE.ainBJGsYKu9hsp5KiZR0DtRCsBURHiFdHaPk.rLB2', 'employee', 42, 1, '2025-08-21 05:53:50'),
(48, '3120905', '$2y$10$F0oAgdjsmXNr5wKt3xLnGu5u.ij0akSUfTB29DEbKdmWxTDjEJal6', 'employee', 43, 1, '2025-08-21 05:53:50'),
(49, '3121042', '$2y$10$w95yTjqIM6irTWtLeSEm0.qGoC83Cg1NLWautjJDdTufh18vEwDNO', 'employee', 44, 1, '2025-08-21 05:53:50'),
(50, '3121201', '$2y$10$DnLO86XlahfTYNVvOKGGn.A6UJICLVA4QV9MJ/.oB1VeQ8V0tNk.C', 'employee', 45, 1, '2025-08-21 05:53:50'),
(51, '3121207', '$2y$10$jWP0ataUcNaKf8E0a7ynDeQASZ6nuQF6JzJih1Gl8BuT.t2sfraG.', 'employee', 46, 1, '2025-08-21 05:53:50'),
(52, '3121215', '$2y$10$Iw15aR7FdOY1APbMUG86Qut2rtwMlUfSaKqae6U9bgPck172BgFHa', 'employee', 47, 1, '2025-08-21 05:53:50'),
(53, '3130101', '$2y$10$5OX4AyiCa7Hb8qZ3i3qpoeu40nU1rIA2//iJytVUbJV4CEPXro/.m', 'employee', 48, 1, '2025-08-21 05:53:50'),
(54, '3130104', '$2y$10$5c17l.R6CjNPzWV9Pe9eqOYxBEksgGHSXEwztpQ/EKb.RsYpussCK', 'employee', 49, 1, '2025-08-21 05:53:50'),
(55, '3130115', '$2y$10$L9sjvhL2Rq9TBG96ww6UhuX8N8c6m.EmNz9C10Jad9XWS2gyVKAD.', 'employee', 50, 1, '2025-08-21 05:53:50'),
(56, '3130125', '$2y$10$VbkDLPtzLrP09O8S9ENNF.CLmUG/J52j1GbKPu3OjxUcACFrew6Ba', 'employee', 51, 1, '2025-08-21 05:53:50'),
(57, '3130140', '$2y$10$kGc.Y6TTH1tbWAeIHgeunufeVlUbLJgUEKVF2rvPd9kcGc3I0dw1G', 'employee', 52, 1, '2025-08-21 05:53:50'),
(58, '3130149', '$2y$10$tpRXijFM7PME4YOUPPY.1OOsoY.9q8nsYWtJSNBTDPIHOyV5IIQZW', 'employee', 53, 1, '2025-08-21 05:53:50'),
(59, '3130201', '$2y$10$vtbFw0uQfUiRo5hfalrXIOOE9J34F5.oimjU.6aHe5CGDCsAbzjUG', 'employee', 54, 1, '2025-08-21 05:53:50'),
(60, '3130309', '$2y$10$euwt9Vaf2T5yzdN24nFgZOMVsBRDdAu0HPGlTWVKtPzL7tESMcCPi', 'employee', 55, 1, '2025-08-21 05:53:50'),
(61, '3130410', '$2y$10$.E9oMjN0XikqhxIOU6mjHe/8FXN9txKKgVoxtey2j/4lv0DaAYnOi', 'employee', 56, 1, '2025-08-21 05:53:50'),
(62, '3130505', '$2y$10$FmZSAf.se5Jq4vFVbn8kLONRIdSCL1FvP/ZB17lpzFJakC8EnUj16', 'employee', 57, 1, '2025-08-21 05:53:50'),
(63, '3130515', '$2y$10$DBkAy/OKvwJi0e6R/x/5CezwCW9dHzYehCwgjICL5pCxSgIAQck8O', 'employee', 58, 1, '2025-08-21 05:53:50'),
(64, '3130523', '$2y$10$7jPM5gfvChCUtt0vSMccPeBnIpgAVKTAR8VI88Ms77lvlUjKjKfSS', 'employee', 59, 1, '2025-08-21 05:53:50'),
(65, '3130705', '$2y$10$3OEj03CNiSjZqB9T34VB9u20Bpd7S0Gl5lF6I4iubEusw.8/TLuIm', 'employee', 60, 1, '2025-08-21 05:53:50'),
(66, '3130708', '$2y$10$VDlFg.kZ/T5YK1AQqkiyxuIvBCo34Y/fzj.B9i0G..8XbchfLEfO2', 'employee', 61, 1, '2025-08-21 05:53:51'),
(67, '3130718', '$2y$10$l0BbNyoog0TFYYxH/1nFl.sxiFQK4/KPJc8MTt7BljLRKs1FvhlYC', 'employee', 62, 1, '2025-08-21 05:53:51'),
(68, '3130819', '$2y$10$OA1.8kLZhlBp2g1HaoOaZOoQ2Lc9cxPiR7Elu.sbGsjwUT4bWmTCK', 'employee', 63, 1, '2025-08-21 05:53:51'),
(69, '3130829', '$2y$10$QxEeZuw3B4yzzxWMkzlxE.pebnxexDH5bMaBEnzunYpb70I50i2cy', 'employee', 64, 1, '2025-08-21 05:53:51'),
(70, '3130848', '$2y$10$.HoWMxA8nyGvKplORzKn8OA75nP/xm23X3cem1GOS2iRKebcGYunK', 'employee', 65, 1, '2025-08-21 05:53:51'),
(71, '3130871', '$2y$10$We03LnvSb5/qNx8ZD18wfOhjQ5vtDBTdLfj.lOK5a74xV4WOMc3l6', 'employee', 66, 1, '2025-08-21 05:53:51'),
(72, '3130906', '$2y$10$vwyM7K.jCKIhAUvxVvF90.xQ8xK3Mj2qqupVf0SO/8kWenQvLvvo6', 'employee', 67, 1, '2025-08-21 05:53:51'),
(73, '3130916', '$2y$10$DEK.60L8RoSsLqkiQn8useA5tcuA0lO8891gMY8HR65zus0xwkMsO', 'employee', 68, 1, '2025-08-21 05:53:51'),
(74, '3130927', '$2y$10$9Ayf5w1GcitNK6x0vYJ3NuymGiZQR5pZ9lCpBaBtaW/MgRYI9wuN.', 'employee', 69, 1, '2025-08-21 05:53:51'),
(75, '3130945', '$2y$10$WvnKC86tqKmkJPb1SLK9POLS15l0Kmb451UdLIRxmxGqe2dQFoZQC', 'employee', 70, 1, '2025-08-21 05:53:51'),
(76, '3130987', '$2y$10$HDA91KR3nGDcvBpUXupUwO4QZLLSp/AbcKF1iRU7.36cUdadtSqt6', 'employee', 71, 1, '2025-08-21 05:53:51'),
(77, '3131006', '$2y$10$OsRZRHJSOR.gzfrsQ/5.2efF.qtmRfEhLuSTKFCHyJlFHwxOvcix2', 'employee', 72, 1, '2025-08-21 05:53:51'),
(78, '3131025', '$2y$10$uhnD.BgRs7Olq8/NLxpwxOeCvocknvzVN5cQNvWZfUuMchovZHJ/6', 'employee', 73, 1, '2025-08-21 05:53:51'),
(79, '3131036', '$2y$10$2nSGAA5uNEp47WNrkn1neejcaEvcYyvfwXC40KkIUwnqquMAI.CDK', 'employee', 74, 1, '2025-08-21 05:53:51'),
(80, '3131042', '$2y$10$JlfywmGrHX9XqsX9xixaC.mj9ue7qjIuW8bpaczGw4XbiTmwo.EHS', 'employee', 75, 1, '2025-08-21 05:53:51'),
(81, '3131045', '$2y$10$IQqsMcIkaLnf/Hm4dQ7gNe.kaBqpV8SvsuRNy56LWXwmXszyaHsme', 'employee', 76, 1, '2025-08-21 05:53:51'),
(82, '3131051', '$2y$10$xupYEW9JKp4/dwrbZkGIhOg80Ul.Q2zL8VNQ0LG7WIy4Jji0dF.eq', 'employee', 77, 1, '2025-08-21 05:53:51'),
(83, '3131101', '$2y$10$awl60Qkjy7hRjt8W6mTNbOKIMhLFpS.LB8Uq/an3f1OUKOtyU21sS', 'employee', 78, 1, '2025-08-21 05:53:51'),
(84, '3131135', '$2y$10$mVmdyuTJU.7kEgG3R20tde.VMg1.YWoCzwEO7xy8LvlZdNSzh8Uc.', 'employee', 79, 1, '2025-08-21 05:53:51'),
(85, '3131218', '$2y$10$EK22VJ3oor8F5cintgUiPunw8y1keR.H1e5tJFGgVxw2VfB.R2ln2', 'employee', 80, 1, '2025-08-21 05:53:51'),
(86, '3131222', '$2y$10$sCIb1HS/lkYtdDaNIVc9XOH4c6SzGaKPD/4mF9wgfwwW5843pjVf6', 'employee', 81, 1, '2025-08-21 05:53:52'),
(87, '3131225', '$2y$10$7EinynJGMKBINofbQoboneW/1AgQydMYiYYa9Sv/GoOyCk986pXN.', 'employee', 82, 1, '2025-08-21 05:53:52'),
(88, '3131238', '$2y$10$Xkno8leupdxITNGzS8oiW.xltWODTSbnpJ97zWCMkfWaKEXjlU9Ay', 'employee', 83, 1, '2025-08-21 05:53:52'),
(89, '3140104', '$2y$10$pmZeFx6oAPH4HU2hS2fCxukMV/.YzNJ3yd4ZzkeNwJ5a2cLSZhy8C', 'employee', 84, 1, '2025-08-21 05:53:52'),
(90, '3140108', '$2y$10$8bDQfo1EtZ9PKgmvRd/QZ.6d2.iCalgyT0Nrs7xv.hSmTPP8k5bUi', 'employee', 85, 1, '2025-08-21 05:53:52'),
(91, '3140113', '$2y$10$k0GNMjcJTyaqevV/2N7XPOrKIonM8S7z0WhPZMjNyS9TY1gSalT1u', 'employee', 86, 1, '2025-08-21 05:53:52'),
(92, '3140127', '$2y$10$BDVal9whX9cfWmfvXv3yt.tjNaBuI83k0X7TS8AGGxiE6/JzjzB9q', 'employee', 87, 1, '2025-08-21 05:53:52'),
(93, '3140202', '$2y$10$kZdoxSPNFYT49IKkix7QVukh.OsnhUJwE5tHbxSmraAI42a0tMzBu', 'employee', 88, 1, '2025-08-21 05:53:52'),
(94, '3140212', '$2y$10$OtSU6P0EVRStAomkFkalCelT0ZXjeUI8ttnjM5z3Kq74qdR2ngRve', 'employee', 89, 1, '2025-08-21 05:53:52'),
(95, '3140215', '$2y$10$x8edlAH.PZLThQeHwVvICe8p7.X8uo3FruKSPZg0fFs2j1XP1iRoO', 'employee', 90, 1, '2025-08-21 05:53:52'),
(96, '3140304', '$2y$10$9vqMpNcKDhTsQUnRaydhne6TQTM/JxP1JDuIjCrErl3fpSRO0iLeO', 'employee', 91, 1, '2025-08-21 05:53:52'),
(97, '3140508', '$2y$10$0dmjx6OrYzU66BBnOoWZ9uiKlfDTmPRyqGFo1fncOvHcrTaHqamle', 'employee', 92, 1, '2025-08-21 05:53:52'),
(98, '3140526', '$2y$10$BZMbDievdvLjt8RRB7Bkj.doDMRUCoMopIG0yI2dHWI7VToQF04Lq', 'employee', 93, 1, '2025-08-21 05:53:52'),
(99, '3141004', '$2y$10$Ni9qnCYcHl2mUgznlp/isuUQYDSpJfTIKlH1kNJZ.mGNnRz/3Od1q', 'employee', 94, 1, '2025-08-21 05:53:52'),
(100, '3141010', '$2y$10$XatAGhrABteC99w1KckbKeBooqB7Bwrf.2kW5e3Ye5/8wBjFqn1j.', 'employee', 95, 1, '2025-08-21 05:53:52'),
(101, '3141012', '$2y$10$pomtuPd26s7y9Zc4oz0vge0pB4TKPxHE59pOX5hAAueyhDSPvknP2', 'employee', 96, 1, '2025-08-21 05:53:52'),
(102, '3150106', '$2y$10$ny3h6M5vlxPBDDdWQN.GfuBlIwY9uTCq4tEB8DZ13GURsg8aOGEUW', 'employee', 97, 1, '2025-08-21 05:53:52'),
(103, '3150210', '$2y$10$z/K4WK7nMkic3DF5F3htgOg9N6l34cfsYTIes5FmTVxYMLzQGObXu', 'employee', 98, 1, '2025-08-21 05:53:52'),
(104, '3150901', '$2y$10$Z4NSt6J.8lthyYwMzB2DTeTTVR5y2McktwLs5e5IiM.RTY1tKxFGm', 'employee', 99, 1, '2025-08-21 05:53:52'),
(105, '3150908', '$2y$10$k3DtfYD/ZGszTdlgOetKCuhNYrMxhNDpcOf0H/Nr4yPj4jX9r1fki', 'employee', 100, 1, '2025-08-21 05:53:53'),
(106, '3160103', '$2y$10$EwwvUfTNlqnyWRI39AFbkOZww8dZ5unpo8FNekvwmVrEMEjzhzU2q', 'employee', 101, 1, '2025-08-21 05:53:53'),
(107, '3160115', '$2y$10$FzZ/0Xvf7SCX2YvjXoSp3uQG6SLnkWMil1rHHP3jtUTAP5Tucjaxy', 'employee', 102, 1, '2025-08-21 05:53:53'),
(108, '3160301', '$2y$10$ujEXAvgKXYjg7vpMv45Jt.aGeP1rPFIKjmYHYAj5O5kHuowevvVn6', 'employee', 103, 1, '2025-08-21 05:53:53'),
(109, '3160902', '$2y$10$.0iSeYqt2tUYe6rRQ2wdWucU1tKF1YPNnDFnce7tCJACi/FZwcHNG', 'employee', 104, 1, '2025-08-21 05:53:53'),
(110, '3161209', '$2y$10$Kru5GlshRAwbSSrIWs5tm.4jxW1Nic3FQ0EkMqwRql/srMEXceGOC', 'employee', 105, 1, '2025-08-21 05:53:53'),
(111, '3170533', '$2y$10$9ZGKmFddRNCV4brUT/fFKOe0vDtS0SOd79tvODVcTTbVWm84dYrYe', 'employee', 106, 1, '2025-08-21 05:53:53'),
(112, '3180101', '$2y$10$tPhQDeulQRmhiDS/0ZfVV.6/2MUK1KoKiNMcQyoxaZSS0GLvJgAzi', 'employee', 107, 1, '2025-08-21 05:53:53'),
(113, '3190302', '$2y$10$tDgUQICsjR6/lGr/L.DADeo/U4hb3nD6dF4OVo.r7GQLw1/vetgAq', 'employee', 108, 1, '2025-08-21 05:53:53'),
(114, '3190501', '$2y$10$.Myz1FW9TxhOndGRllZN1O5k1ApZipXAKoxBGs5cpDT4NZkQmQeey', 'employee', 109, 1, '2025-08-21 05:53:53'),
(115, '3190506', '$2y$10$jgIEPfXGhQL0RySLqQJPD.b1joW.XBR1u3t/sTdPwlXZv4cB/R4Hu', 'employee', 110, 1, '2025-08-21 05:53:53'),
(116, '3190602', '$2y$10$dCaT/sh2jVtLItq6VtneYeFq9GK52KQ7BUPeAcOBp.ggLBxExwXC.', 'employee', 111, 1, '2025-08-21 05:53:53'),
(117, '3190604', '$2y$10$oMI7g6CfGOCgQgSj.zqCO.lbtsZZk9Eya.5bXCaALtkF5Vxt2U4xG', 'employee', 112, 1, '2025-08-21 05:53:53'),
(118, '3190608', '$2y$10$mOS1T7jzNvc0S/iB34EpHOVT5/RKJVef2YbZSe4d.AF39PGfs.cLW', 'employee', 113, 1, '2025-08-21 05:53:53'),
(119, '3190609', '$2y$10$zqZmk5UCEBe0JQNiabqyP.i9u1fPweUh4S0ubomo6Lt7g9Yx5iPdG', 'employee', 114, 1, '2025-08-21 05:53:53'),
(120, '3190701', '$2y$10$vL9EmrJEVPZBRR8nr4Rnae4xI3iHS5v/dJSsqlqqkENkZ.la7lOp6', 'employee', 115, 1, '2025-08-21 05:53:53'),
(121, '3220501', '$2y$10$q7fN7a5Sux0m4uA6GlZFG.HmH/MwPawMOTtp9Me3TZAz5PV.eaoji', 'employee', 116, 1, '2025-08-21 05:53:53'),
(122, '3221004', '$2y$10$HfadIDcc.DlNiFSiqkKUe.V7eO6MkiTaUprU8imlbL57eZ.LGf4A6', 'employee', 117, 1, '2025-08-21 05:53:53'),
(123, '3221006', '$2y$10$piE8suC1vpnnCriV6u6H0uRtRl8kPhCEbYJC/EK9T3qPFNnGbrSiS', 'employee', 118, 1, '2025-08-21 05:53:53'),
(124, '3221009', '$2y$10$/LN71DbgODPxiPcKNOtDDedTe2XwyQJbeoP1lsu.wD0ROkAOGRXc6', 'employee', 119, 1, '2025-08-21 05:53:53'),
(125, '3221010', '$2y$10$P7dOETkLhUY.0BUozzeLEeazRID.OU12oWe0PypE/9QBfYsQyuHa.', 'employee', 120, 1, '2025-08-21 05:53:54'),
(126, '3221011', '$2y$10$SjVFAGEoC3aH2Oj4LgodpewWIDk../Ka9Fteldo./4hXCbbdW5BZW', 'employee', 121, 1, '2025-08-21 05:53:54'),
(127, '3221021', '$2y$10$rWu8MIp5lI0/.4JeKlrTVeqm12zRrIaafbIVA2M0lXssdCZrziOw2', 'employee', 122, 1, '2025-08-21 05:53:54'),
(128, '3221022', '$2y$10$3E4InSyOfmGz0KzfR2BnR.tzB3.yDTxySoos8iromLdqS7/NLTqkK', 'employee', 123, 1, '2025-08-21 05:53:54'),
(129, '3221023', '$2y$10$AlMkkHj2YQT1HQyaCbYD1.BKqo5GkG/bwa8Kzjg5W25E4CcChaIVW', 'employee', 124, 1, '2025-08-21 05:53:54'),
(130, '3221024', '$2y$10$4jSaKjLHk2xR9pxOwPRIn.aVGWX/Y869UXsZVQT52DR/rN3Hss1zm', 'employee', 125, 1, '2025-08-21 05:53:54'),
(131, '3221025', '$2y$10$szcb34ACWrMXgrtWN55VLOQABiET1qodDkH99mtvXY7u81LuF9GjC', 'employee', 126, 1, '2025-08-21 05:53:54'),
(132, '3221027', '$2y$10$4DHPanQPtCOzEkxmY.zGXuHzPaEwUyJPqJ7ASv2w9/vEt1uUMdeqa', 'employee', 127, 1, '2025-08-21 05:53:54'),
(133, '3221028', '$2y$10$5CEfbmWkPxYMDxlzjLp3PeCPonzWpP6JsTg8.YBLH50lkGnNJ4/1S', 'employee', 128, 1, '2025-08-21 05:53:54'),
(134, '3221029', '$2y$10$vrw.RZyCDdwxG0iK17iWCeuE6mVUgDCZCbbgxHZUoPQC7ZQGx54Qe', 'employee', 129, 1, '2025-08-21 05:53:54'),
(135, '3221101', '$2y$10$LQNw8i5S/vOu28ZHqrFQ.O.IqkYdolEhKaNEUlJEoEoCKoBuKlYu6', 'employee', 130, 1, '2025-08-21 05:53:54'),
(136, '3230102', '$2y$10$FGqxJAglZN3NkWp3wfXEBegyhVeVc5FYAgawPAhaidArjBrScgeI6', 'employee', 131, 1, '2025-08-21 05:53:54'),
(137, '3230104', '$2y$10$2O454l0CzOdb7VfdERikuOB9.n0Z3jQSw5Jr3xFZYjn3lTwUMw34.', 'employee', 132, 1, '2025-08-21 05:53:54'),
(138, '3230201', '$2y$10$62KShXOcmT0mO9T./eq/0eM2lAeddN9uBzmejiGIULVCKbsjd9ygK', 'employee', 133, 1, '2025-08-21 05:53:54'),
(139, '3230202', '$2y$10$2qJ0TXNmOhftb/ldaPS7FuMmLAyV52HK.zNDyaAwpJv4xnTXNkE4y', 'employee', 134, 1, '2025-08-21 05:53:54'),
(140, '3230203', '$2y$10$Wz8NZpxCfRdTbXykyknuQeTfZqNEk76vRWG/uCRpP3o1gXlU0Vlmi', 'employee', 135, 1, '2025-08-21 05:53:54'),
(141, '3230205', '$2y$10$Dw1leS1rlX8PYE.J8oyenunXn5a/Cj59bAHMDcs89Ui52SAp578du', 'employee', 136, 1, '2025-08-21 05:53:54'),
(142, '3230206', '$2y$10$ptKJIQGdFVodI5RE80yjOO2JoM56m.CtmBcFmt59ymtKxC6aIacnS', 'employee', 137, 1, '2025-08-21 05:53:54'),
(143, '3230211', '$2y$10$zJy.A7gYV9poJWs2a/seEOws6CLBGLAWFSp.FkMTTv89uzHKGFRxy', 'employee', 138, 1, '2025-08-21 05:53:55'),
(144, '3230212', '$2y$10$34MlVE.RFZwrKkkXcg3lJuO.8AVAZloxlImT7uCnwIBflStWooWMu', 'employee', 139, 1, '2025-08-21 05:53:55'),
(145, '3230213', '$2y$10$2F2qsaBriGbxWVdB1MXjTua4mLUewK4/nsTsxfpPsKJqHSxvphF26', 'employee', 140, 1, '2025-08-21 05:53:55'),
(146, '3230214', '$2y$10$bcaegwm.KPHngPwLLFVNH.64lxxVgHDzXnYvLS6QfMgyGpe5Bo1/S', 'employee', 141, 1, '2025-08-21 05:53:55'),
(147, '3230301', '$2y$10$6QD3v/y6CZXtKEq7IiShXeByJHBN0wGk9ch.percRgOEI70.suoWO', 'employee', 142, 1, '2025-08-21 05:53:55'),
(148, '3230305', '$2y$10$aVGgDTeZBcQJz67IjtQ42uK/3cmGGjiYQzQip7SVieYmVPF4Hv8Im', 'employee', 143, 1, '2025-08-21 05:53:55'),
(149, '3230309', '$2y$10$pQwtWqx6/1Sl54RsCwu0RO.xyEqchIc9B2hr8xtDpnDePSDBz.WMe', 'employee', 144, 1, '2025-08-21 05:53:55'),
(150, '3230312', '$2y$10$mwK9l2nY7SRDh9T/na84XeD5W0GVZcRkUzos8pTxjirF..ZBTuVwq', 'employee', 145, 1, '2025-08-21 05:53:55'),
(151, '3230313', '$2y$10$565ub/Sg8obUm16JUfgfEe.4ZCUI/F2qPPLZTkIPdaoCeRYD.QkXK', 'employee', 146, 1, '2025-08-21 05:53:55'),
(152, '3230316', '$2y$10$rhVQ0dS4vp2E5l45enCWX.i3TVLmljmSxxs.pJb4TzAxK/RugBN7C', 'employee', 147, 1, '2025-08-21 05:53:55'),
(153, '3230317', '$2y$10$B3zfJCWTi9J419I6hoa0xO5Yy7LdRmodOOjojkDBFJfYIKp/96sgG', 'employee', 148, 1, '2025-08-21 05:53:55'),
(154, '3230402', '$2y$10$Bj3fMNIJQFCifv1ghIg1WOK3n8DW6CVp7KeW1I/ZlpX7h9AP1I0Bq', 'employee', 149, 1, '2025-08-21 05:53:55'),
(155, '3230406', '$2y$10$lQDH0MPhfXMkn5XavCMGdubWXhy80pIUxegUPOmpxIOJAyaJr.tNy', 'employee', 150, 1, '2025-08-21 05:53:55'),
(156, '3230409', '$2y$10$XO3E0A0YXO0mDqM/Y4ZHiOHCZbulzps1mkWsbc2YdGHVyLkncCcSS', 'employee', 151, 1, '2025-08-21 05:53:55'),
(157, '3230416', '$2y$10$XZymgi8zZww.Z1P2sWSnmuqA8bwyVUvw59CyjHCTL5oHRoB/4.oa6', 'employee', 152, 1, '2025-08-21 05:53:55'),
(158, '3230601', '$2y$10$XjcAIIqjWkc39Bc50Xa6tevw2XBmrrSsX2vF/ZqkOKC.bJ/QoK6aK', 'employee', 153, 1, '2025-08-21 05:53:55'),
(159, '3230603', '$2y$10$YostpjD9bx5AmLzYNhbgJegqxVpCNSD4ju6ri.OuFxizj6e9jv886', 'employee', 154, 1, '2025-08-21 05:53:55'),
(160, '3230604', '$2y$10$KIiy9atqGHCduNJDsqwu4OSdX/1/g2n79jMvjjltxE8tC.JoOFR22', 'employee', 155, 1, '2025-08-21 05:53:55'),
(161, '3230801', '$2y$10$EHzuw2tcWaSoyEG6VC5oeunZzdhI/FO3/FL4duqdGEV8Zk5aG3hGO', 'employee', 156, 1, '2025-08-21 05:53:55'),
(162, '3230802', '$2y$10$KF9wXvmWiCiexupYJLRPxenRjZrMBYvoslqNRYmXDmS.H5p7fgQ0S', 'employee', 157, 1, '2025-08-21 05:53:56'),
(163, '3231002', '$2y$10$7C/7hO8QjdIE73Zqc8nhNOBKjLZ84KXB/Us8B8MuzrY/siQZIUQUG', 'employee', 161, 1, '2025-08-21 05:53:56'),
(164, '3231003', '$2y$10$3s9Nc8B.pI/p39kn75IVbebk4rSs4LmAFTa2apIseGhKXIouRmUpm', 'employee', 162, 1, '2025-08-21 05:53:56'),
(165, '3231004', '$2y$10$jrjBochVlaNdZiy3vrRsBeTwEBbGB9bSc6bvbH21ZdrdXLAyONjri', 'employee', 163, 1, '2025-08-21 05:53:56'),
(166, '3231102', '$2y$10$RvmaWLL.ZJpSZ3BvFFonKOqdOnPNJBVZaReyyqKiPWFSfT4kwIgM2', 'employee', 164, 1, '2025-08-21 05:53:56'),
(167, '3240101', '$2y$10$u4iXukgr5PDRa9b.5kOhL.TS.a3ZNp1HvCAyr19oeABFRo/A63iAG', 'employee', 165, 1, '2025-08-21 05:53:56'),
(168, '3240107', '$2y$10$oiSogtIluXb5LGL/Y/qLZOPqMYvZ2e6f/LCOkT0OnzDVxwzsaJPjm', 'employee', 166, 1, '2025-08-21 05:53:56'),
(169, '3240201', '$2y$10$XQ7aQe6YxlwiocNykhwYieGlDxqukM0Hnt7.pAQ1AH.LtQwM.ICAa', 'employee', 167, 1, '2025-08-21 05:53:56'),
(170, '3240202', '$2y$10$gNAs0GPGlGzwgK.dTPy8d.eX7M2fK2pGSPaLjCaRloPUutNnu04AW', 'employee', 168, 1, '2025-08-21 05:53:56'),
(171, '3240203', '$2y$10$sQCBVKTyaKmPpREnVMuKv.O0dWpZDcqEEMU.RyvU5hQOCUvsX.7Sy', 'employee', 169, 1, '2025-08-21 05:53:56'),
(172, '3240204', '$2y$10$W6KZlTzSNxUED/7vuFi8WOg7yqx1OsCt4HvTaoTTNYycpuILYx.7O', 'employee', 170, 1, '2025-08-21 05:53:56'),
(173, '3240205', '$2y$10$ckoY34lsVUqSWc34.pIF6OIqXSktIrwwvpabpIwA6wvT6YcYQX.iq', 'employee', 171, 1, '2025-08-21 05:53:56'),
(174, '3240208', '$2y$10$wzJpYwHqIqwy5Xr4lKkUU.anttwN7ZEx.UVlU2FJpOygNAGvE6cZ2', 'employee', 172, 1, '2025-08-21 05:53:56'),
(175, '3240501', '$2y$10$2eoHABVrp9KObDPvYpXUvesHVdM45M.cEe5WC75mgMTllhroA06Mu', 'employee', 173, 1, '2025-08-21 05:53:56'),
(176, '3240502', '$2y$10$a38TAqi94uhhqh2pdyhgI.vbZtYCqzLNw.LvtVjIFa8.HpoAl6B/a', 'employee', 174, 1, '2025-08-21 05:53:56'),
(177, '3240508', '$2y$10$qwZ4/W1N7FkWn4iI.GYcduZzsIznRHAq2tO6miGRNzsRkfsTSKRAe', 'employee', 175, 1, '2025-08-21 05:53:56'),
(178, '3240603', '$2y$10$Jh6DS0g4DxuaZLyqpMWsuueqgZNTdksfCJ0tbmUgH2LxSZK0jaKVy', 'employee', 176, 1, '2025-08-21 05:53:56'),
(179, '3240604', '$2y$10$NtjvnCxlannJQ7n8Y7bzWuqmNumkFNW64i1BB/oA.PswA2.EqsfgO', 'employee', 177, 1, '2025-08-21 05:53:56'),
(180, '3240605', '$2y$10$SlyYS0qbaA9hhBhy0GK6Meuwk3xYzpXtplLor1xKh2IFygYogqYBu', 'employee', 178, 1, '2025-08-21 05:53:56'),
(181, '3240606', '$2y$10$LrUil0l9CAPqcmvtmV8ZY.AI3BKSJIFm/BetnIJDJQjrWEYGUMgn.', 'employee', 179, 1, '2025-08-21 05:53:56'),
(182, '3240701', '$2y$10$rbkyA/qQiJbYj7bXO8maxOCUiuKZrVSoMtyQjTXI9znG5ylK6kJCm', 'employee', 180, 1, '2025-08-21 05:53:57'),
(183, '3240702', '$2y$10$2gZeY3rOQRzPv/5OFFcs1u1WVXVyVAdvSBk4LW3L/T0Eqm7pK0daq', 'employee', 181, 1, '2025-08-21 05:53:57'),
(184, '3240703', '$2y$10$uEHX5RNcDdrRV7wnGw7SiuywDRi/58M2IaLNef.pB8NoX30XLx2h2', 'employee', 182, 1, '2025-08-21 05:53:57'),
(185, '3240704', '$2y$10$aul002jLLsIHrqhKarjxxOsFNfAz2jYmi730hkwBRHSFoAYNNmtMW', 'employee', 183, 1, '2025-08-21 05:53:57'),
(186, '3240705', '$2y$10$rcOHT6hHyzmIZyLqAhWTTeLPdYaM1NTWfBEuLlvGrzbiAXOcsitpa', 'employee', 184, 1, '2025-08-21 05:53:57'),
(187, '3240706', '$2y$10$.b.B/zWHNYXiz7TxEWACXe.6Ftz7r5fWMb3c0CmWWjXwvyQmUfm4a', 'employee', 185, 1, '2025-08-21 05:53:57'),
(188, '3240708', '$2y$10$QIIe0OLPzXzsx0O0As7at.IVkHhBA1FBByL0RSGpSc66IzEjkf/8u', 'employee', 186, 1, '2025-08-21 05:53:57'),
(189, '3240711', '$2y$10$ibQrIv0pQipFzloPUTJdeuNep9LgIOp0b25Ez4cm2gLxjm/EU9bge', 'employee', 187, 1, '2025-08-21 05:53:57'),
(190, '3240714', '$2y$10$i8vm0X4PCVGTyUM4zQcQZuqQEQQvdUzNHSgLlpx4hzRTy9dg5w6aW', 'employee', 188, 1, '2025-08-21 05:53:57'),
(191, '3240715', '$2y$10$lxNk7q8XGXgBAWj4YgOIUO14FKeRb5Ik49.SC2fAvHSbMvIyaWl4S', 'employee', 189, 1, '2025-08-21 05:53:57'),
(192, '3240801', '$2y$10$oCaI3W7lEQhqZkSRVNN33uqDr1chUOsFXaoHNa9lFPYE5B9spNF4S', 'employee', 190, 1, '2025-08-21 05:53:57'),
(193, '3240802', '$2y$10$16JCWAt6Ckhl3GhSHxziXO3VAVNavNEsuYWtxagqs.c29i7p9VqyG', 'employee', 191, 1, '2025-08-21 05:53:57'),
(194, '3240902', '$2y$10$mR9nYzfh6PnC/oHfxyOMcuPnLe/Zz3BkUH0o.0t4GSoLymWg9FkXy', 'employee', 192, 1, '2025-08-21 05:53:57'),
(195, '3240903', '$2y$10$UKHTFKeUAYuLGUPPzFlPXeEUizYDkJoJ2y2tuZ/LozrurfnVsGQ1W', 'employee', 193, 1, '2025-08-21 05:53:57'),
(196, '3241001', '$2y$10$z73TAwU6vQv6a/8gXRFAVOVcoO2Hh0kdv9hbjt5lDIvjMzs14/WOi', 'employee', 194, 1, '2025-08-21 05:53:57'),
(197, '3241002', '$2y$10$0zp36SSQWRyojzTChd0nQ.g0Y3Gmq4/yBFAywN4fsJDQnvJ/aswPG', 'employee', 195, 1, '2025-08-21 05:53:57'),
(198, '3241003', '$2y$10$QKkGUa5CrEUXEty9Xo957.YEa48MJrXM8wNmA3a6WiyDrYeb.Kf/2', 'employee', 196, 1, '2025-08-21 05:53:57'),
(199, '3241101', '$2y$10$uT57VnsckM/CeFtBdgXgPOegvikxJMqxXIxBVeAWIKD7oKN/2/9wS', 'employee', 197, 1, '2025-08-21 05:53:57'),
(200, '3241103', '$2y$10$FgP59aMkoKlAJa5XufTyB.8MrhRUndFS8cculN17IYNFIytp35JR.', 'employee', 198, 1, '2025-08-21 05:53:57'),
(201, '3241106', '$2y$10$vebgCKzrh2ul5XXNU5q0NOBF4Ohbf3KYQ.rdsluBS8WLHoXbM3OBK', 'employee', 199, 1, '2025-08-21 05:53:58'),
(202, '3241107', '$2y$10$xBzskuOGYzrjfk/CBwKetumk79gqIlqrLwswV9h9dD9/Co67i9X5m', 'employee', 200, 1, '2025-08-21 05:53:58'),
(203, '3241108', '$2y$10$bgNjHfGRFPE7NIPTy06DMOJmDLjBUx3pP3QByZmOId0huKlXu3j/O', 'employee', 201, 1, '2025-08-21 05:53:58'),
(204, '3241109', '$2y$10$XFx/1JYjI8KFHs00ibq22eYOpZDIdLwX5VBR5k.OjVLAGqY1HpbY2', 'employee', 202, 1, '2025-08-21 05:53:58'),
(205, '3241110', '$2y$10$ewX35HlyGKl13Rh8uEjOXeQW5qpdebOcbpgDCKZod2Xdxz3NSE0AW', 'employee', 203, 1, '2025-08-21 05:53:58'),
(206, '3241112', '$2y$10$K.cwfw2cFvA0yz9WRxk/LOC2BscFU3PEYrSgJlHtS.QlgmWXfowjG', 'employee', 204, 1, '2025-08-21 05:53:58'),
(207, '3241113', '$2y$10$.S9E71L9W5BYRNC2V4K2TuZCVdfxtFAMT8lPA2APKJOg/IUzttZdq', 'employee', 205, 1, '2025-08-21 05:53:58'),
(208, '3241115', '$2y$10$FYrGVXIucfz9wvCLFu3TcuZCxZbt.6OqX5h7jl7B0nERNas9TKUp2', 'employee', 206, 1, '2025-08-21 05:53:58'),
(209, '3241116', '$2y$10$GXLu.p4crQuBEDFU0LyXX.LV9qQDilwyUQv77quGG7pvsGQC6qa3a', 'employee', 207, 1, '2025-08-21 05:53:58'),
(210, '3241117', '$2y$10$QP7tcfv778Kd6Zhlm8Bcuu8YueyP5.zO12hNob24k.Yi2rFbit82i', 'employee', 208, 1, '2025-08-21 05:53:58'),
(211, '3241118', '$2y$10$Q7/M6u8ymJeTTZF5xUoKjOoqCtxwmbVRNoL5uR78n./K2C1oFEB7u', 'employee', 209, 1, '2025-08-21 05:53:58'),
(212, '3241119', '$2y$10$pSBWJYsiVNF2ktY3tchCeOLeqG6TlGm.v8M5nGoOYLVLkCIJytc5C', 'employee', 210, 1, '2025-08-21 05:53:58'),
(213, '3241120', '$2y$10$Xdzyg6tWvZLIm5o4mTFWPei8aqzqAUSBMhf5D7qk.iOM/QK4BdQCa', 'employee', 211, 1, '2025-08-21 05:53:58'),
(214, '3241124', '$2y$10$jkyJO5DBDev1w/BEV581kuQtlx6BFx9Lo4wQRRm/eT6XA3FNxCdte', 'employee', 212, 1, '2025-08-21 05:53:58'),
(215, '3241126', '$2y$10$C0jJ8Gs7zZP6TIKsHRYKIueZumNs4EjXRZ1UGwJxw/abZPrjfQ1QS', 'employee', 213, 1, '2025-08-21 05:53:58'),
(216, '3241128', '$2y$10$nE6qBlKHYdYSD1AyDSLdL.PcBfPiA3h4y2sTPuzCTeWQpkWbftjLq', 'employee', 214, 1, '2025-08-21 05:53:58'),
(217, '3241130', '$2y$10$iCiIJSSLfNl8rlc9PMLUfO4id4XVVUZ8ooSI2irPH7cOjlVs1xHTi', 'employee', 215, 1, '2025-08-21 05:53:58'),
(218, '3241131', '$2y$10$xSD7Kc89Jfs5DmCy5nkv6eBojihILdiZcBc6EXCjaFg49TwlQUiUW', 'employee', 216, 1, '2025-08-21 05:53:58'),
(219, '3241132', '$2y$10$846rlftEzmWlma0E9O33gOPyApoKgSO4EM/uuEmUaFyifL5qgYqg6', 'employee', 217, 1, '2025-08-21 05:53:58'),
(220, '3241135', '$2y$10$Z4PblIuozXS5JTz/RewImeSV96WPcM8fmSgbOC..dnfag4V9lExau', 'employee', 218, 1, '2025-08-21 05:53:59'),
(221, '3241136', '$2y$10$lLHv10Ftjw1zikuoS6tXT.A4JPSmsx4AmdOxH5xpIvwxxQczIzDB6', 'employee', 219, 1, '2025-08-21 05:53:59'),
(222, '3241137', '$2y$10$3U7I/YbeSYoSAdIUdEfoLO1YqLxROSa90M2rv7Z9xQdMk/EYwrXKm', 'employee', 220, 1, '2025-08-21 05:53:59'),
(223, '3241138', '$2y$10$Uyia.aR0cLKK/s5Vvom8zuhw8uyZibQXOO1DskDYXDPcmsC/FzAUq', 'employee', 221, 1, '2025-08-21 05:53:59'),
(224, '3241139', '$2y$10$5FviAy0RekKtZYO2yvh4ReXAKrXkuVvsVzBTTQNF/Dk.SolCTubzK', 'employee', 222, 1, '2025-08-21 05:53:59'),
(225, '3241140', '$2y$10$LQfMl8pck.HJULgafx6kn.YvXixTkQqE2khGaxnVsusYzE2JlKsUK', 'employee', 223, 1, '2025-08-21 05:53:59'),
(226, '3241141', '$2y$10$EdYTIM/aFIFkOute4OYQLuX066XLwoom0bVNIS3dbDpiwTBRIv65y', 'employee', 224, 1, '2025-08-21 05:53:59'),
(227, '3241142', '$2y$10$YpngTPYzEQK2vfGWDjWuR.L9JlThbotE0ys2mi67OI7Ga.9anlZcC', 'employee', 225, 1, '2025-08-21 05:53:59'),
(228, '3241143', '$2y$10$wS7rRydMNdkOAp6f3HfI0OeocWGvxB7n4OvM6AE69Tm9SRbpvJyuq', 'employee', 226, 1, '2025-08-21 05:53:59'),
(229, '3241144', '$2y$10$LzCZfWMLKtT8Zo69kWAbQexrf.2aLIZcWOWOd9sL2UJjftfAo1bYO', 'employee', 227, 1, '2025-08-21 05:53:59'),
(230, '3241202', '$2y$10$lYvG31FKdGqeE.0HfSbaj.3QNKormj3v7mln0l61BWy9Hk9sycIEa', 'employee', 228, 1, '2025-08-21 05:53:59'),
(231, '3241204', '$2y$10$pKKjYSq8vjEy7SnoyxzQwuNFlp4wAzA8wGBrzPj8H09DRRPq//eM2', 'employee', 229, 1, '2025-08-21 05:53:59'),
(232, '3241205', '$2y$10$Ts7Wm6wNJ9LDQEFa/EybouDfYWbH.w.Os915VDxK8wHlidxxCMpUu', 'employee', 230, 1, '2025-08-21 05:53:59'),
(233, '3241206', '$2y$10$2CVPmukFrnBedAlQYM6unOFc.bWrzvsA6OqpvGIp..CDCwkpoOSAO', 'employee', 231, 1, '2025-08-21 05:53:59'),
(234, '3250101', '$2y$10$QDZRHHModU9oJEAkIp9k5.x6jCLrlJ.yRYLzEBwO.SAT1OrZ7AUY6', 'employee', 232, 1, '2025-08-21 05:53:59'),
(235, '3250201', '$2y$10$qa6WNCH7IcMYqhuXEQpDk.ciBNiGEedRON6LiAcjQ59D8Gu0UxW42', 'employee', 233, 1, '2025-08-21 05:53:59'),
(236, '3250202', '$2y$10$y/YAFY0H0GeBVwyVloaJMeb92OeaWjkDwr.EIjoEAl7BgBxgvbNSW', 'employee', 234, 1, '2025-08-21 05:53:59'),
(237, '3250203', '$2y$10$xp3FhUavFeSLbh21x.Od5uy2nBVSLl8vpA.htLeWss7SPvNxCI8jK', 'employee', 235, 1, '2025-08-21 05:53:59'),
(238, '3250204', '$2y$10$1iDxxXnsakmasixdbTuAUuVFqX0fFQGSSl1uJGAYs7GkBwZndinkq', 'employee', 236, 1, '2025-08-21 05:53:59'),
(239, '3250205', '$2y$10$vPdkM3ZSLiysRuV.TYzfienCNQzJCCfQGzA8svnAeFg9f.WR3Soe.', 'employee', 237, 1, '2025-08-21 05:54:00'),
(240, '3250206', '$2y$10$HagUtMuJhQBXipiD34INOuZgq5J6xMz7FkRoW94qHe6JDVW.2.Mb.', 'employee', 238, 1, '2025-08-21 05:54:00'),
(241, '3250207', '$2y$10$RmtYxDzhS4p0X.nIVvnhDOJoqqlq4sQuOKrefABohlE6gQwZb8X3.', 'employee', 239, 1, '2025-08-21 05:54:00'),
(242, '3250210', '$2y$10$MManNLXNFiw0aWAy9fl/Oe0ZIRZPu2UrDwk7v6tOpFUNiL687aQZG', 'employee', 240, 1, '2025-08-21 05:54:00'),
(243, '3250211', '$2y$10$AZ7MxEUTao1VXxmOnzPizO8kMEuSdWI6Z49aMck554EbtsP0UFNSm', 'employee', 241, 1, '2025-08-21 05:54:00'),
(244, '3250213', '$2y$10$sLys1XkQPtuVWmjO6sP4SethNzK4kc8JTEy7jMj4pSmfPF1s2Gl.a', 'employee', 242, 1, '2025-08-21 05:54:00'),
(245, '3250214', '$2y$10$S/va1XnJ9TwDopMYdOmB/eh2Sgi2pKM2zpV.uiy.UK0SEWAoslXbi', 'employee', 243, 1, '2025-08-21 05:54:00'),
(246, '3250217', '$2y$10$TcZGYqlgePgn2oaHB4uXdOEMIEjZ9LKuhab6/zPSWQnosYXpPUHKO', 'employee', 244, 1, '2025-08-21 05:54:00'),
(247, '3250218', '$2y$10$9lzwNIVcfI4ET8M422mtTOQHoPIu5t5x8Y3NJN4ynVaEsnPCbGLeS', 'employee', 245, 1, '2025-08-21 05:54:00'),
(248, '3250301', '$2y$10$Hx6Uvy3j1OqdlThmc86.4Opya4/./OMadJQ4suMuKqrTPccoKM2Sy', 'employee', 246, 1, '2025-08-21 05:54:00'),
(249, '3250302', '$2y$10$8sL0zF8nqWfS.r.qbcYUee59yneJgUORtln2P8A4A1GelX1kOxMQa', 'employee', 247, 1, '2025-08-21 05:54:00'),
(250, '3250303', '$2y$10$S.FpYQEQ8wBeoNYbQBRm0O5S4/ixymaAaWthkvzeXqJccg0JKaZFe', 'employee', 248, 1, '2025-08-21 05:54:00'),
(251, '3250304', '$2y$10$vISf6EJ7Otv.QVgfgDRTf./gqSJlUpZeg/cr7NDDEQjhxZuQK4bkS', 'employee', 249, 1, '2025-08-21 05:54:00'),
(252, '3250305', '$2y$10$VWLPASAG7xj6IJ81v2g48.8oceBiWAk05dt5trUVroTb5T.fdIana', 'employee', 250, 1, '2025-08-21 05:54:00'),
(253, '3250306', '$2y$10$BRIkMoFYrrHhUq13IzHhX.06BtnVviUajva892JszsTEFe.gYEx4i', 'employee', 251, 1, '2025-08-21 05:54:00'),
(254, '3250401', '$2y$10$PXDT5Zw0/3GgBk3SKkhdOucZ2K3OqKsYNUfUVlCkT//EuA6434LTi', 'employee', 252, 1, '2025-08-21 05:54:00'),
(255, '3250405', '$2y$10$zapb8Ri70GpuxuOPtcUFje.TwoWnBI32MN0cMEb0Wuz9//NjrAgD2', 'employee', 253, 1, '2025-08-21 05:54:00'),
(256, '3250407', '$2y$10$XgnN1tJmUrA2kQaYdsryme6ehF.MQBhAApngjD50JnJ6MIqddLoP2', 'employee', 254, 1, '2025-08-21 05:54:00'),
(257, '3250408', '$2y$10$BRYfFmNC7wuQuUd3UOCsrOTIGSzDj84Af7O4XxAmH.Se1lpD9xL..', 'employee', 255, 1, '2025-08-21 05:54:00'),
(258, '3250501', '$2y$10$vdKPvqxF5DaOBPi3PPAPRu3QwVm43jCI4SXqniv0FpSopnlDAoui.', 'employee', 256, 1, '2025-08-21 05:54:01'),
(259, '3250504', '$2y$10$erruzGy3a8ygZLL/rUIO/.t2GfGzwYGq3AHJ6PylcO72Cx9Z6C/jG', 'employee', 257, 1, '2025-08-21 05:54:01'),
(260, '3250506', '$2y$10$Rl1azHLcFP6c3hfWGsByoelrbGy3FRt3qw8GHrmVlO6Y7yvgW8W/.', 'employee', 258, 1, '2025-08-21 05:54:01'),
(261, '3250507', '$2y$10$RmJ71Pt2B7/Xx8XqrDLjye7J/fs/N8xYCiDRT5/j1IYG8GzSsWxCS', 'employee', 259, 1, '2025-08-21 05:54:01'),
(262, '3250508', '$2y$10$d2Vs65ghBSJ.l9PNhsUSpOzd6LZtfDcucSzwVWe3dZXBOMfqwK0g2', 'employee', 260, 1, '2025-08-21 05:54:01'),
(263, '3250509', '$2y$10$QZqU00PWJ0keu8Y.EAGKi.W3hOIEHBx9plfspGUhGIssotXzbx46a', 'employee', 261, 1, '2025-08-21 05:54:01'),
(264, '3250510', '$2y$10$o8EztzjXefdraRDo25PjE.NVYUw1.Lb8zShaZB0LmVpBO2omcLHqq', 'employee', 262, 1, '2025-08-21 05:54:01'),
(265, '3250511', '$2y$10$iLrCcYnV2FAg1jONqcEi0OL9H2PV/ceGRn8IRes.9ucrKo9lMSSwi', 'employee', 263, 1, '2025-08-21 05:54:01'),
(266, '3250512', '$2y$10$huvWI75jG/56vopSTVIStueheV83UighxPA1Ag7ZXU2xSi4ye9VT.', 'employee', 264, 1, '2025-08-21 05:54:01'),
(267, '3250513', '$2y$10$iMnDSdTkF8IXECVf6nPb5OpHizvoNj6xH7dZB8m6KTF.J0VrYy9mS', 'employee', 265, 1, '2025-08-21 05:54:01'),
(268, '3250515', '$2y$10$ibw1O77.jMmXqi8UmgGI3.C5p.03coeGlUNCviJz/VRxGOjpC2kxK', 'employee', 266, 1, '2025-08-21 05:54:01'),
(269, '3250516', '$2y$10$vF3C2URDKac0pRJCGssN3./OvMBOKaS8RLRg5wj2zEdzlZyMuldNW', 'employee', 267, 1, '2025-08-21 05:54:01'),
(270, '3250517', '$2y$10$uaimBYYtjauv68ldPolBgOchLuCFmfu00H5UqwKWXBsWP1WCiQw9S', 'employee', 268, 1, '2025-08-21 05:54:01'),
(271, '3250518', '$2y$10$dSwqJrrtgstb2jQIo9czfuXiKUMyZAfTJaoa8Hzyc6WbK8ZlKYfkK', 'employee', 269, 1, '2025-08-21 05:54:01'),
(272, '3250519', '$2y$10$uXHXPq5YFblPp36wbRvvyeOpR9WpeH/BYiqRHkrb/rN0G0N5cfkyi', 'employee', 270, 1, '2025-08-21 05:54:01'),
(273, '3250520', '$2y$10$p/yeQ0qB.M//mBExe3bik.R.rh9fp0Ul9Q0CzGRytJoknViJr6R4W', 'employee', 271, 1, '2025-08-21 05:54:01'),
(274, '3250521', '$2y$10$b8P2OJxWWg6r8f63rJ0IBOM850BSAmKniaVJPi1JKeOuta1qkFuWy', 'employee', 272, 1, '2025-08-21 05:54:01'),
(275, '3250602', '$2y$10$Ak.jXncN.78IJkOLFIcs2.6dw5ThIbbqwrpJb8drWkPjcxUjhRd5S', 'employee', 273, 1, '2025-08-21 05:54:01'),
(276, '3250603', '$2y$10$SwROvpDdXlqJdloyxKN75.i2lahlm/I1jyAl//vPewjXbHrsZIP/.', 'employee', 274, 1, '2025-08-21 05:54:01'),
(277, '3250604', '$2y$10$CmhOPNGMMryqbPrkKR5AAu8zuAbwSFGDXnBK/mi68Udely4kd948e', 'employee', 275, 1, '2025-08-21 05:54:01'),
(278, '3250605', '$2y$10$3uH8xKIEDGKpcxNiDcqRcuB63RB6NnpOYj/Z5/qvOCGdn8pGqKx4O', 'employee', 276, 1, '2025-08-21 05:54:02'),
(279, '3250606', '$2y$10$Ej/Ppbou9o679./Q8YlJpebAlp2N.PDbaJ6v1/WwSQTD/Qvyyr/Uu', 'employee', 277, 1, '2025-08-21 05:54:02'),
(280, '3250607', '$2y$10$J96TdspKAvbHBpSmpHdTpefv9SYzfPRRxPCSmRKQrFdkYddkz66pm', 'employee', 278, 1, '2025-08-21 05:54:02'),
(281, '3250608', '$2y$10$bKYTMAaufs/wu4PiwkQtn.F3jxLtRLnedRn.hWnKPiJ6yHc4AsbC2', 'employee', 279, 1, '2025-08-21 05:54:02'),
(282, '3250609', '$2y$10$I.sBId1pH.esd9K1Ty1qsu1PEMhlwh5xqUIdiKt0AABu1wUuS9Xjm', 'employee', 280, 1, '2025-08-21 05:54:02'),
(283, '3250610', '$2y$10$fQSoIR2S4BQkCVWPfAUbp.jAUQ3M6kI5Vj42NbaZ4Yb5ckIWfL7lC', 'employee', 281, 1, '2025-08-21 05:54:02'),
(284, '3250611', '$2y$10$EVRljYD.79FGV2tHfNlxSeHbgSpNaa4Bck76L7M45z.QXAUXCKqWW', 'employee', 282, 1, '2025-08-21 05:54:02'),
(285, '3250612', '$2y$10$BWs6FwSC.eryYqvVOVHLSO8T6cJ5WNgZTcUvNcn7lHb/cznqq3zUi', 'employee', 283, 1, '2025-08-21 05:54:02'),
(286, '3250613', '$2y$10$V1FOcgBJV3jCdVW4EuiqRuuIs9/xsSxHTYunyBqa7F0bCZCphLl0a', 'employee', 284, 1, '2025-08-21 05:54:02'),
(287, '3250614', '$2y$10$YIpR7YrETd7N3faxTHg0mO.SNDyL7gVgZS6Y4GNL1Qv0zC.UjjUly', 'employee', 285, 1, '2025-08-21 05:54:02'),
(288, '3250615', '$2y$10$viZE/fT6vDfs0mfSZ9c7ne2x/Ik0wQ/T2T7C0EcnB4BZOVL0HQRLa', 'employee', 286, 1, '2025-08-21 05:54:02'),
(289, '3250616', '$2y$10$dwi35t.0fiDv6TlOUvdaueZ0TcqwYe2znfPNjJJ2fwH9TG2wgg7Aa', 'employee', 287, 1, '2025-08-21 05:54:02'),
(290, '3250617', '$2y$10$.l.xyEvh98ZGw0wdJTKfluBijX8dwawVpkMsqxJEBi5y9xSbQJUai', 'employee', 288, 1, '2025-08-21 05:54:02'),
(291, '3250701', '$2y$10$YzT26ZsFqKmni4rPtG.0IuuAV1kmT.kRu8xNh2fUZfwmJUnDhaUbi', 'employee', 289, 1, '2025-08-21 05:54:02'),
(292, '3250702', '$2y$10$aWqh6lDMNBhHunHjHvXaGe3veheQX4K0eo16Idvy/FNp.c6Dr/irS', 'employee', 290, 1, '2025-08-21 05:54:02'),
(293, '3250703', '$2y$10$WJfoUeOzpXhbso5yIgA/hOTHnKNryRHS.T7K0ncEnOOtr.6vfvJSW', 'employee', 291, 1, '2025-08-21 05:54:02'),
(294, '3250704', '$2y$10$zPg/bt44T9ASle1yWjWxaOB/8Wr84QUEV2J3NOFdA.tUSfNCtg2bu', 'employee', 292, 1, '2025-08-21 05:54:02'),
(295, '3250705', '$2y$10$JdV8i3y5K.KcRT4Xj6jDH.4IORJgNH/RaXVeLSJWkuY5xQ368R55q', 'employee', 293, 1, '2025-08-21 05:54:02'),
(296, '3250706', '$2y$10$4RxVvitHvYhsOLsAtw7LvOMHPf7zQvuBU9uQRuA0Kz4mXmVs/DVyO', 'employee', 294, 1, '2025-08-21 05:54:02'),
(297, '3250801', '$2y$10$RNrvrAzJ0SsLEPOZZPdGmu8NmORthpUSgWExozvm.54ow2.T812ri', 'employee', 295, 1, '2025-08-21 05:54:03'),
(298, '3250802', '$2y$10$0QTB1R2zpqCBIcY5BFIvXO46S3pE9PSc7BYqlip8LzpIBPwf9u4jG', 'employee', 296, 1, '2025-08-21 05:54:03'),
(299, '3250803', '$2y$10$KbOxBDfqFdIfnleue0kbeum75.bDMmtzPXKvmKUhG5M/IK9ikJilG', 'employee', 297, 1, '2025-08-21 05:54:03'),
(300, '3250804', '$2y$10$RwFElEdT3kD0pGPG6CZNJOGybcak/a2OLGplWzVWG2Bbi0DD9hwZa', 'employee', 298, 1, '2025-08-21 05:54:03'),
(301, '3250805', '$2y$10$VGBoviyPr52LvsYVXhm6se31PFVquqAVsUN18JtemjP9qttULucQy', 'employee', 299, 1, '2025-08-21 05:54:03'),
(302, '3250806', '$2y$10$kpU.Kix6uIhUJnM.Yh0q2Owp9/hPlzP2GHkQ7gqbvaq5yKV9h9b.u', 'employee', 300, 1, '2025-08-21 05:54:03'),
(303, '3250807', '$2y$10$7DtNIgB5U8MpKHOwzDK7QOj2mvmgm9YfoE69TlmnVEkNZg6rcYruK', 'employee', 301, 1, '2025-08-21 05:54:03'),
(304, '3250808', '$2y$10$qX1sZn3SU9udvw1G1hkSUuhZwTOWFcduENCJA.SdwBjxMqHgaVDOy', 'employee', 302, 1, '2025-08-21 05:54:03'),
(305, '3250809', '$2y$10$b.jl83acJqkbzRAmf4s94.qu8z96HXgh41OajRtvnmUQs3TRJy0eW', 'employee', 303, 1, '2025-08-21 05:54:03'),
(306, '3250810', '$2y$10$Wz4/JWoyDcujWtULHjyAWOGpEOIL5OWzlquuq4YlVBLC1g1xF8YpC', 'employee', 304, 1, '2025-08-21 05:54:03'),
(307, '9703002', '$2y$10$EmmTWlXX6g/kZF8B34yWvubR4NgGWI5pi38mxjbGqMbCblPqFMJC2', 'employee', 305, 1, '2025-08-21 05:54:03'),
(308, '9706019', '$2y$10$XHaUuf9C7LYUXJZNLXtFrOQ2ef.fgG9SdHGfx2FgG0B80PqullHhu', 'employee', 306, 1, '2025-08-21 05:54:03'),
(309, '9706034', '$2y$10$rAaoxfehiCC2bE5v7YpiZObid5ppio6rzJxfEuAScVG6L81RYAem6', 'employee', 307, 1, '2025-08-21 05:54:03'),
(310, '9709079', '$2y$10$FGTzLL0gptc/SeOH9nRMw.pkgK92OxWgI6QYpfSSftB6wpCwMr5Sy', 'employee', 308, 1, '2025-08-21 05:54:03'),
(311, '9709083', '$2y$10$BDg/OLi4pY1maxqiJS3Cpuv2I9PrJPl/x1/8GUuiNGVU0ysSk7pjy', 'employee', 309, 1, '2025-08-21 05:54:03'),
(312, '9709097', '$2y$10$pNiELZX5BTKqW.q7niH7wOXoNp8K6e4z9itjuDt5nKeVklSz1bKDO', 'employee', 310, 1, '2025-08-21 05:54:03'),
(313, '9709107', '$2y$10$D/nbqMcHFVLDqkC9Nta/dekG77Yk10GSSZfAmNMHK69jUz48Z/NhS', 'employee', 311, 1, '2025-08-21 05:54:03'),
(314, '9802136', '$2y$10$r83e6G5.1ZOmummBHqiyru01qtjX8kIZFL5pAC4WSwn./qnGZh4Um', 'employee', 312, 1, '2025-08-21 05:54:03'),
(315, '9802137', '$2y$10$vAdeMIO7LEHnugTKzzWPZ.r4LCuh34E/kP/FREdVS89eo3q4uzOcW', 'employee', 313, 1, '2025-08-21 05:54:03'),
(316, '9802141', '$2y$10$GDQzOTMuyUQC5rdcHjh.QOA5lRDJ52Vqx5e9JSoyLf4EhrWoNyj56', 'employee', 314, 1, '2025-08-21 05:54:04'),
(317, '9802171', '$2y$10$Hb4nsE8s51X2j07NTaOIMOqWRMgs59eROWUGv4SkGLem.dwyl6XOa', 'employee', 315, 1, '2025-08-21 05:54:04'),
(318, '9802176', '$2y$10$Iw8GoV8jnwqMHR1tU9KHyeMeBAl6shlnynQOh/xUXw/lUsOQbMTt2', 'employee', 316, 1, '2025-08-21 05:54:04'),
(319, '9807223', '$2y$10$JoCJIXCJXXYpXmsckHErdO.G25KHVvIKj7SBN1FKPlvyfyumK.tPi', 'employee', 317, 1, '2025-08-21 05:54:04'),
(320, '9807228', '$2y$10$Fhsfn2qqdXRIFhKVCwmn4.XxKLgXVMY6cOlBXzTB6l9FTcWNhLyse', 'employee', 318, 1, '2025-08-21 05:54:04'),
(321, '9812245', '$2y$10$M/YAEOXThcUoTCenbIm/EuyyihHd2G9OMX41C1I9fRIBXpFH7B53G', 'employee', 319, 1, '2025-08-21 05:54:04'),
(322, '9902263', '$2y$10$1vZeHq2PiJMYuZui8w1Sm.wcpjfolCVS2ie6L3eA6piZI4SRGUcS.', 'employee', 320, 1, '2025-08-21 05:54:04'),
(323, '9902269', '$2y$10$7nES0VkM.yGRNKe.Yk2ml.3gjX1P0.2qcak9B3t0qkLcHmsuPogUa', 'employee', 321, 1, '2025-08-21 05:54:04'),
(324, '9902285', '$2y$10$qpldO4YVdduxF2qmj8V7qejK06GUM2ae0dCcu4nc0bHgLGUE.Huw6', 'employee', 322, 1, '2025-08-21 05:54:04'),
(325, '9902302', '$2y$10$zWz/ul3zLFTaDN1wrhn9HuV.nGXGxb5..C8QtA/0giK83CYa5Gxm.', 'employee', 323, 1, '2025-08-21 05:54:04'),
(326, '9903335', '$2y$10$OidEHWw1tgXuVypwhah6rOomaiNqtJ0kj.vyvmDVwFofKsmn7RhN6', 'employee', 324, 1, '2025-08-21 05:54:04'),
(327, '9903373', '$2y$10$1sDpayn6hInUq8E/tNyVn.LkoZVUgH172U04iWsKAJhoCG5F2/kuq', 'employee', 325, 1, '2025-08-21 05:54:04'),
(328, '9903386', '$2y$10$WkUY5GHhMb6.keYG0VLMGuMpDEAWjAzDg11Jqt/9W6UsKTtFC/XCu', 'employee', 326, 1, '2025-08-21 05:54:04'),
(329, '9903424', '$2y$10$99LBmHiTwCFL6X7HEVl/muUDiGu6QIa5PsP2BREj0JhkA4JGQc9ve', 'employee', 327, 1, '2025-08-21 05:54:04'),
(330, '9903430', '$2y$10$ffHRWCG.NSAIcjpa7QgOOeXVIRGfawQ.CEJTSpfcjhMaseCObfE7m', 'employee', 328, 1, '2025-08-21 05:54:04'),
(331, '9904440', '$2y$10$XS1mOW1O4sdYrgZHpVqT6OOIC.EC..Jncv.kLAh2SygC5fUfL1SG6', 'employee', 329, 1, '2025-08-21 05:54:04'),
(332, '9904449', '$2y$10$uUpDazcM6FbpeNRyCpuzfOIoD8kybTUrD/JoREMQmHEYkx0eWr5yq', 'employee', 330, 1, '2025-08-21 05:54:04'),
(333, '9904477', '$2y$10$OhUz.MB4a4uQFD48Q9H/wOYjWdhJlDmBcRho/2J6MrJslHXuObApO', 'employee', 331, 1, '2025-08-21 05:54:04'),
(334, '9905535', '$2y$10$qkiCwEJ3P7qHxAvtBIswJeCo/CKqUm2j9/73F8evh96ccJKNDQdsy', 'employee', 332, 1, '2025-08-21 05:54:04'),
(335, '9905575', '$2y$10$t8mlnD65mfqb1/tQPsbZiONkKXtlKKqCfHWipE5H1nA331Yzp.khS', 'employee', 333, 1, '2025-08-21 05:54:05'),
(336, '9905584', '$2y$10$XrlB/e.LeaKaSF4Rn4Tz4.fA5Fd22bp4NuOkoBunyE3xKxuqYyHKO', 'employee', 334, 1, '2025-08-21 05:54:05'),
(337, '9905595', '$2y$10$hN4U7CWOx2OZFkxRx7tncOyhlf2oaDVVa.DAao87ZiCpVwUXQMDFm', 'employee', 335, 1, '2025-08-21 05:54:05'),
(338, '9905654', '$2y$10$UBqroTkr2MtPB/X1f35E1.axPBvkd/iSyEZstEVsCW12Oy/KeFXvK', 'employee', 336, 1, '2025-08-21 05:54:05'),
(339, '9907686', '$2y$10$qZv5lrvWb0WyJ5RD731juOBLo6zpggF73krdZZEpWBZv6hYkMQLcW', 'employee', 337, 1, '2025-08-21 05:54:05'),
(340, '9910704', '$2y$10$bEO9JHsWhjg0SuMxjgFJj.dCxb7TXACcAVdNV6431merHXvz.EZRu', 'employee', 338, 1, '2025-08-21 05:54:05'),
(341, '9911765', '$2y$10$Xvm8d3syVXzX/4/FOobmYeRmFG3I/CQodJcU/cP.3UREJ47LdTdkW', 'employee', 339, 1, '2025-08-21 05:54:05'),
(342, 'admin', '$2y$10$N3RBH2gU7wex23kGB9QtKeYYVk9roxGVy8coGlkMAuonJ6tqgXfci', 'admin', NULL, 1, '2025-08-21 05:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `circular`
--
ALTER TABLE `circular`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_dept_code` (`DeptCode`);

--
-- Indexes for table `clasificcode`
--
ALTER TABLE `clasificcode`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_departments_dept_code` (`dept_code`),
  ADD KEY `idx_departments_name` (`name`);

--
-- Indexes for table `desigcode`
--
ALTER TABLE `desigcode`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `EmpNo` (`EmpNo`),
  ADD KEY `idx_empno` (`EmpNo`),
  ADD KEY `idx_deptcode` (`DeptCode`),
  ADD KEY `idx_clascode` (`ClasCode`),
  ADD KEY `idx_destcode` (`DestCode`),
  ADD KEY `idx_grpcode` (`GrpCode`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `groupcode`
--
ALTER TABLE `groupcode`
  ADD PRIMARY KEY (`GrpCode`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `japanemployees`
--
ALTER TABLE `japanemployees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `EmpNo` (`EmpNo`),
  ADD KEY `idx_japan_empno` (`EmpNo`),
  ADD KEY `idx_japan_deptcode` (`DeptCode`),
  ADD KEY `idx_japan_clascode` (`ClasCode`),
  ADD KEY `idx_japan_destcode` (`DestCode`),
  ADD KEY `idx_japan_grpcode` (`GrpCode`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `settlements`
--
ALTER TABLE `settlements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `settlement_items`
--
ALTER TABLE `settlement_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trip_emp` (`employee_id`),
  ADD KEY `idx_trip_status` (`status`);

--
-- Indexes for table `trip_approvals`
--
ALTER TABLE `trip_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trip_id` (`trip_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_trip_approvals_circular` (`circular_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `circular`
--
ALTER TABLE `circular`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_approvals`
--
ALTER TABLE `trip_approvals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
