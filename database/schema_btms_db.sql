-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2025 at 07:54 PM
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
('1', 'OPERATOR'),
('2', 'NON OT'),
('3', 'MANAGER'),
('4', 'STAFF'),
('5', 'NON OPERATOR'),
('6', 'DRIVER'),
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
  `name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_code`, `name`, `created_at`) VALUES
(1, '31', 'Accounting & Adm', '2025-08-19 07:17:48'),
(4, '34', 'Assy 2 YRS', '2025-08-19 07:17:48'),
(8, '38', 'Utility Control', '2025-08-19 07:17:48'),
(9, '39', 'General Affairs', '2025-08-19 07:17:48'),
(11, '41', 'Human Resources', '2025-08-19 07:17:48'),
(14, '44', 'Monozukuri', '2025-08-19 07:17:48'),
(15, '45', 'Inspection', '2025-08-19 07:17:48'),
(20, '50', 'Material Control', '2025-08-19 07:17:48'),
(23, '54', 'Production Control', '2025-08-19 07:17:48'),
(25, '56', 'Quality', '2025-08-19 07:17:48'),
(26, '58', 'Shipping Control', '2025-08-19 07:17:48'),
(30, '62', 'System Engineering', '2025-08-19 07:17:48'),
(31, '63', 'Technical Standard', '2025-08-19 07:17:48'),
(34, '66', 'Assy 1 LED', '2025-08-19 07:17:48'),
(35, '67', 'Packing', '2025-08-19 07:17:48'),
(37, '69', 'Manufacturing 2', '2025-08-19 07:17:48'),
(38, '70', 'Process Engineering', '2025-08-19 07:17:48'),
(39, '71', 'Maint. Engineering', '2025-08-19 07:17:48'),
(40, '72', 'SMD LED', '2025-08-19 07:17:48'),
(41, '73', 'SMD YRS', '2025-08-19 07:17:48'),
(43, '75', 'Manufacturing Control', '2025-08-19 07:17:48'),
(44, '76', 'Assembly Manufacturing', '2025-08-19 07:17:48'),
(47, '78', 'Manufacturing3', '2025-08-19 07:17:48'),
(49, '79', 'Manufacturing 1', '2025-08-19 07:17:48'),
(50, '80', 'Engineering', '2025-08-19 07:17:48'),
(51, '77', 'Adm2', '2025-08-19 07:17:48'),
(52, '81', 'Technical Assitant', '2025-08-19 07:17:48'),
(53, '82', 'Manufacturing 1,3', '2025-08-19 07:17:48'),
(54, '83', 'QC/Eng suport', '2025-08-19 07:17:48'),
(55, '91', 'Accounting', '2025-08-19 07:17:48'),
(57, '86', 'Manufacturing Dept', '2025-08-19 07:17:48'),
(58, '', 'IT', '2025-08-19 07:17:49'),
(61, '87', '(Dept 87)', '2025-08-19 07:17:49'),
(62, '90', '(Dept 90)', '2025-08-19 07:17:49'),
(63, '01', '(Dept 01)', '2025-08-19 07:17:49');

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
('11', 'Security'),
('12', 'Senior GM'),
('13', 'Senior Mgr'),
('14', 'Staff'),
('15', 'Technician'),
('16', 'Driver'),
('17', 'Sekretaris Eksekutif'),
('18', 'Presiden Director'),
('19', 'Senior Managing Director'),
('2', 'Cleaner'),
('20', 'Direktur & Plant Manager'),
('21', 'Sekretaris'),
('22', 'Director'),
('23', 'Komisaris'),
('24', 'Manager 1'),
('25', 'Manager 2'),
('26', 'Manager 3'),
('27', 'Chief Engineer'),
('28', 'Asst. Manager2'),
('29', 'Group Leader'),
('3', 'Clerk'),
('30', 'Sales&Marketing Executive'),
('31', 'Marketing Executive'),
('32', 'Asst. GM'),
('33', 'Interpreter'),
('34', 'Junior Technician'),
('35', 'Director'),
('36', 'SSO'),
('37', 'Finance Advisor'),
('4', 'Engineer'),
('5', 'General Manager'),
('6', 'Leader'),
('7', 'Mail Boy'),
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
  `department_id` int DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `EmpNo`, `Name`, `DeptCode`, `ClasCode`, `DestCode`, `Sex`, `Resigned`, `GrpCode`, `department_id`, `designation_id`, `created_at`) VALUES
