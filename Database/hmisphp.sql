-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 25, 2025 at 04:53 AM
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
-- Database: `hmisphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `his_admin`
--

CREATE TABLE `his_admin` (
  `ad_id` int(20) NOT NULL,
  `ad_fname` varchar(200) DEFAULT NULL,
  `ad_lname` varchar(200) DEFAULT NULL,
  `ad_email` varchar(200) DEFAULT NULL,
  `ad_pwd` varchar(200) DEFAULT NULL,
  `ad_dpic` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_admin`
--

INSERT INTO `his_admin` (`ad_id`, `ad_fname`, `ad_lname`, `ad_email`, `ad_pwd`, `ad_dpic`) VALUES
(1, 'System', 'Administrator', 'barangan.jb.bscs@gmail.com', 'f527018312300912dc6cc319e6b25dc28aa013b3', 'doc-icon.png');

-- --------------------------------------------------------

--
-- Table structure for table `his_consultations`
--

CREATE TABLE `his_consultations` (
  `consult_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `consult_date` datetime DEFAULT current_timestamp(),
  `consult_notes` text DEFAULT NULL,
  `consult_checklist` text DEFAULT NULL,
  `consult_image` varchar(255) DEFAULT NULL,
  `checklist_files` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `his_consultations`
--

INSERT INTO `his_consultations` (`consult_id`, `pat_id`, `consult_date`, `consult_notes`, `consult_checklist`, `consult_image`, `checklist_files`) VALUES
(25, 31, '2025-06-25 10:35:12', '1', 'X-ray, ', '', 'X-ray: uploads/consultations/1750818912_508149482_1083559987167523_3019846626555645071_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `his_docs`
--

CREATE TABLE `his_docs` (
  `doc_id` int(20) NOT NULL,
  `doc_fname` varchar(200) DEFAULT NULL,
  `doc_lname` varchar(200) DEFAULT NULL,
  `doc_email` varchar(200) DEFAULT NULL,
  `doc_pwd` varchar(200) DEFAULT NULL,
  `doc_dept` varchar(200) DEFAULT NULL,
  `doc_number` varchar(200) DEFAULT NULL,
  `doc_dpic` varchar(200) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_docs`
--

INSERT INTO `his_docs` (`doc_id`, `doc_fname`, `doc_lname`, `doc_email`, `doc_pwd`, `doc_dept`, `doc_number`, `doc_dpic`, `is_active`, `last_active`) VALUES
(20, 'John Bill', 'Barangan', 'patientsussystem@gmail.com', 'a346bc80408d9b2a5063fd1bddb20e2d5586ec30', NULL, '256-05760M', 'RobloxScreenShot20250530_230457091.png', 0, '2025-06-25 10:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `his_patients`
--

CREATE TABLE `his_patients` (
  `pat_id` int(20) NOT NULL,
  `pat_fname` varchar(200) DEFAULT NULL,
  `pat_lname` varchar(200) DEFAULT NULL,
  `pat_dob` varchar(200) DEFAULT NULL,
  `pat_age` varchar(200) DEFAULT NULL,
  `pat_gender` varchar(20) DEFAULT NULL,
  `pat_number` varchar(200) DEFAULT NULL,
  `pat_addr` varchar(200) DEFAULT NULL,
  `pat_phone` varchar(200) DEFAULT NULL,
  `pat_type` varchar(200) DEFAULT NULL,
  `pat_date_reg` datetime DEFAULT NULL,
  `pat_date_joined` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `pat_condition` varchar(255) DEFAULT NULL,
  `pat_discharge_status` varchar(200) DEFAULT NULL,
  `pat_dept` varchar(255) DEFAULT NULL,
  `ref_unit` varchar(255) DEFAULT NULL,
  `pat_treatment` varchar(255) DEFAULT NULL,
  `pat_mun` text DEFAULT NULL,
  `pat_email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patients`
--

INSERT INTO `his_patients` (`pat_id`, `pat_fname`, `pat_lname`, `pat_dob`, `pat_age`, `pat_gender`, `pat_number`, `pat_addr`, `pat_phone`, `pat_type`, `pat_date_reg`, `pat_date_joined`, `pat_condition`, `pat_discharge_status`, `pat_dept`, `ref_unit`, `pat_treatment`, `pat_mun`, `pat_email`) VALUES
(31, 'Large', 'Calagno', '2004-06-03', '21', 'Male', 'OSMDT', '1', '1', 'Inactive', '2025-06-18 18:28:13', '2025-06-25 02:34:47.537087', '111', NULL, '1', '12', '112', NULL, 'barangan.jb.bscs@gmail.com'),
(35, 'Lycka', 'Baylon', '2004-06-11', '21', 'Female', 'EYMAO', 'bahay', '1', 'Discharge', '2025-06-25 00:58:55', '2025-06-24 23:03:20.412503', 'adik sa coke', NULL, 'ad', 'ad', 'ad', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `his_patient_transfers`
--

CREATE TABLE `his_patient_transfers` (
  `t_id` int(20) NOT NULL,
  `t_hospital` varchar(200) DEFAULT NULL,
  `t_date` varchar(200) DEFAULT NULL,
  `t_pat_name` varchar(200) DEFAULT NULL,
  `t_pat_number` varchar(200) DEFAULT NULL,
  `t_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patient_transfers`
--

INSERT INTO `his_patient_transfers` (`t_id`, `t_hospital`, `t_date`, `t_pat_name`, `t_pat_number`, `t_status`) VALUES
(1, 'Kenyatta National Hospital', '2020-01-11', 'Mart Developers', '9KXPM', 'Success');

-- --------------------------------------------------------

--
-- Table structure for table `his_pwdresets`
--

CREATE TABLE `his_pwdresets` (
  `id` int(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `his_update_logs`
--

CREATE TABLE `his_update_logs` (
  `log_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `updated_by` varchar(100) NOT NULL,
  `changed_fields` text NOT NULL,
  `old_values` text NOT NULL,
  `new_values` text NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `his_update_logs`
--

INSERT INTO `his_update_logs` (`log_id`, `pat_id`, `updated_by`, `changed_fields`, `old_values`, `new_values`, `updated_at`) VALUES
(13, 31, 'System Administrator', '[\"First Name\"]', '[\"Harvey\"]', '[\"Large\"]', '2025-06-24 20:36:12'),
(14, 31, 'System Administrator', '[\"Patient\'s Type\"]', '[\"Inactive\"]', '[\"Active\"]', '2025-06-24 20:39:12'),
(15, 31, 'System Administrator', '[\"Patient\'s Type\"]', '[\"Active\"]', '[\"Inactive\"]', '2025-06-25 07:00:03'),
(16, 35, 'John Bill Barangan', '[\"pat_type\"]', '{\"pat_type\":\"Active\"}', '{\"pat_type\":\"Discharge\"}', '2025-06-25 07:03:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `his_admin`
--
ALTER TABLE `his_admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `his_consultations`
--
ALTER TABLE `his_consultations`
  ADD PRIMARY KEY (`consult_id`),
  ADD KEY `pat_id` (`pat_id`);

--
-- Indexes for table `his_docs`
--
ALTER TABLE `his_docs`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `his_patients`
--
ALTER TABLE `his_patients`
  ADD PRIMARY KEY (`pat_id`);

--
-- Indexes for table `his_patient_transfers`
--
ALTER TABLE `his_patient_transfers`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `his_pwdresets`
--
ALTER TABLE `his_pwdresets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `his_update_logs`
--
ALTER TABLE `his_update_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `his_admin`
--
ALTER TABLE `his_admin`
  MODIFY `ad_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `his_consultations`
--
ALTER TABLE `his_consultations`
  MODIFY `consult_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `his_docs`
--
ALTER TABLE `his_docs`
  MODIFY `doc_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `his_patients`
--
ALTER TABLE `his_patients`
  MODIFY `pat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `his_patient_transfers`
--
ALTER TABLE `his_patient_transfers`
  MODIFY `t_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `his_pwdresets`
--
ALTER TABLE `his_pwdresets`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `his_update_logs`
--
ALTER TABLE `his_update_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `his_consultations`
--
ALTER TABLE `his_consultations`
  ADD CONSTRAINT `his_consultations_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `his_patients` (`pat_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