(3, '2017041', 'Kodama Hidenobu', 'JG', NULL, '4', 'M', NULL, NULL, NULL, NULL, '2025-08-19 08:47:24'),
(4, '2023041', 'Kazuyasu Ohki', 'JP', NULL, '22', 'M', NULL, NULL, NULL, NULL, '2025-08-19 08:47:24'),
(10, '2010008', 'Hisafumi Muramoto', 'JG', NULL, '5', 'M', NULL, NULL, NULL, NULL, '2025-08-19 08:47:24'),
(11, '2015071', 'Hiroo Kubo', 'JD', NULL, '22', 'M', NULL, NULL, NULL, NULL, '2025-08-19 08:47:24'),
(13, '2019042', 'Shigeru Sonoda', 'JG', NULL, '5', 'M', NULL, NULL, NULL, NULL, '2025-08-19 08:47:24'),
(6512, '2001785', 'Julizal', '39', '001', '06', 'M', '', '002', NULL, NULL, '2025-08-19 07:17:48'),
(6796, '2001786', 'Boy Karawan', '39', '001', '11', 'M', '', '002', NULL, NULL, '2025-08-19 07:17:48'),
(6797, '2004790', 'Titik Pujiati', '90', '008', '03', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6798, '2005814', 'Manuarang Tua Manalu', '39', '009', '08', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6799, '2006833', 'Trubus Sulistiyo', '71', '008', '15', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(6800, '2006839', 'Neneng Ekawati', '67', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6801, '2009901', 'Ani Yusita', '70', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6802, '2009923', 'Yudia Eka Putra', '38', '002', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6803, '2010973', 'Ida Afrita', '73', '001', '29', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6804, '2011015', 'Muslim', '39', '006', '16', 'M', '', '007', NULL, NULL, '2025-08-19 07:17:48'),
(6805, '2102069', 'Polmen Sihombing', '87', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6806, '2206106', 'Cindy Cahyadi', '54', '003', '05', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6807, '2206162', 'Made Joko Santosa', '77', '002', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6808, '2206164', 'Manatap', '70', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6809, '2206172', 'Sujaka Bravo', '86', '003', '05', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6810, '2207180', 'Robi Salam', '38', '008', '10', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(6811, '2207197', 'Ivan Imaduddin', '80', '003', '05', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6812, '2209216', 'Erni Rosana Purba', '87', '005', '03', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6813, '2209244', 'Sri Nirwani Harahap', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6814, '2309314', 'Musrifan', '39', '001', '02', 'M', '', '004', NULL, NULL, '2025-08-19 07:17:48'),
(6815, '2404389', 'Julita Lastiur Simarmata', '45', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6816, '2410268', 'Ari Susanto', '38', '008', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6817, '2502310', 'Masruri', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6818, '2502314', 'Saiful Bahri', '69', '001', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6819, '2603382', 'M. Hidayah', '39', '002', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6820, '2609462', 'Haidil', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6821, '2809772', 'M. Muin Sujudi', '58', '003', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6822, '2809786', 'Imam Edi Raharjo', '38', '001', '06', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(6823, '2909867', 'Anhar', '71', '005', '15', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(6824, '3100101', 'M. Dwi Nugroho', '91', '003', '08', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6825, '3100562', 'Igral Eliyas', '01', '004', '04', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(6826, '3110517', 'Wili Brodus Oni Meku', '39', '001', '11', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6827, '31107140', 'Santia Br. Sibuea', '73', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6828, '31107143', 'Rudy Priyantoro', '87', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6829, '3110851', 'Tias Utami', '73', '001', '35', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6830, '3110852', 'Dedy Heryanto', '87', '001', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6831, '3110903', 'Henda Astalia', '73', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6832, '3111033', 'Medriko', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6833, '3111053', 'Tiodin Marince', '69', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6834, '3111138', 'Tri Ayu Lestari', '87', '001', '35', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6835, '3120525', 'Wiwi Hetiamala', '87', '001', '35', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6836, '3120534', 'Peni Roy', '87', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6837, '3120705', 'Agus Ryanto', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6838, '3120806', 'Tetty Herlina Br Tamba', '69', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6839, '3120905', 'Yol Anabrang Panggabean', '54', '007', '09', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6840, '3121042', 'Aprilyani Kertina Siagian', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6841, '3121201', 'Lidia Simanjuntak', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6842, '3121207', 'Hotria Purba', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6843, '3121215', 'Juni Elfrida Hasugian', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6844, '3130101', 'Riko Andreas Ginting', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6845, '3130104', 'Marnikana Silalahi', '73', '001', '35', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6846, '3130115', 'Lishabri', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6847, '3130125', 'Christyan Kuheba', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6848, '3130140', 'Mascko Gultom', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6849, '3130149', 'Jarot Iswanto', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6850, '3130201', 'Fadjar Sapta Putro', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6851, '3130309', 'Zeronikho Caniago', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6852, '3130401', 'Henriaman Saragih', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6853, '3130410', 'Robin Sarimujie', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6854, '3130505', 'Rismaida Yulynar Sihaloho', '87', '005', '10', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6855, '3130515', 'Wahyudi Sugara', '87', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6856, '3130523', 'Jepri', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6857, '3130705', 'Al Ghapur', '87', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6858, '3130708', 'Berdikari Gultom', '50', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6859, '3130713', 'Nofrianto', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6860, '3130718', 'Yezriel Hamonangan Ginting', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6861, '3130819', 'Syafrizal', '50', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6862, '3130829', 'Yuli Ernawati', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6863, '3130848', 'Elpriaty Samosir', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6864, '3130871', 'Naomi Ida Irawati Simangunsong', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6865, '3130906', 'Andri Wijaya', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6866, '3130916', 'Naqiuddin Muslimin', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6867, '3130927', 'Mediani Restia', '50', '007', '09', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6868, '3130945', 'Kiki Fatimah', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6869, '3130971', 'Evi Safitri', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6870, '3130987', 'Dian Ekowati', '73', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6871, '3131006', 'Yupi Andriyani', '69', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6872, '3131025', 'Vivi Imayani Simanjuntak', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6873, '3131036', 'Hardiati', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6874, '3131042', 'Riki Apriyan', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6875, '3131045', 'Dewita Tampubolon', '58', '005', '10', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6876, '3131051', 'Norita Lance Sitorus', '87', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6877, '3131101', 'Muammar Kadavie', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6878, '3131135', 'Husni Tamrin', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6879, '3131218', 'Edo Zulputra', '67', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6880, '3131222', 'Bisri Mukhojin', '73', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6881, '3131225', 'Ari Widayanto', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6882, '3131238', 'Surti', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6883, '3140104', 'Okky Hariabi', '50', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6884, '3140108', 'Lermi Silalahi', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6885, '3140113', 'Suprijal', '54', '001', '10', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6886, '3140127', 'Oktavianus Tarigan', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6887, '3140202', 'Riki Martono', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6888, '3140208', 'Juni Prapto Sinaga', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6889, '3140212', 'Mustonginah', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6890, '3140215', 'Franita', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6891, '3140304', 'Agustynah. S', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6892, '3140508', 'Kiki Rizki Sintia Devy', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6893, '3140526', 'Ulil Albab', '69', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6894, '3140609', 'Tabrani', '70', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6895, '3141004', 'Dian Degusty', '41', '004', '14', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6896, '3141010', 'Sofyan Yusuf', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6897, '3141012', 'Chairunnisa', '41', '004', '14', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6898, '3150106', 'Sry Andayani Br. Sembiring', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6899, '3150210', 'Lilis Karlina', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6900, '3150612', 'Meiti Sibarani', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6901, '3150703', 'Ade Triasandriani', '45', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6902, '3150901', 'Dedi Susanto', '56', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6903, '3150908', 'Hotnita H. Sijabat', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6904, '3160103', 'Meliza', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6905, '3160115', 'Yuliana Safitri', '38', '001', '10', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6906, '3160301', 'Desiani', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6907, '3160902', 'M. Ery Edy S', '56', '003', '08', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6908, '3161209', 'Mia Karuniadini', '45', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6909, '3170533', 'Imelda Purba', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6910, '3180101', 'Angelina Puspa Sari', '91', '004', '14', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6911, '3190302', 'Siti Pasaribu', '45', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6912, '3190501', 'Apriwansyah', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6913, '3190506', 'Febri Nur Fatimah', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6914, '3190602', 'Nurhayati', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6915, '3190604', 'Purnama Sari Siregar', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6916, '3190607', 'Yepi Tamala Sihombing', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6917, '3190608', 'Rizki Kurniawan', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6918, '3190609', 'Robet Panjaitan', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6919, '3190701', 'Much Bayu Aji', '70', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6920, '3220501', 'Taufik Eko Saputro', '62', '002', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6921, '3221004', 'Ekananda Gusra Cahya', '87', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6922, '3221005', 'Heni Safitri', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6923, '3221006', 'Mehwani Sri Kartika Silitonga', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6924, '3221007', 'Andika Syahputra Agustias', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6925, '3221008', 'Josua Pasaribu', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6926, '3221009', 'Angelina Riyani Agustin', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6927, '3221010', 'Stevan Yoland Henry', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6928, '3221011', 'Muharni', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6929, '3221021', 'Azri Amirudin', '69', '001', '35', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6930, '3221022', 'Rizki Astria', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6931, '3221023', 'Siska Wahyuni Putri', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6932, '3221024', 'Zahda Piki Arjuka', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6933, '3221025', 'Ali Napia', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6934, '3221027', 'Aida Kurnia Putri', '56', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6935, '3221028', 'Sarina Wati', '56', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6936, '3221029', 'Fitriah', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6937, '3221101', 'Faizal Yusuf', '39', '001', '02', 'M', '', '004', NULL, NULL, '2025-08-19 07:17:48'),
(6938, '3230101', 'Gosen Manalu', '69', '005', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6939, '3230102', 'Abdul Aziz', '69', '005', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6940, '3230103', 'Kasinia Zendato', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6941, '3230104', 'Misanta Uli Asrina Br Sembiring', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6942, '3230201', 'Siti Hartina Marbun', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6943, '3230202', 'Hafriza Hasanah', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6944, '3230203', 'Paridawati Gultom', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6945, '3230205', 'Valentina Hutabarat', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6946, '3230206', 'Tirmasari', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6947, '3230207', 'Anatri Sadiah', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6948, '3230211', 'Jagad Oktafrianto', '56', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6949, '3230212', 'Muhammad Nur Sidiq', '56', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6950, '3230213', 'Hotmasi Sihombing', '56', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6951, '3230214', 'Meliwati Sagala', '56', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6952, '3230301', 'Muhamad Ferry Irawan', '69', '005', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6953, '3230302', 'Rizki Ridwan', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6954, '3230304', 'Yoan Nita Sari', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6955, '3230305', 'Novia Nuzulyani', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6956, '3230309', 'Khesia Santa Maria Sianipar', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6957, '3230310', 'Juliana Perangin Angin', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6958, '3230312', 'Yovanka Nikita', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6959, '3230313', 'Mimi Arianti', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6960, '3230316', 'Latifa Ramandes', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6961, '3230317', 'Primta Richardo', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6962, '3230402', 'Windi Saputri', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6963, '3230406', 'Trinanda Wahyudi', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6964, '3230407', 'Eka Safitri', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6965, '3230409', 'Muhammad Rayan Andhika', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6966, '3230411', 'Fernanda Adriano Sihombing', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6967, '3230415', 'Rophan Afdhillah', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6968, '3230416', 'Rendi Yanis', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6969, '3230601', 'Yeriko Pratama Riandro Sianturi', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6970, '3230602', 'Agnes Tambunan', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6971, '3230603', 'Putri Sofia Ramadani', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6972, '3230604', 'Satria Rizqi Ramadhan', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6973, '3230801', 'Salsabila Desti Darma', '70', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6974, '3230802', 'Takako Takimoto', '91', '010', '05', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6975, '3231002', 'Hasti Janu Aulia', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6976, '3231003', 'Yeneti', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6977, '3231004', 'Putri Aida Lestari', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6978, '3231005', 'Bela Dwi Susanti', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6979, '3231101', 'Khairul Walid', '69', '005', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6980, '3231102', 'Boby Toba Siregar', '69', '005', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6981, '3240101', 'Daniel Demak Berkat Parulian Panjaitan', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6982, '3240102', 'Syamza Diaf', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6983, '3240103', 'Zesica Pintaloka Br Ginting', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6984, '3240105', 'Dita Agusrina', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6985, '3240107', 'Rindi Dwi Permata', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6986, '3240201', 'Rani Rusmawati', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6987, '3240202', 'Yasi Marwanti', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6988, '3240203', 'Gracia Romasta Sitompul', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6989, '3240204', 'Desi Muliana Br Sidabutar', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6990, '3240205', 'Desi Aryani Sihombing', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6991, '3240208', 'Kumaratih Kumaratungga Dewi', '70', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(6992, '3240501', 'Lasya Eriani', '56', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6993, '3240502', 'Hendri Purnawidijaya', '56', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6994, '3240505', 'Viola Sagita', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6995, '3240508', 'Daffa Amalia Damayanti', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6996, '3240602', 'Sri Ayunita', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6997, '3240603', 'Rozalina', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6998, '3240604', 'Esti Ratnasari', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(6999, '3240605', 'Enny Frisdawati', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7000, '3240606', 'Mega Oktapia', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7001, '3240607', 'Chris Chenery', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7002, '3240701', 'Maulina Sentari', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7003, '3240702', 'Nayla Azzahra Syahputri', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7004, '3240703', 'Ismail Sembiring', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7005, '3240704', 'Dhivo Ananda', '69', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7006, '3240705', 'Herlambang Gustian', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7007, '3240706', 'Hafuza Bazla', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7008, '3240707', 'Ira Christiani', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7009, '3240708', 'Nia Dewi Kartika', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7010, '3240709', 'Novi Puspita Rahmawati', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7011, '3240710', 'Hanifah Fitri Kusumawardani', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7012, '3240711', 'Puput Sharifatun Nisaq', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7013, '3240712', 'Suriani Halawa', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7014, '3240713', 'Rena Ade Natasya', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7015, '3240714', 'Enita Roza', '73', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7016, '3240715', 'Jascia Adelia', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7017, '3240801', 'Nada Rahmi Zamra', '56', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7018, '3240802', 'Yoky Adi Saputro', '67', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7019, '3240901', 'Maissy Ar Maghfiroh', '71', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7020, '3240902', 'Wahyudi', '71', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7021, '3240903', 'Faris Rizki Ramadhani', '70', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7022, '3241001', 'Zul Ibrahim', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7023, '3241002', 'Susi Rania Manurung', '69', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7024, '3241003', 'Abdee Mekha Permana', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7025, '9703002', 'Elva Susanti', '41', '003', '08', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7026, '9706019', 'I Putu Pangkat', '86', '009', '08', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7027, '9706034', 'Sunarmadi', '71', '004', '04', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7028, '9709079', 'Kasiman', '71', '008', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7029, '9709083', 'Nurhayati', '73', '001', '29', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7030, '9709097', 'Sochifudin', '69', '001', '29', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7031, '9709107', 'I Putu Yoga Sugama', '38', '003', '05', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7032, '9802136', 'James Winter P', '38', '008', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7033, '9802137', 'Jon Poni Irianto', '73', '008', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7034, '9802141', 'Mardiana', '70', '004', '04', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7035, '9802171', 'Zuli Adam', '38', '008', '29', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(7036, '9802176', 'Sapari Sri Indarti', '54', '002', '01', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7037, '9807223', 'Suparman', '71', '008', '15', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7038, '9807228', 'Yusi Andri', '50', '002', '01', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7039, '9812245', 'Slamet Supriyadi', '39', '001', '11', 'M', '', '002', NULL, NULL, '2025-08-19 07:17:48'),
(7040, '9902263', 'Normalinda Pakpahan', '56', '001', '29', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7041, '9902269', 'Mirshad', '70', '004', '04', 'M', '', '049', NULL, NULL, '2025-08-19 07:17:48'),
(7042, '9902285', 'M. Nurhadi', '73', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7043, '9902302', 'Zaenal', '56', '005', '15', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7044, '9903335', 'Nugrahanto Himawan', '67', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7045, '9903373', 'Agus Suyamto', '73', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7046, '9903376', 'Didik Adi Prasetyo', '69', '008', '29', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7047, '9903386', 'Kamidi', '67', '001', '10', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7048, '9903424', 'Rosmawati', '56', '005', '03', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7049, '9903430', 'Samsuddin', '38', '001', '10', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7050, '9904440', 'Andi Catur Prasetyo', '67', '005', '29', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7051, '9904449', 'Wardi', '50', '005', '29', 'M', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7052, '9904477', 'Rini Wulandari', '73', '005', '15', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7053, '9905535', 'Jimper Sirait', '56', '001', '06', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7054, '9905575', 'Andiski Novalyna Sinambela', '56', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7055, '9905584', 'Duma Sari Pane', '70', '005', '15', 'F', '', '003', NULL, NULL, '2025-08-19 07:17:48'),
(7056, '9905595', 'Erma Yeni', '87', '001', '35', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7057, '9905654', 'Tutioma Ambarita', '87', '001', '06', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7058, '9907686', 'Suyanto', '39', '001', '06', 'M', '', '004', NULL, NULL, '2025-08-19 07:17:48'),
(7059, '9910704', 'Basuki Rachmat', '87', '001', '29', 'M', '', '008', NULL, NULL, '2025-08-19 07:17:48'),
(7060, '9911765', 'Sudiyanti', '87', '001', '10', 'F', '', '008', NULL, NULL, '2025-08-19 07:17:48');

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
('1', 'TP MFG Gp 1'),
('10', 'ML Opr.Gr.1'),
('11', 'ML Opr.Gr.2'),
('12', 'ML Opr.Gr.03'),
('13', 'TP MFG AKM 1'),
('14', 'TP MFG AKM 2'),
('15', 'TP MFG AKM 3'),
('18', 'ASSY MFG Gp 1'),
('19', 'ASSY MFG Gp 2'),
('2', 'Security A'),
('20', 'ASSY MFG Gp 3'),
('21', 'ASSY MFG Gp 4'),
('3', 'Normal'),
('4', 'Op GA Grp.1'),
('45', 'HE GP 01'),
('46', 'HE GP 02'),
('47', 'HE GP 03'),
('48', 'HE Staff Gr.A'),
('49', 'Production Staff'),
('5', 'opr GA Grp.2'),
('51', 'QC Opr. Gr.1'),
('52', 'QC Opr. Gr.2'),
('53', 'QC Opr.Gr.3'),
('6', 'TP MFG Gp 2'),
('7', 'Driver GA'),
('8', 'group operator'),
('9', 'TP MFG Gp 3');

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
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'submitted',
  `remaining_cash` decimal(14,2) DEFAULT '0.00',
  `settlement_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settlements`
--

INSERT INTO `settlements` (`id`, `trip_id`, `total_realisasi`, `bukti_file`, `created_at`, `variance`, `status`, `remaining_cash`, `settlement_date`) VALUES
(7, 4, '1450000.00', '', '2025-08-20 08:54:33', '-1050000.00', 'submitted', '0.00', NULL),
(8, 2, '25000.00', '', '2025-08-20 09:19:27', '-75000.00', 'submitted', '0.00', NULL);

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

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `employee_id`, `employee_source`, `emp_no`, `emp_name`, `emp_department`, `emp_description`, `tujuan`, `destination_district`, `destination_company`, `tanggal`, `period_from`, `period_to`, `biaya_estimasi`, `currency_entered`, `temp_payment_idr`, `temp_payment_sgn`, `temp_payment_yen`, `keperluan`, `purpose`, `data_for_collection`, `status`, `created_at`, `approved_at`) VALUES
(1, 6806, 'local', '2206106', 'Cindy Cahyadi', '54', NULL, 'Tanjung Uban', 'Tanjung Uban', 'rq', '2025-08-19', '2025-08-19', '2025-08-21', '1000000.00', 'IDR', '1000000.00', '85.00', '9500.00', 'fsgds', 'fsgds', '', 'approved', '2025-08-19 15:00:53', '2025-08-19 15:20:31'),
(2, 6819, 'local', '2603382', 'M. Hidayah', '39', NULL, 'Tanjung Uban', 'Tanjung Uban', 'Bank', '2025-08-19', '2025-08-19', '2025-08-20', '100000.00', 'IDR', '100000.00', '8.50', '950.00', 'gatau', 'gatau', 'nothing\r\n', 'approved', '2025-08-19 21:49:44', '2025-08-19 21:50:17'),
(3, 6819, 'local', '2603382', 'M. Hidayah', '39', NULL, 'Tanjung Uban', 'Tanjung Uban', 'Bank', '2025-08-19', '2025-08-19', '2025-08-21', '200000.00', 'IDR', '200000.00', '17.00', '1900.00', 'gaty', 'gaty', 'gada', 'rejected', '2025-08-19 21:52:55', '2025-08-19 21:53:39'),
(4, 6806, 'local', '2206106', 'Cindy Cahyadi', '54', NULL, 'Tanjung Uban', 'Tanjung Uban', 'Bank', '2025-08-18', '2025-08-18', '2025-08-19', '2500000.00', 'IDR', '2500000.00', '212.50', '23750.00', '123', '123', 'test', 'approved', '2025-08-20 07:25:28', '2025-08-20 07:25:46'),
(5, 6920, 'local', '3220501', 'Taufik Eko Saputro', '62', NULL, 'batam', 'batam', 'duta', '2025-08-22', '2025-08-22', '2025-08-23', '600000.00', 'IDR', '600000.00', '51.00', '5700.00', 'tes', 'tes', '', 'approved', '2025-08-20 08:19:26', '2025-08-20 08:20:06'),
(6, 7025, 'local', '9703002', 'Elva Susanti', '41', NULL, 'Tanjung Pinang', 'Tanjung Pinang', 'Manpower', '2025-08-22', '2025-08-22', '2025-08-22', '1000000.00', 'IDR', '1000000.00', '85.00', '9500.00', 'meeting', 'meeting', '', 'approved', '2025-08-20 13:35:40', '2025-08-20 13:36:20');

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

--
-- Dumping data for table `trip_approvals`
--

INSERT INTO `trip_approvals` (`id`, `trip_id`, `circular_id`, `approver_name`, `approval_level`, `status`, `notes`, `created_at`, `approved_at`) VALUES
(1, 1, NULL, 'local_admin', 1, 'approved', NULL, '2025-08-19 15:20:31', NULL),
(2, 2, NULL, 'local_admin', 1, 'approved', NULL, '2025-08-19 21:50:17', NULL),
(3, 3, NULL, 'local_admin', 1, 'rejected', NULL, '2025-08-19 21:53:39', NULL),
(4, 4, NULL, 'local_admin', 1, 'approved', NULL, '2025-08-20 07:25:46', NULL),
(5, 5, NULL, 'local_admin', 1, 'approved', NULL, '2025-08-20 08:20:06', NULL),
(6, 6, NULL, 'local_admin', 1, 'approved', NULL, '2025-08-20 13:36:20', NULL);

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
(1, '2001785', '$2y$10$Q2JvjrfAL8Ah4qfLutNbge2SDh3k5JhQYXoT1ZcoEGFuuy1PlPKHi', 'employee', 6512, 1, '2025-08-19 07:17:48'),
(2, '2001786', '$2y$10$7N7Fvz7IaPHCpXUnrWAPN.lCG9HXvSVfhuvZBeQUvZTKtFHpNjhi2', 'employee', 6796, 1, '2025-08-19 07:17:48'),
(3, '2004790', '$2y$10$WApxaNjAihE.YbKmAM3hFefCpJa7Ff6Ez4DoBqr8pV9qDgeY85zo.', 'employee', 6797, 1, '2025-08-19 07:17:48'),
(4, '2005814', '$2y$10$gjzF0dj5KQH1LlwaDYm20.EoJujHALx3P454hs3nlt80pSaKWSeBq', 'employee', 6798, 1, '2025-08-19 07:17:48'),
(5, '2006833', '$2y$10$tqOAbvhAVgWSoTWoIpapOuX/FiAwmtpNbYzZFyap3AflWVvWx1O56', 'employee', 6799, 1, '2025-08-19 07:17:48'),
(6, '2006839', '$2y$10$QHT.4sM9kaelMKrtnJklPO53LTPgPDMCeU85WDkpJuoqsVgcScNEK', 'employee', 6800, 1, '2025-08-19 07:17:48'),
(7, '2009901', '$2y$10$MD6V6wNPfa5ZsgDuF29qHuuv5xtXH3x3DKFkwicE0eqg4.WGdAGLi', 'employee', 6801, 1, '2025-08-19 07:17:48'),
(8, '2009923', '$2y$10$YJaOidj0fGWxdh5ETBt.R.xbXaFh.qugFXqs3OXFqvjHwB5h4m7Ky', 'employee', 6802, 1, '2025-08-19 07:17:48'),
(9, '2010973', '$2y$10$nWo7MKsZkAMWknmrT3adoOZ6ddU82FxIEiHPvE8T4fy2j3W1DtxX6', 'employee', 6803, 1, '2025-08-19 07:17:48'),
(10, '2011015', '$2y$10$O8ArHGMUk/0saYp1IRjhfORPajEhS/WdEMEU.6LAvsHx.8Gwf1FKi', 'employee', 6804, 1, '2025-08-19 07:17:48'),
(11, '2102069', '$2y$10$qy/Uy3HWlIupLiyKegzql.Lsuhe24Yw8UPGsQmhYtMW3tpjusge/6', 'employee', 6805, 1, '2025-08-19 07:17:48'),
(12, '2206106', '$2y$10$UfIeD0hzcBfdlkmn0mhYsujYGTu/oUxNOxUSEvFJhN/elnmdCnG0a', 'employee', 6806, 1, '2025-08-19 07:17:48'),
(13, '2206162', '$2y$10$JQZLpk6tVoJRnBpfPZquyOC.vjeL8s2Q2ksSnvmpD6cInJe01cqpm', 'employee', 6807, 1, '2025-08-19 07:17:48'),
(14, '2206164', '$2y$10$KPNihx/twy.h.Wz21TRCFOA1F8vL20ona98X1fwfgJUwulKtAYZLi', 'employee', 6808, 1, '2025-08-19 07:17:48'),
(15, '2206172', '$2y$10$8dVM6ZQy5gIovxdPMXwBEu2zJLWifYkcXoAnh5c8TYyVo7ysmmwhy', 'employee', 6809, 1, '2025-08-19 07:17:48'),
(16, '2207180', '$2y$10$NhXn5VJw2WpVTQqH6DR1AuD2x0USLLwzZ3lxGL78m47daHhOoHbU2', 'employee', 6810, 1, '2025-08-19 07:17:48'),
(17, '2207197', '$2y$10$fzlHNHDeOJKBphvyLWzVr.Iyefvz6AfXvCaP8dnK6Ou7Gvm237EKC', 'employee', 6811, 1, '2025-08-19 07:17:48'),
(18, '2209216', '$2y$10$hkOB3L5nTG/xqj8Tx1WnF.tiJCtPRj9fOjbfzSF5DRoMUJYMKwBr.', 'employee', 6812, 1, '2025-08-19 07:17:48'),
(19, '2209244', '$2y$10$3wtbRlMGiKfNItvf0ZY84O7zxNPWFRDa/Jnn4yqkECXj.eVI42GnW', 'employee', 6813, 1, '2025-08-19 07:17:48'),
(20, '2309314', '$2y$10$hUq2RC/x6HKOn0nj1cRLx.t3wiZ6wa02Q3jJnnqjHEPTKBCz30sv.', 'employee', 6814, 1, '2025-08-19 07:17:48'),
(21, '2404389', '$2y$10$Cegrecu6JWLxMZ3eY20Fe.N9Jg5BYcngF5DdoFJe9Eo2mELDDgDi.', 'employee', 6815, 1, '2025-08-19 07:17:48'),
(22, '2410268', '$2y$10$S3VH0IH.eeC7UB1UsTCtLelpH.2G9h7zGmgXZKCdkWnBALrEQreHG', 'employee', 6816, 1, '2025-08-19 07:17:48'),
(23, '2502310', '$2y$10$Q23UYYrlVT9n/bX8iQJmi.Aa4oL2gGQMzVTDFeZLI7BIHMjHPOmQC', 'employee', 6817, 1, '2025-08-19 07:17:48'),
(24, '2502314', '$2y$10$5G9Hft.BkBDgAtY1ye2GEOurU3esAl6ECgCAqRzSQDLnueMc3OKRu', 'employee', 6818, 1, '2025-08-19 07:17:48'),
(25, '2603382', '$2y$10$l42RJfeBEKDj1sDIMlvP8O6OzN71up/dmwIGrGN.V0JLOltpaxlFK', 'employee', 6819, 1, '2025-08-19 07:17:48'),
(26, '2609462', '$2y$10$MOwP9M1r9ZFzJqzpd0AXmO.mV9fAVvIeBEv.32HoCqShIAIGB9w5W', 'employee', 6820, 1, '2025-08-19 07:17:48'),
(27, '2809772', '$2y$10$n01dWFQxoRGjlFeVPcmdHujZjR8OCFA5WUSXV/J/AiI.OsSl1Da0K', 'employee', 6821, 1, '2025-08-19 07:17:48'),
(28, '2809786', '$2y$10$4Bqb15kFurfs/Tqd5Uura.oQyYyl01WM2RfO.KGxXUJNTZO1yPXO.', 'employee', 6822, 1, '2025-08-19 07:17:48'),
(29, '2909867', '$2y$10$TmGxlMkARmsoYzADkNMy..MAcBHOYGvVIHjKTQXjpcS/fSrsljUC6', 'employee', 6823, 1, '2025-08-19 07:17:48'),
(30, '3100101', '$2y$10$eCy8Yz3zSEM1u8O.h5cGqO8okbQwuwGLUO7Ex8kVKyq3a1YpaXQgy', 'employee', 6824, 1, '2025-08-19 07:17:48'),
(31, '3100562', '$2y$10$.rYeJ3QzshWDX56O1o6.qeb7aCfVS.k10gMBAvT5rAirfyzLm5Nkm', 'employee', 6825, 1, '2025-08-19 07:17:48'),
(32, '3110517', '$2y$10$7bOonteh..rVg.9mEvoi0.FhmWbyKDPLeydEzmd49mLM7vu4Rgg0.', 'employee', 6826, 1, '2025-08-19 07:17:48'),
(33, '31107140', '$2y$10$G5zXWCMfKWMzIq6lbhW4A.IpNnor0BKTKoIICO0i3y8ACr6GTcvX6', 'employee', 6827, 1, '2025-08-19 07:17:48'),
(34, '31107143', '$2y$10$KXLQb5h1LpvaZLYqwlNMze8A5eCN7Yn8jAoDJHBGMa.zPxyKHB4Cy', 'employee', 6828, 1, '2025-08-19 07:17:48'),
(35, '3110851', '$2y$10$qYg3RnuULpkk5TwbqTjSI.fTWj2XMyZolcfJ7I.HVTALpb9EEBfqS', 'employee', 6829, 1, '2025-08-19 07:17:48'),
(36, '3110852', '$2y$10$TKFU7eTKHWxyn/oooGViu.hRXhocarqgUbuj9R63ZsvMhv3d8igO2', 'employee', 6830, 1, '2025-08-19 07:17:48'),
(37, '3110903', '$2y$10$BgnJCziJdz4NdpQmkTclceN/szp1IiUACOH6I/HddrQogGuMvPNTa', 'employee', 6831, 1, '2025-08-19 07:17:48'),
(38, '3111033', '$2y$10$g4ujKQ862q2mX0HTIz3gl.A3j4B9TjHw1aYGeW..NjvAh/SV4TfDm', 'employee', 6832, 1, '2025-08-19 07:17:48'),
(39, '3111053', '$2y$10$8y/PWAlEVteQhUJWpeDjO.WznRKHwD5hCWPcwaT9okBsexXA90kda', 'employee', 6833, 1, '2025-08-19 07:17:48'),
(40, '3111138', '$2y$10$zPYpwbwXEOuD3mqL5REzA.cfp4P2pgcrWIxjZK.j8iXgLq0T.e29G', 'employee', 6834, 1, '2025-08-19 07:17:48'),
(41, '3120525', '$2y$10$BK7w.kB8S6Il8spwFR0/GOVa2/TVt1tEwNb9L8sZECe5RPjBKNXBC', 'employee', 6835, 1, '2025-08-19 07:17:48'),
(42, '3120534', '$2y$10$OS4CLoM8etk4F41/2oL5y.KcxYddWk4KMNEExGGb088qwH.c/p0sG', 'employee', 6836, 1, '2025-08-19 07:17:48'),
(43, '3120705', '$2y$10$vstOZ6j2BqgEXRpv1KG7FuUtTUNcXgTv/QvREgeJimq4Ag5xKp62q', 'employee', 6837, 1, '2025-08-19 07:17:48'),
(44, '3120806', '$2y$10$n4czIRVVzLzKbTm0SCdoLOuin6pLnjmBNzDcuuLTk4Y91f/gbHP8m', 'employee', 6838, 1, '2025-08-19 07:17:48'),
(45, '3120905', '$2y$10$EZmCeyChhqq8BdiexJW9pe/v1QSGZapkbszReCexpLbD8CySFmwYy', 'employee', 6839, 1, '2025-08-19 07:17:48'),
(46, '3121042', '$2y$10$6w8ZWI0OaC5kAFhQ.NIhtea/fDv91pkGsnUIiA5g8x3WZ2R5QbZZ.', 'employee', 6840, 1, '2025-08-19 07:17:48'),
(47, '3121201', '$2y$10$.INeHa82H1Evjq1L8bQpoO9nK7FuSeiD3a9DoH3HXTlVcBKstwq3m', 'employee', 6841, 1, '2025-08-19 07:17:48'),
(48, '3121207', '$2y$10$t1GWJHeZdD2RlYsTi2EJ9.ua4xDaHZqHpYbH49hdNhVSrdDaza5lC', 'employee', 6842, 1, '2025-08-19 07:17:48'),
(49, '3121215', '$2y$10$A4kkjZJheHekUjrTGFlii.c1SzosBuNV5vOiW4lugexJcWdEYUl4m', 'employee', 6843, 1, '2025-08-19 07:17:48'),
(50, '3130101', '$2y$10$k6cI4PO6ykjPRa0Z1dAgHOJoGwggwcNNccsgpRfPWc.Y.q8yy1Pzi', 'employee', 6844, 1, '2025-08-19 07:17:48'),
(51, '3130104', '$2y$10$3zPu4RVYcr9usuTLuYhCZ.3tE0qZlXKhmJu5pkvbl2.Un2TENS6by', 'employee', 6845, 1, '2025-08-19 07:17:48'),
(52, '3130115', '$2y$10$4huuKPYygDaASHOXfaZzpeZklUCIHFrxm/tKKKQnPX7uaIGK0njv2', 'employee', 6846, 1, '2025-08-19 07:17:48'),
(53, '3130125', '$2y$10$7YLVypehbvlkmVlQQO/fo.QwKt.lCNg6FJm4w8SeEjBrEYQ0VxCxO', 'employee', 6847, 1, '2025-08-19 07:17:48'),
(54, '3130140', '$2y$10$QLN71YSvHMvJwKTVIYdjcOjVT5UH4IEZocLx68WUvksNtA.WIIVBy', 'employee', 6848, 1, '2025-08-19 07:17:48'),
(55, '3130149', '$2y$10$lrT/WoAzpI7wS6vYlg4aieaJjeq/Rhtsm0Fel3UzNUiTT6J6KTq/.', 'employee', 6849, 1, '2025-08-19 07:17:48'),
(56, '3130201', '$2y$10$5qGqrPZF1EfuLef6H4kSbuNqFCFMRDNxB4oManCQ.pku1kNVG5GaK', 'employee', 6850, 1, '2025-08-19 07:17:48'),
(57, '3130309', '$2y$10$lDwv9wt2ujn9YLPmHgqU8uaobg8RqinyYFIN8jP0yTIBkvN5/dxsW', 'employee', 6851, 1, '2025-08-19 07:17:48'),
(58, '3130401', '$2y$10$uGL8lcJEzOhhdwefqgM.ReFBLRzSrXu99d.MamUo2JSLs3vtwwP4G', 'employee', 6852, 1, '2025-08-19 07:17:48'),
(59, '3130410', '$2y$10$w981Ofbr8FIxwO9S4.fId.B03WDF2k1xmramRuD2xGxGghpHjRHBq', 'employee', 6853, 1, '2025-08-19 07:17:48'),
(60, '3130505', '$2y$10$TiHd3ozyTrZmCKl8Z67Xde2YIiJv8RPJRFbo/PdT19rhmrXbrX24.', 'employee', 6854, 1, '2025-08-19 07:17:48'),
(61, '3130515', '$2y$10$mCpH8x5//kKomXnb.8b5qOey1OIon9bmFa2lhpsFXEupaYu63CClS', 'employee', 6855, 1, '2025-08-19 07:17:48'),
(62, '3130523', '$2y$10$DwogeaXurrLE5oEIdPG4bOebz3uG2Onab6OSuAQ6V/k0qPZiOqh4.', 'employee', 6856, 1, '2025-08-19 07:17:48'),
(63, '3130705', '$2y$10$a/2OFXMxNPjS4yyobDlpiuDzvtpa.jzfa9m.cAnR6pbS/dikeEvby', 'employee', 6857, 1, '2025-08-19 07:17:48'),
(64, '3130708', '$2y$10$.OIPB.hwLY68NJygT2SnMeqbX45FlzyOOqYwnISl0jGBFtrYQZtWa', 'employee', 6858, 1, '2025-08-19 07:17:48'),
(65, '3130713', '$2y$10$GPCYYTFjCBGzFYqXwNzb8eApLRupH50keGPgFp9tmUOuPXlUeRV7O', 'employee', 6859, 1, '2025-08-19 07:17:48'),
(66, '3130718', '$2y$10$A1zHkVHROmfA.88eCAyaLe9ho13KqyGb7YNdkkCZqOEzKDgCxVmIS', 'employee', 6860, 1, '2025-08-19 07:17:48'),
(67, '3130819', '$2y$10$LnAOhZTBFbG035MRAtihre3Fd4W0gpfdYXcgarafPI2PZO8VOhL/m', 'employee', 6861, 1, '2025-08-19 07:17:48'),
(68, '3130829', '$2y$10$3SXoTvkP8FutJcFdkZZhTOq1xNq3p/IiSHo0QtebRFiN6cAHe1CBK', 'employee', 6862, 1, '2025-08-19 07:17:48'),
(69, '3130848', '$2y$10$DE.NyoAOxYZHcyQ1fk8d6.DZHabSUbZcS8DuuN9Y2fDUxQq4ehJ76', 'employee', 6863, 1, '2025-08-19 07:17:48'),
(70, '3130871', '$2y$10$cZRaEmgG9aTcabUDneat0uSaQVM1nQTZAb3NPT9hlJadmsg6V9CPO', 'employee', 6864, 1, '2025-08-19 07:17:48'),
(71, '3130906', '$2y$10$HG00Yibk/MVHafBEVQql7ObKr0gQL5aLcvQXPI1XJEP4iiJYpicRK', 'employee', 6865, 1, '2025-08-19 07:17:48'),
(72, '3130916', '$2y$10$YQrujynve4bgZPFXkBwJ8.rCZPj2P1SPfSjmPnju96.VH6oUOAqha', 'employee', 6866, 1, '2025-08-19 07:17:48'),
(73, '3130927', '$2y$10$/lPPzpv1gUPR1gVQXrvr/OLL1YnBA7XRy.AISJ5LUzs5cM4eg/ZTa', 'employee', 6867, 1, '2025-08-19 07:17:48'),
(74, '3130945', '$2y$10$.KX3SIY3SwGN506.Br5Zhu9B7a0iLbrliqUpBocMUvl9WuimKi03a', 'employee', 6868, 1, '2025-08-19 07:17:48'),
(75, '3130971', '$2y$10$zdhDjL2riLzhKZChvTFQFeY7INh9SHZaiH7XUEYE95NreKay378lW', 'employee', 6869, 1, '2025-08-19 07:17:48'),
(76, '3130987', '$2y$10$z3HipiONIAD8G0MUrSD.r.LTvRC0neiHQ5IRPHGweYqDu4yOTp/Wu', 'employee', 6870, 1, '2025-08-19 07:17:48'),
(77, '3131006', '$2y$10$/WPHo1tRJDgvMC8AuO7g8ezf/wkYVidnDivKgrTIj1BJH.L5kqU2O', 'employee', 6871, 1, '2025-08-19 07:17:48'),
(78, '3131025', '$2y$10$PfYnr7pvR6K89qxbFSdHKOgZGlYbo5k.be2I5rXeiJ2HOxJLnXpxC', 'employee', 6872, 1, '2025-08-19 07:17:48'),
(79, '3131036', '$2y$10$HNUKNUW2pbIRf1Qd6II.1OEm20U0UirelImxFxbl/H1yARA3e6vfO', 'employee', 6873, 1, '2025-08-19 07:17:48'),
(80, '3131042', '$2y$10$ngAIJlWKq/wQOpy4DgU7huix9wszHv/zxMJJS9AL9xxmAaMu68PEi', 'employee', 6874, 1, '2025-08-19 07:17:48'),
(81, '3131045', '$2y$10$SGW//Evx.b00SZS3c/G4JuQpBABacdNpcHxY7O62Zk//.XOffpEWm', 'employee', 6875, 1, '2025-08-19 07:17:48'),
(82, '3131051', '$2y$10$ziA4IDZyYvPevL2YwFDrIeNeJpO021F1wPGdoBhP8EO2wUefYzvFK', 'employee', 6876, 1, '2025-08-19 07:17:48'),
(83, '3131101', '$2y$10$gZmylMEawDTHEjpfXeAr.eK3yaplh3uHsshRIwyNOSGHUlwLOQs3S', 'employee', 6877, 1, '2025-08-19 07:17:48'),
(84, '3131135', '$2y$10$ne3StYW5cQp3qmqIMcAhjezHmBFwzM1tKcz.18vMArKVbsbYUx4wK', 'employee', 6878, 1, '2025-08-19 07:17:48'),
(85, '3131218', '$2y$10$mlWPJ1RJjZwZ5X5bKotEc.rtCJhfvfT6Kz/j3mb9VWyrd0S19DrVq', 'employee', 6879, 1, '2025-08-19 07:17:48'),
(86, '3131222', '$2y$10$5jVCyCrjBNf4HY4UxQX9d.fA8IaW6x1S9Od1qumv2qvo8ynPKdzb6', 'employee', 6880, 1, '2025-08-19 07:17:48'),
(87, '3131225', '$2y$10$HN7BDdz6Uo1hBJ9bA9iHuOR58uQES40QEtoKQWgMqq9zJpDlADpPC', 'employee', 6881, 1, '2025-08-19 07:17:48'),
(88, '3131238', '$2y$10$4W713cVpdWz.WzpK0MfrseIcbDZ0oq.EhbQJiPMzOUex0rQFEI9Fa', 'employee', 6882, 1, '2025-08-19 07:17:48'),
(89, '3140104', '$2y$10$xfuNF2f9EEL8hh.Yq/oXF.DFLCUVp0/XADDg8ONCyD.qQ2ofLuXyC', 'employee', 6883, 1, '2025-08-19 07:17:48'),
(90, '3140108', '$2y$10$Y55TEw7Xt/Y2OfSQu9ct3uadcb0jtRcC.fOBmmBmMQRTvXg3RCZLi', 'employee', 6884, 1, '2025-08-19 07:17:48'),
(91, '3140113', '$2y$10$IvvCO7YBH7e7w.jZjiDi7ekQuRnAkkdWpw1q80B3niE72drNMyIzO', 'employee', 6885, 1, '2025-08-19 07:17:48'),
(92, '3140127', '$2y$10$DbQD.lsz.pCM.fMTuNSjp.ybAirzWqwDOKGZV5I5L6mpUeQp9e9mq', 'employee', 6886, 1, '2025-08-19 07:17:48'),
(93, '3140202', '$2y$10$VBVn0WskQgpcv5AFUEMUPOPhjJDdlWWBXByWG2R3kMInwAIruTPtu', 'employee', 6887, 1, '2025-08-19 07:17:48'),
(94, '3140208', '$2y$10$Oh7oKQJWGiTjsMOGiFKyFu2hnJ7KCMZoDN244WXg3CE/6aB0twklq', 'employee', 6888, 1, '2025-08-19 07:17:48'),
(95, '3140212', '$2y$10$MOA4mMF9LutOl1RTOdT1p.fO173R2MlkJtRFgbmr/WEsK4hfzdjh2', 'employee', 6889, 1, '2025-08-19 07:17:48'),
(96, '3140215', '$2y$10$Y5sWis6T4dOlov.mbVzRdORcARiglnl1ttf4E.i5q52NRSSjylvwO', 'employee', 6890, 1, '2025-08-19 07:17:48'),
(97, '3140304', '$2y$10$Rr9JT.J2vqFjxg6q4walC.g.qxvF4ZOLErAsGT7NLzZI5kBjDXUvm', 'employee', 6891, 1, '2025-08-19 07:17:48'),
(98, '3140508', '$2y$10$QHa.sZll5keNOFY61klnVeL7saU1VNArOk4CQ.XYeRv7D4Lo.q1ga', 'employee', 6892, 1, '2025-08-19 07:17:48'),
(99, '3140526', '$2y$10$W.BnIQ5qcc6bZnT8qXGCFueSB0rg9jUBUtqEr9gIilaB.0b/Nsyhm', 'employee', 6893, 1, '2025-08-19 07:17:48'),
(100, '3140609', '$2y$10$iVCnw4gJkfCalCaAU31XrO0C2I4g4XraN/CmdnFrrInIvKxqV2AzK', 'employee', 6894, 1, '2025-08-19 07:17:48'),
(101, '3141004', '$2y$10$iXGPGepCFCynAbZAD6EiC.80Ip.i5.KOO2LzylHNr6R/XlcJBfBLm', 'employee', 6895, 1, '2025-08-19 07:17:48'),
(102, '3141010', '$2y$10$rYDtZYctZW2tHVXMtGxldeBjKBknQyv.WQvG69ZXPZBW/HQxG3BZ.', 'employee', 6896, 1, '2025-08-19 07:17:48'),
(103, '3141012', '$2y$10$CQLDmF/KZEclxwX7fpvaguIdif1kSjZeAk.9Fx9iX4SbcNIYHF8sS', 'employee', 6897, 1, '2025-08-19 07:17:48'),
(104, '3150106', '$2y$10$RfzCJUhR.Z4LmvuKg6IdH.BNK9ZeAd0oo2ItkLv6Vruvs99Zi0/cq', 'employee', 6898, 1, '2025-08-19 07:17:48'),
(105, '3150210', '$2y$10$juqIYRvup72Pyufqc6GT.uo8PCL5AIjKT767vdRXsdBVCcn9Ds/Ey', 'employee', 6899, 1, '2025-08-19 07:17:48'),
(106, '3150612', '$2y$10$tYFK0q21biCAb2m3c/q/dOx0iy4JjrOCq5ey6UycGJZ5hBjirT1Ui', 'employee', 6900, 1, '2025-08-19 07:17:48'),
(107, '3150703', '$2y$10$MI0XQXCwk2zMscIGTFznzugv8oN.6npGBwdpFRk00//q0UxfQCal6', 'employee', 6901, 1, '2025-08-19 07:17:48'),
(108, '3150901', '$2y$10$KE9bdNAZflD1HCQPFkEtvOeVbUQHjGzOo4MwTvFGAyCs/8Tw4NSfy', 'employee', 6902, 1, '2025-08-19 07:17:48'),
(109, '3150908', '$2y$10$2DrK1Zy2Uo/2hxOQVeilo.jAzDzUjdGJPR5EiVyhqz5R431d2/jae', 'employee', 6903, 1, '2025-08-19 07:17:48'),
(110, '3160103', '$2y$10$HdCgKs7ziw2xXttPBRTcCeKeW2Ttf0XcmhZ4dbV0wMAYCac0XJUMq', 'employee', 6904, 1, '2025-08-19 07:17:48'),
(111, '3160115', '$2y$10$mNnJD9doseNhuGEKZPUXkeKbzqSqOaqMfny2eqxBybs/rCMAaiYre', 'employee', 6905, 1, '2025-08-19 07:17:48'),
(112, '3160301', '$2y$10$1T1O4VEgVr5e6C9qs3YNz.kHhcjg3R1lQdq.Z0/BYArOwkgB71MMG', 'employee', 6906, 1, '2025-08-19 07:17:48'),
(113, '3160902', '$2y$10$IOfPCx8AHhQ6fX309uLAzeqtNyVnLVpTdbo0gBNu2cXQ.1BfbVwuC', 'employee', 6907, 1, '2025-08-19 07:17:48'),
(114, '3161209', '$2y$10$sRD2H0lOmCCy4F6GMitA3.4SCuyJyza0KWi7CeBrwSTMhi.8PvW9S', 'employee', 6908, 1, '2025-08-19 07:17:48'),
(115, '3170533', '$2y$10$SljsxQOitVBdzSWLtH/Gn.Y.ufv5K/alBWzWWlpoiD4V6KAIAwxne', 'employee', 6909, 1, '2025-08-19 07:17:48'),
(116, '3180101', '$2y$10$yK4hXU2cPJwitGirOherBuH16qEUUNx8QqmTyg5iSpfrMFMd2gKBW', 'employee', 6910, 1, '2025-08-19 07:17:48'),
(117, '3190302', '$2y$10$PCoqT9yaIdjFrj8LYjVLfOb.YCCqJk/K/G1ZuHJg07tfTn6useDFC', 'employee', 6911, 1, '2025-08-19 07:17:48'),
(118, '3190501', '$2y$10$owFqWGHLckQ7S3FsMl2oAOoOXmc45CzooGTMkj5DMmsAehWY7mnvO', 'employee', 6912, 1, '2025-08-19 07:17:48'),
(119, '3190506', '$2y$10$eba5v/F2K9kjo4yjWT5M.emEgELFN6iJIEkg/80fIUQQy.Pj0JFjq', 'employee', 6913, 1, '2025-08-19 07:17:48'),
(120, '3190602', '$2y$10$z.N..O0Q6a16jS75YFi5Ke1Z8iMBxWtq//bxgFqeK0kvqVHDPYQ4C', 'employee', 6914, 1, '2025-08-19 07:17:48'),
(121, '3190604', '$2y$10$Zc.Sq2qzuca2Ph2fH.puAeci1iFk85tcS6csbQoiONnJdaB2m58Uu', 'employee', 6915, 1, '2025-08-19 07:17:48'),
(122, '3190607', '$2y$10$Zd27oYfnyj4VFFo7aAEX4.lyqxUGGLpLa6xZBSjZx691gweDIcVPi', 'employee', 6916, 1, '2025-08-19 07:17:48'),
(123, '3190608', '$2y$10$TMWq.uB3TooNVqnF/dCp9OSxjEri4ybSJnJt45CR6cPeFX4kQZnnm', 'employee', 6917, 1, '2025-08-19 07:17:48'),
(124, '3190609', '$2y$10$dygRiuYgzmUR9wU4y.qEzODwdzRlyBPYZmOh49STBb6BCaxu2HptK', 'employee', 6918, 1, '2025-08-19 07:17:48'),
(125, '3190701', '$2y$10$iMUknOaNmMZsiiVfmnLmquc/kph1BSh8tD0kwiQF3LzlpBpshMjN.', 'employee', 6919, 1, '2025-08-19 07:17:48'),
(126, '3220501', '$2y$10$5xlFfTbOChtFiNxZntBQrumy.huCabM/kfm2Zk2fScMp.v.gzsq/O', 'employee', 6920, 1, '2025-08-19 07:17:48'),
(127, '3221004', '$2y$10$SDxzsv6KqbQj1AvXcJCraOvoYyqXKkNPNvnuZ6Auzb9B.5jiivy/C', 'employee', 6921, 1, '2025-08-19 07:17:48'),
(128, '3221005', '$2y$10$/VTNXkHH4CzQ9mWY6bv2tOR20CYBGMGZ2fTHOydxERTNVJip5TpDK', 'employee', 6922, 1, '2025-08-19 07:17:48'),
(129, '3221006', '$2y$10$J62FrpIWnBhbSlnTO3XF/.rPTPwsVLRru.72PNI9eo0TQ7Yc5P/tO', 'employee', 6923, 1, '2025-08-19 07:17:48'),
(130, '3221007', '$2y$10$qsVlVAz6H6boHGfuX0wJBesKsOuF9uNtxb4VO.b9kZTPm.8PIp62G', 'employee', 6924, 1, '2025-08-19 07:17:48'),
(131, '3221008', '$2y$10$lEQKDkpdBJNNkp//uAh3A.5W7dumDC/L0G2kmxh5EDbhtQfQb5uBG', 'employee', 6925, 1, '2025-08-19 07:17:48'),
(132, '3221009', '$2y$10$5L0pjiL8ggSDIGlynGrAsegbu40E8DFI3lqKSAUA97udEToyyaBVS', 'employee', 6926, 1, '2025-08-19 07:17:48'),
(133, '3221010', '$2y$10$pUhCN/HFFIlO.Yzw5ckxZOLRrAg72f9yRx7GulD.lEJXDubs2czLG', 'employee', 6927, 1, '2025-08-19 07:17:48'),
(134, '3221011', '$2y$10$8bB4QNcieOSwgffi4P3SNewNcn976MzwytvEMQ3wH8ZExg75D1kE6', 'employee', 6928, 1, '2025-08-19 07:17:48'),
(135, '3221021', '$2y$10$LrqtbzYtKNQK.gnycsRP1ec6VuE5XYcUwwM1i0UJlUn1E/qapwOt6', 'employee', 6929, 1, '2025-08-19 07:17:48'),
(136, '3221022', '$2y$10$ZQp1OcTUn/RcgKvmU0sLtO0dwE7XxL7H4K4RGSbCVd5jjvCEsvzFy', 'employee', 6930, 1, '2025-08-19 07:17:48'),
(137, '3221023', '$2y$10$hcbWlcZi7lxUb8WPXK6dheFKu0YEn/F37Av/kT8u.YvJ/cdpPJIui', 'employee', 6931, 1, '2025-08-19 07:17:48'),
(138, '3221024', '$2y$10$JvAHv88b0hydLY1vQxRwsey/MlUsLAKDZTvp5Fsk3K55IJIyhVCbK', 'employee', 6932, 1, '2025-08-19 07:17:48'),
(139, '3221025', '$2y$10$kt8HhIYRSZF2UMEsHcfprOiqFuRIh9P3pFjEJvq42efqRVCorY6mC', 'employee', 6933, 1, '2025-08-19 07:17:48'),
(140, '3221027', '$2y$10$SPEYg8eFsho5vFou9QPEK.Sq0aacLdQItuxMdMxNm1eisYemklQdm', 'employee', 6934, 1, '2025-08-19 07:17:48'),
(141, '3221028', '$2y$10$gqiuLK.uF6TpDwL.YMxXlOSvp3rTtE01E.NDZOljZr6.FklXnSb3W', 'employee', 6935, 1, '2025-08-19 07:17:48'),
(142, '3221029', '$2y$10$vl4yD1EQa9io6Q6jx3O6ZO4KMZEZBS1MqsRSLVM46F7VbXn4SVcba', 'employee', 6936, 1, '2025-08-19 07:17:48'),
(143, '3221101', '$2y$10$evJqWXI4miwsQ3g3ojLGz.8jbmzX5XV7/DUe4hIawM/RmAvtIphHi', 'employee', 6937, 1, '2025-08-19 07:17:48'),
(144, '3230101', '$2y$10$sUQ1gdCfxqZrobBKcDWqUucqvrgZ70Ozhni3r1U6iIio2c0oUNxtS', 'employee', 6938, 1, '2025-08-19 07:17:48'),
(145, '3230102', '$2y$10$nIfL373zS738un6qKdGJkOS.Ed6NIkYgR3lJFW6lAwbKI/pQ/upUW', 'employee', 6939, 1, '2025-08-19 07:17:48'),
(146, '3230103', '$2y$10$r44FGB3/o5b.mA2Rr2X1IeYTFFZc8w55ZbA3BwoMjR3HOsrtM7F22', 'employee', 6940, 1, '2025-08-19 07:17:48'),
(147, '3230104', '$2y$10$8XIIrGg7qTusEpJLKFUacusXUxljcgHCDrjnGPfKuzG9qXp9yW3x6', 'employee', 6941, 1, '2025-08-19 07:17:48'),
(148, '3230201', '$2y$10$NBk.M62noUKoTUAGXf3FleIOrkUgRj1qSNI8rNf/UJIaDjSS4xPDO', 'employee', 6942, 1, '2025-08-19 07:17:48'),
(149, '3230202', '$2y$10$uP8BrU81vwHDAKiTuvKl3uUgEqucquIy2DUj2qvIquw2gJTOpeiyC', 'employee', 6943, 1, '2025-08-19 07:17:48'),
(150, '3230203', '$2y$10$rWN8kSHj2Esi3wMEXTO/wepATK/qooXmxp0A.IywGX015fkkuZaxW', 'employee', 6944, 1, '2025-08-19 07:17:48'),
(151, '3230205', '$2y$10$DTSVGvIdG3IQBCD/3gdP0.BVdV5Pjk311FPzYywrSSKjeopI/H946', 'employee', 6945, 1, '2025-08-19 07:17:48'),
(152, '3230206', '$2y$10$xstQlGBa2VyreePe77bQJutNQ9xzRWny3i7SL/tyCVO4Lzm8UcFkW', 'employee', 6946, 1, '2025-08-19 07:17:48'),
(153, '3230207', '$2y$10$M43Iv9xP1c3jUGZzHCNhcOOcVD944/xMO9k3dCf4p5rqCDft7IZfa', 'employee', 6947, 1, '2025-08-19 07:17:48'),
(154, '3230211', '$2y$10$GqTnC.xRTeI12v2qgrfBL.A/KHOs1PmNItGErIrz6.Ee93sD.f.oe', 'employee', 6948, 1, '2025-08-19 07:17:48'),
(155, '3230212', '$2y$10$qANBGRSrah5q7DDirRI4PuF9osqBjEnQq9WtKNdiHE5P8RWV4ma/y', 'employee', 6949, 1, '2025-08-19 07:17:48'),
(156, '3230213', '$2y$10$psRfIMMyHdMcGHNdkHqhpu9TxnqN3A.0V5C.z.mcaKMNkjwWs3esu', 'employee', 6950, 1, '2025-08-19 07:17:48'),
(157, '3230214', '$2y$10$Sg/kwZr0zE.QXJJ4TlxoK.APiz8zzjQkX/irNpRmwFjnCpLJWdtb2', 'employee', 6951, 1, '2025-08-19 07:17:48'),
(158, '3230301', '$2y$10$5eAz7pMddC3tZeRDw7ObPuimWfOm9VFsquNhRMWsTPUWfjC41tdHS', 'employee', 6952, 1, '2025-08-19 07:17:48'),
(159, '3230302', '$2y$10$Qv8i0CvwqE.0pPdl4XxyZOcKZvrFcSaqVKDhRkXj7P9./1HATKKCe', 'employee', 6953, 1, '2025-08-19 07:17:48'),
(160, '3230304', '$2y$10$a3BhgrJaeVKFS8b88VdeCOlYgrgVJngDEF//D6dRRCz555KhBbasy', 'employee', 6954, 1, '2025-08-19 07:17:48'),
(161, '3230305', '$2y$10$fjZwVJHsbIctcxEZMryYIehB73oLRCp2MU2qmUnld9anN8udZT6wa', 'employee', 6955, 1, '2025-08-19 07:17:48'),
(162, '3230309', '$2y$10$1Bz7TdALnbTwGd3TznkxVupQiPWJPPYmuxSTBaAnZnhakAxFYvuhS', 'employee', 6956, 1, '2025-08-19 07:17:48'),
(163, '3230310', '$2y$10$/vrIGhbypJe.UBMvnAb7qetUN4B5Vkzr1EJC1etUGwZUt6cCHe4wq', 'employee', 6957, 1, '2025-08-19 07:17:48'),
(164, '3230312', '$2y$10$./it8x5rHjZpxzoAtjYdU.K/apngICZP6PwA7g01BmB.xtRmKB2eG', 'employee', 6958, 1, '2025-08-19 07:17:48'),
(165, '3230313', '$2y$10$dGw5PPhM/Ea.5W70BoRBDeb0uvCRBjT/to56E3/TvuQ2WOPZmHvSC', 'employee', 6959, 1, '2025-08-19 07:17:48'),
(166, '3230316', '$2y$10$amr9C94S4g7A.w.L8afd1eNToJi5kG1v21s9PVkB.oA3fcbjKh37y', 'employee', 6960, 1, '2025-08-19 07:17:48'),
(167, '3230317', '$2y$10$iAq6ms7Q1cReGS1ELE5zUO0c0u1aoslZcU5HJmJpR5bWeP3ojVO6O', 'employee', 6961, 1, '2025-08-19 07:17:48'),
(168, '3230402', '$2y$10$4NsZmQ8fJFsZEx22Zh8dzuHbLeWNHf2s7GuS.yg4WKVpcfkmCr/RO', 'employee', 6962, 1, '2025-08-19 07:17:48'),
(169, '3230406', '$2y$10$APfLbvurSU1nNx9jcwf5W.aMTkpEL2z0Is7bOtwWvlh7uPzCamuAe', 'employee', 6963, 1, '2025-08-19 07:17:48'),
(170, '3230407', '$2y$10$9oqpR/jQfNsoFByADDgUSeJbShjdzj1hjbW8N/DLuXaZvIBvJu8/C', 'employee', 6964, 1, '2025-08-19 07:17:48'),
(171, '3230409', '$2y$10$iab8aZOpqC.jThOr/QpwxOyPKHyIwNKgVMqBgZXoBRwH7PDvAMpl2', 'employee', 6965, 1, '2025-08-19 07:17:48'),
(172, '3230411', '$2y$10$DH09NuW7fyQfkY0wPeKMdebl8PiMjHfn9ddA.vouEFL2YYSUHuth2', 'employee', 6966, 1, '2025-08-19 07:17:48'),
(173, '3230415', '$2y$10$hALZ2g5xguYiTbt977p3s.Xr8KAe/QgdpRdxUm.9tNz.6N9e/Hej2', 'employee', 6967, 1, '2025-08-19 07:17:48'),
(174, '3230416', '$2y$10$5C3kDNlFEN8jxJFfLcB8vOVgc9BvEQ0PCCEYNfvnBHb2O72R1Et72', 'employee', 6968, 1, '2025-08-19 07:17:48'),
(175, '3230601', '$2y$10$LMgbXx2hvzD4Q8lHe5mWp.RYV.gP2Szp1FFMsqVWlzCnHhuCInUaS', 'employee', 6969, 1, '2025-08-19 07:17:48'),
(176, '3230602', '$2y$10$10xGiFdpmzNyq/VCSFpbKeXig5XWoArf8api9pJ6EET14hCL8Dbs2', 'employee', 6970, 1, '2025-08-19 07:17:48'),
(177, '3230603', '$2y$10$KJongwKWEHyRipAICZqOge6a6.Wi3.FhPVaF0IHZ/LVNocXO31j0O', 'employee', 6971, 1, '2025-08-19 07:17:48'),
(178, '3230604', '$2y$10$a0WI/BYXIzTHy5i2jIPw9.3hoYl1I.W.FmT3Cwolp3p7KUGCYnIIy', 'employee', 6972, 1, '2025-08-19 07:17:48'),
(179, '3230801', '$2y$10$fQL5B7TJJM3bhtlSt7olF.sjDt1xgUtvjRuvu4jBu/rsSph.XixyG', 'employee', 6973, 1, '2025-08-19 07:17:48'),
(180, '3230802', '$2y$10$D5JCLWgGaGqF5esdjTtxm.czqx69.mUR4JneEGcPMW4jG9yz8dq0e', 'employee', 6974, 1, '2025-08-19 07:17:48'),
(181, '3231002', '$2y$10$yNvP2Dz9b32Et0jlC47I6us8F21zSbkunvrYpp1tKp.KpJPBdL7h6', 'employee', 6975, 1, '2025-08-19 07:17:48'),
(182, '3231003', '$2y$10$QjZUkFs9Z7ElJrzh76tyEeMPnvSVz/RuAZMhnzfCQD3Q58vOjrbNy', 'employee', 6976, 1, '2025-08-19 07:17:48'),
(183, '3231004', '$2y$10$U2qAq1G6BmbUUrzGqNxX9u9nlJsbEqAJeeN8LqvUFM4/ZZrSloSii', 'employee', 6977, 1, '2025-08-19 07:17:48'),
(184, '3231005', '$2y$10$rflU5OnPDmzRRNInmTEWruhRNDmhVo6kHzfevYOSttdFypDswoVjW', 'employee', 6978, 1, '2025-08-19 07:17:48'),
(185, '3231101', '$2y$10$HjxRzWqBj9fT3pBqN8w5AeAdsEps9s9kLb5.xYmxmXA.snjZtFMrO', 'employee', 6979, 1, '2025-08-19 07:17:48'),
(186, '3231102', '$2y$10$RFiz2/QYKHK53tGzaSPIbuJkHs08WeSYgtTj2ReSTsCf6iYLbONdS', 'employee', 6980, 1, '2025-08-19 07:17:48'),
(187, '3240101', '$2y$10$hPhX7wyX6deMPZxF7qLVl.l9e1MDM3sHb4CPBqKtUiPYw.v8kpjC6', 'employee', 6981, 1, '2025-08-19 07:17:48'),
(188, '3240102', '$2y$10$Nhb6dD3cfiCDitUK43SvE.TJNo/jQrpE9d3Gyk.njWSHmEDllbtb2', 'employee', 6982, 1, '2025-08-19 07:17:48'),
(189, '3240103', '$2y$10$F.hTcc0A9vOER9MrwYBYHOv0Q/1cSjZ/PdkYKEIL/jBbiKtDJGqMq', 'employee', 6983, 1, '2025-08-19 07:17:48'),
(190, '3240105', '$2y$10$0q8/cMNqB5IkHysObNXEU.tZgu14UyWLHuEYRqLbt.VxUYIKRMePC', 'employee', 6984, 1, '2025-08-19 07:17:48'),
(191, '3240107', '$2y$10$4j/k8JooYt4u9wUIfWCxsOWLKN7fslq.05YBX87hH99SjFS0GsjUW', 'employee', 6985, 1, '2025-08-19 07:17:48'),
(192, '3240201', '$2y$10$.gUxzrrnetRjg4iPc9UBjOjfQnGVfze39ECwiMvSPiR0IyRkucNne', 'employee', 6986, 1, '2025-08-19 07:17:48'),
(193, '3240202', '$2y$10$cnxtThC0v7Dvvj2dUJDR3OxDml/EzLAJgpotnOoxIvQFn5FHZiG3e', 'employee', 6987, 1, '2025-08-19 07:17:48'),
(194, '3240203', '$2y$10$yBMAIhLLWx2.cX9IujZoseYhtb2trkQ6IPxniZl0KrElmiQEMaQYC', 'employee', 6988, 1, '2025-08-19 07:17:48'),
(195, '3240204', '$2y$10$Wp7BJ4SMS1floFQPngto5uvZnS0KFc0KtfqPXsIEbMRO9k9CQ265i', 'employee', 6989, 1, '2025-08-19 07:17:48'),
(196, '3240205', '$2y$10$q5STJhu.19OHK2WHhx9nheUSILdGJbBDlJMYxTapXwcjeDKFQbhLy', 'employee', 6990, 1, '2025-08-19 07:17:48'),
(197, '3240208', '$2y$10$Vpe63dQ/H95Aw//jZyNdS.YtYplk8GWXkv58XC3fdBkymKOaeZ2l2', 'employee', 6991, 1, '2025-08-19 07:17:48'),
(198, '3240501', '$2y$10$PVxByAe/nIFPZfzUlI1Xh.XHUstwWJX71tFvMqJxXIboqeu.n3KS6', 'employee', 6992, 1, '2025-08-19 07:17:48'),
(199, '3240502', '$2y$10$qNJlHiKBcPbqDljwpcOwfO6R.nIbphlX5.Rh6xS46FsYNPAgqua1u', 'employee', 6993, 1, '2025-08-19 07:17:48'),
(200, '3240505', '$2y$10$NYE7zVcWp7.JSa7ffSV9Ee9bh0MW/h.xdUmKVjv9SU9P0n95orsP2', 'employee', 6994, 1, '2025-08-19 07:17:48'),
(201, '3240508', '$2y$10$1oAcnJLQXdlMIT8Fth4hvOHN3Y.39Pkrzj3zHg6Rb.VmD75AqRSma', 'employee', 6995, 1, '2025-08-19 07:17:48'),
(202, '3240602', '$2y$10$3iYm4/crtggfcsdVfMcN1.xAFZ5onB2DJekmWv174pZSHWSjO2ZJ6', 'employee', 6996, 1, '2025-08-19 07:17:48'),
(203, '3240603', '$2y$10$yEpvURDa8uRvsvCCR4p.L.zn3AA7IRhVeEB1kV3YitwyJ0PcLyZvS', 'employee', 6997, 1, '2025-08-19 07:17:48'),
(204, '3240604', '$2y$10$bkj4KVHf.EvetSyteCDSCe3ooLEGtxr8Ub5tboPIleoeWoIZxGRvm', 'employee', 6998, 1, '2025-08-19 07:17:48'),
(205, '3240605', '$2y$10$MZ98GtOfETJTJhC6p3yOOeMwWekilZTrrxtCXOHVJ3UbT6XiD/4nG', 'employee', 6999, 1, '2025-08-19 07:17:48'),
(206, '3240606', '$2y$10$GrWREL0YwL7OaaSDyvPdnetVCkRNfsewXjS9yPZELnecGRXYeax.O', 'employee', 7000, 1, '2025-08-19 07:17:48'),
(207, '3240607', '$2y$10$nV3mNv9ymn4jDtki0XKbxurP2p7Mv.iJE1TzaLdzHhN68zDHOsN9S', 'employee', 7001, 1, '2025-08-19 07:17:48'),
(208, '3240701', '$2y$10$LNp9XNSOT/RFkeBgFweSDeMDCGsndgwdHaS100cMgZoQi0brbNcOK', 'employee', 7002, 1, '2025-08-19 07:17:48'),
(209, '3240702', '$2y$10$qTGhxHp6qRdTO/ALfH1VHu/JNOSFr7W.TByyzWHmmndt6iuJKwSe2', 'employee', 7003, 1, '2025-08-19 07:17:48'),
(210, '3240703', '$2y$10$ElmEAieVq5WeZV/8VILlc.NP8FV1Co8ZM/Qz8vG0tB9W/bDQUyIPi', 'employee', 7004, 1, '2025-08-19 07:17:48'),
(211, '3240704', '$2y$10$qY99.3D6eflfKMg79elhSuxidxtlQKeDRlZrmCUAYthoJO8M6OrIe', 'employee', 7005, 1, '2025-08-19 07:17:48'),
(212, '3240705', '$2y$10$C5ZB3Es8SsHXrs5Y3XnCG.jZ3u3CPEujDa8HahUW4pm34JpmfaY/G', 'employee', 7006, 1, '2025-08-19 07:17:48'),
(213, '3240706', '$2y$10$ZwYvA8TEe4aAijPqP3lyKeK/eCU3qzsYVcD8yVnOAtVpY3jQlrIEG', 'employee', 7007, 1, '2025-08-19 07:17:48'),
(214, '3240707', '$2y$10$RwNOj1M01bUFnVDTj9D8R.rLuP6XU2AdSQ5i2J6Mwc5KNyjwpU.XO', 'employee', 7008, 1, '2025-08-19 07:17:48'),
(215, '3240708', '$2y$10$HfuflmTtHooFKlUMnw/QYu8zTDshrD3e5mwB/YUlAkeQwJIUWlKye', 'employee', 7009, 1, '2025-08-19 07:17:48'),
(216, '3240709', '$2y$10$prpOpvJTiVePs.DjoFMxJ.wKJ6kcOjsBjbzq9onxCdUEQ7VyFAUyu', 'employee', 7010, 1, '2025-08-19 07:17:48'),
(217, '3240710', '$2y$10$N/5Xc8M0w.HP.5t5WhjJ6ev8mNDgKEGeh5pCwJ/izSXCsmU3XufuG', 'employee', 7011, 1, '2025-08-19 07:17:48'),
(218, '3240711', '$2y$10$zctSYQ25R8wmXB.eQtIRROGQxQwisQieEfsLUM/wA4ag5daOJf56S', 'employee', 7012, 1, '2025-08-19 07:17:48'),
(219, '3240712', '$2y$10$hcrt3MlBttruONi1gvWayOWLS1FtO1YadBKx1ozbthX3xjOVgXSEm', 'employee', 7013, 1, '2025-08-19 07:17:48'),
(220, '3240713', '$2y$10$E8dRVpcabJQxvCxHUJymeu1/nNClFkT6LNUCadhDk/3V0.MPug8JS', 'employee', 7014, 1, '2025-08-19 07:17:48'),
(221, '3240714', '$2y$10$abvPoKnmgKwWCLWEVd4Y9OJwhYImUD.zAtricUsvQEwuGeiBqRCLa', 'employee', 7015, 1, '2025-08-19 07:17:48'),
(222, '3240715', '$2y$10$04mLnQ9j/ychjMHyUUT0teXVgpZ0xZQwWDJ5i/sGdMch3DXOjrmqa', 'employee', 7016, 1, '2025-08-19 07:17:48'),
(223, '3240801', '$2y$10$MbK5hCLi2eLrOjNqcLK3Luu41DDkXvlkYEhs0l.i8k.xFCLZ29PnS', 'employee', 7017, 1, '2025-08-19 07:17:48'),
(224, '3240802', '$2y$10$WoRPHT087DgQdrU6H4L03O6UIzbR2cAHqM5EdetWvBs4qBAKNvvvC', 'employee', 7018, 1, '2025-08-19 07:17:48'),
(225, '3240901', '$2y$10$OhFgJASZetJluFcNrSNYH./Nq41I8xrRY.aYbwqDKzzhgT5qE/BBG', 'employee', 7019, 1, '2025-08-19 07:17:48'),
(226, '3240902', '$2y$10$ihupqTDnQg.E/rBexmhDX.p.XyUaCSp/y85W4pU9IJiwvU3j50R9K', 'employee', 7020, 1, '2025-08-19 07:17:48'),
(227, '3240903', '$2y$10$5wzXZiyJddsa.g1Kx8MePuQAkafMyZINyQJ78G7ouHEr3WGtqG9zi', 'employee', 7021, 1, '2025-08-19 07:17:48'),
(228, '3241001', '$2y$10$bzCTKUrUoUrZZ8RWtC26R.EdAvUxUP2YMDQEVerNyirKqGmMIMcDy', 'employee', 7022, 1, '2025-08-19 07:17:48'),
(229, '3241002', '$2y$10$MFpoikqYdlwjyQghhtaEE.zqzO3FR1alx7YnX2dscY1b7/h1T5foa', 'employee', 7023, 1, '2025-08-19 07:17:48'),
(230, '3241003', '$2y$10$rWV4DmPtZ6odR7tdscHqO.U9DJmvZCTduFwll5CfUXj1bpAnHWTg6', 'employee', 7024, 1, '2025-08-19 07:17:48'),
(231, '9703002', '$2y$10$KOLnBvkKRGwTG1J4TVVOPOHMRTnDu2UIQiEEBuWnkk13qYQdhtRfG', 'employee', 7025, 1, '2025-08-19 07:17:48'),
(232, '9706019', '$2y$10$wpIWqAm5NRZSurxKce/H6uP.gQ5T5uM1yZuSALjjOUckk8vZ.vrx2', 'employee', 7026, 1, '2025-08-19 07:17:48'),
(233, '9706034', '$2y$10$LJA01R2iB5pchxRKAJ5xzOLV9Sxq.DbNdUxIgj4b5dpZoSBCvpabC', 'employee', 7027, 1, '2025-08-19 07:17:48'),
(234, '9709079', '$2y$10$zfDeArnwZCbS8G8P2A2y4OLPbtPcoRcJYXryJ0OqT8GGk6LpXDf9m', 'employee', 7028, 1, '2025-08-19 07:17:48'),
(235, '9709083', '$2y$10$RIBS4W.KIdnFgQfrXZgJpuTUW2fgXc4dT.wBPMTpdLNXQvaigdqeq', 'employee', 7029, 1, '2025-08-19 07:17:48'),
(236, '9709097', '$2y$10$z7s/X2UqMy8TfWtCUwj4BONvhsGgHkj/7L2Egvus3e2WH4CtwH3cS', 'employee', 7030, 1, '2025-08-19 07:17:48'),
(237, '9709107', '$2y$10$6HArczyiuMf./KUv5npyNO7F3UBqwImKWto04jyPatVehQ0uBqyoa', 'employee', 7031, 1, '2025-08-19 07:17:48'),
(238, '9802136', '$2y$10$MycMJrwxLKKzCri4iiYoQejD2rCmpkLM3ntnTinGdk5hHuzTojOJ2', 'employee', 7032, 1, '2025-08-19 07:17:48'),
(239, '9802137', '$2y$10$Rv1WmLhSkggc1idX9c3NbOEnvhaf8F4bRe6zfEWXi7x1.f164jZx.', 'employee', 7033, 1, '2025-08-19 07:17:48'),
(240, '9802141', '$2y$10$Zzz3nCyrSlYkLS6svO2NP.sxAbPXNSJjoA1KdZO7uB2ENw8I2W5Ny', 'employee', 7034, 1, '2025-08-19 07:17:48'),
(241, '9802171', '$2y$10$Ao4/2fCKGmPpC4tO/TDbLuL7C3iFXwxhkdOHSvjJ7Zt695WYm9R7C', 'employee', 7035, 1, '2025-08-19 07:17:48'),
(242, '9802176', '$2y$10$yKfN4hcoTvpC9L2O1WZX2Ood9v3r4NHuItoA8I0ktuxflcS7YrfB.', 'employee', 7036, 1, '2025-08-19 07:17:48'),
(243, '9807223', '$2y$10$n80RuXX38CAJQm9oorlHEevsZI9z6u1o5NGHSnLmAkFQbc9Z6kKZK', 'employee', 7037, 1, '2025-08-19 07:17:48'),
(244, '9807228', '$2y$10$1r3wnKzt5XGixQ8cO9oMoeno38XgIC7EcX8Xt6JHJ5Uske5u8IKK.', 'employee', 7038, 1, '2025-08-19 07:17:48'),
(245, '9812245', '$2y$10$tB8JAK9LPlD5Cxz56dHwhOtjiZQIDzYXGJT3io1KC0Xl0hNjdQct.', 'employee', 7039, 1, '2025-08-19 07:17:48'),
(246, '9902263', '$2y$10$xOyzvECzIY80xx3e57X./.9jjhcvcTiWrBVrRyrFV6gcOK8MuXRvG', 'employee', 7040, 1, '2025-08-19 07:17:48'),
(247, '9902269', '$2y$10$ARZ4kpuqvRUnC3X8nwg6kOQOGdvTpS..KWDw9v41pYPmVY5HPQb3O', 'employee', 7041, 1, '2025-08-19 07:17:48'),
(248, '9902285', '$2y$10$Lzy5bVXEuNsXJ.mXm87mM./XSpw1nM5y0/gVcwuqx7RQgZ5PU1ZI.', 'employee', 7042, 1, '2025-08-19 07:17:48'),
(249, '9902302', '$2y$10$tUw/Oz7zQ4PfTwlaWuGjm.R4Q7o3edMGPK3rDv0UI.VWZrs8qbTO2', 'employee', 7043, 1, '2025-08-19 07:17:48'),
(250, '9903335', '$2y$10$K7cK3l.MgR.ro8c2c78/4u6h0xYmtAaO5BDXTtIyoKZRVvLIi4MP.', 'employee', 7044, 1, '2025-08-19 07:17:48'),
(251, '9903373', '$2y$10$VMdOwirvCvLaqfh/bSZDTOR4q9zBcgzj6B4MBMqDdXk2PdYJo83YK', 'employee', 7045, 1, '2025-08-19 07:17:48'),
(252, '9903376', '$2y$10$yIpzqDuPpiwXb.2EQudTqOfTbDxPnqx98FDXnwdnY39tDyc4GOZZa', 'employee', 7046, 1, '2025-08-19 07:17:48'),
(253, '9903386', '$2y$10$osmaK33VIJZSr3COkUwbKOcjyY3PFimxsjK0Z0J0ULuYK.LutsQ6W', 'employee', 7047, 1, '2025-08-19 07:17:48'),
(254, '9903424', '$2y$10$8pmvXBQZuYJGzkOxgdjgruknoJJ.UM815Q3hOY1ifJMSPeuezbJ7O', 'employee', 7048, 1, '2025-08-19 07:17:48'),
(255, '9903430', '$2y$10$KnAgQN7iPydsjPuPvkty2elwK.ugbfYjna92RdA30553wgkyFjk8C', 'employee', 7049, 1, '2025-08-19 07:17:48'),
(256, '9904440', '$2y$10$NSJwOck1vXQSF3q70MINZOv/j/q0OVnAzVwDYcdEcWpzI1NC9I/iu', 'employee', 7050, 1, '2025-08-19 07:17:48'),
(257, '9904449', '$2y$10$YbiUu5hqHBwTX0oowwwY1OO2Smrwsd5AYIQbE58bGKtbZSibtQZNq', 'employee', 7051, 1, '2025-08-19 07:17:48'),
(258, '9904477', '$2y$10$E8Uf.HmkpuAPDtbWsJf10ONwg5zN.QBl7lYkwCUwau8mSyugyzgS6', 'employee', 7052, 1, '2025-08-19 07:17:48'),
(259, '9905535', '$2y$10$DlyMPpdgA0rRZbw4TqDwz.7HXPr0z6qsWwbplMeIbem9D6ukNe2Si', 'employee', 7053, 1, '2025-08-19 07:17:48'),
(260, '9905575', '$2y$10$G2zS1wk.Q0s1xcXqur5yY.SqZ60dDG9zujvtS4doYPlFDzEppLkIm', 'employee', 7054, 1, '2025-08-19 07:17:48'),
(261, '9905584', '$2y$10$c1Si6dMOJcWzJICWFNhXu.Uf571p.32.86xw.L5SlxkmZBJr6z78u', 'employee', 7055, 1, '2025-08-19 07:17:48'),
(262, '9905595', '$2y$10$wSrJI9G3zcxQIqWNjaZ0EeI.VEJm9l3vW5d4rjxE98Jxd4K2/d3X6', 'employee', 7056, 1, '2025-08-19 07:17:48'),
(263, '9905654', '$2y$10$xNLeo9gmFntWd8BtTzZTAO9Zo2oXkt6gqO5aJBkj3jv1HG2IGDyV.', 'employee', 7057, 1, '2025-08-19 07:17:48'),
(264, '9907686', '$2y$10$KaLTr/V.LZwlr4A1rkpnTeYaaleHQKJC57P7Ta8mM/r.k/L3kNd1W', 'employee', 7058, 1, '2025-08-19 07:17:48'),
(265, '9910704', '$2y$10$dSJ9N/z7FvNdO6nIJPTT..V7i4t9M8SEUW0f/jTBiCnZqwvzxghy6', 'employee', 7059, 1, '2025-08-19 07:17:48'),
(266, '9911765', '$2y$10$ouVVbSZFJSkcyspc1okzuOCSgQEccF6Wj5Dye/WFIMXxgSMis81aO', 'employee', 7060, 1, '2025-08-19 07:17:48'),
(512, 'local_admin', '$2y$10$h2F7fLL/qzAiXu6/CgjuQuBic43iSW9gKGapyuapI5h1A1EHQu1vK', 'admin', NULL, 0, '2025-08-19 07:17:49'),
(513, 'local_manager', '$2y$10$LqjJOJrG8sYXSN03VvHczedwXCS4CA3FtSSFpmSM5lq4AUDTwGp6C', 'manager', NULL, 0, '2025-08-19 07:17:49'),
(514, 'local_emp', '$2y$10$76djkm628Y40/Q.cSAs7Fe4aKh2PFluvM.uYWVnSNn9rBoOK.tzfG', 'employee', NULL, 0, '2025-08-19 07:17:49'),
(515, '2010008', '$2y$10$lQ8ozlC7np4gs9M.ogcs8.JDqoWp.Gfcq5tGmYm8wMyYxKF7mK9iC', 'employee', 10, 0, '2025-08-19 08:47:35'),
(516, '2017041', '$2y$10$vfBbamxuhu0ItkO07TZEqe2Jh1Lq8Sw615BNWGpShZvA1HjuGMYxC', 'employee', 3, 0, '2025-08-20 17:32:02');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7061;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `japanemployees`
--
ALTER TABLE `japanemployees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6975;

--
-- AUTO_INCREMENT for table `settlements`
--
ALTER TABLE `settlements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settlement_items`
--
ALTER TABLE `settlement_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trip_approvals`
--
ALTER TABLE `trip_approvals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=517;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `japanemployees`
--
ALTER TABLE `japanemployees`
  ADD CONSTRAINT `japanemployees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `japanemployees_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `settlements`
--
ALTER TABLE `settlements`
  ADD CONSTRAINT `settlements_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_approvals`
--
ALTER TABLE `trip_approvals`
  ADD CONSTRAINT `fk_trip_approvals_circular` FOREIGN KEY (`circular_id`) REFERENCES `circular` (`ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_trip_approvals_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
