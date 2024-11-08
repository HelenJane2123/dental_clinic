-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 07:27 AM
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
-- Database: `dental_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `member_id` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `contactnumber` int(11) NOT NULL,
  `termscondition` tinyint(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `date_created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `member_id`, `firstname`, `lastname`, `contactnumber`, `termscondition`, `username`, `password`, `user_type`, `email`, `gender`, `address`, `profile_picture`, `remarks`, `date_created`) VALUES
(11, 'M-5220', 'Heart Jasmine', 'Manalo', 2147483647, 1, 'HeartJasmine', '$2y$10$qsH.xioIYh2iBqUycFzfped/hmxA/iJEp41/cPd8ZN8TA0uAGqcJ2', 'user', 'manalohelenjane@gmail.com', '', '', '', '', '2024-10-09'),
(12, 'M-4034', 'Heart Jasmine', 'Manalo', 2147483647, 0, 'HeartAdmin', '$2y$10$9VaOEL7XykS.us9j9NZmhOK/f4sbo2PLtW1MJRdsWYQvbFPre/Zrq', 'super_admin', 'manaloheartjasmine@gmail.com', '', '', '', '', '2024-10-15');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `services` varchar(50) NOT NULL,
  `status` enum('Pending','Confirmed','Canceled','Completed','Re-schedule') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `canceled_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `appointment_date`, `appointment_time`, `services`, `status`, `notes`, `created_at`, `canceled_at`, `updated_at`) VALUES
(31, 38, '2024-10-16', '10:00:00', 'brace_adjustment', 'Canceled', '', '2024-10-16 01:36:22', NULL, NULL),
(32, 38, '2024-10-18', '09:30:00', 'brace_consultation', 'Canceled', '', '2024-10-16 07:04:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `referral_source` varchar(100) DEFAULT NULL,
  `reason_for_consultation` varchar(255) DEFAULT NULL,
  `previous_dentist` varchar(100) DEFAULT NULL,
  `last_dental_visit` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `guardian_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `history_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `physician_name` varchar(100) DEFAULT NULL,
  `physician_specialty` varchar(100) DEFAULT NULL,
  `physician_address` varchar(255) DEFAULT NULL,
  `physician_phone_no` varchar(20) DEFAULT NULL,
  `good_health` tinyint(1) DEFAULT NULL,
  `under_medical_treatment` tinyint(1) DEFAULT NULL,
  `medical_condition_treated` varchar(255) DEFAULT NULL,
  `serious_illness` tinyint(1) DEFAULT NULL,
  `illness_details` varchar(255) DEFAULT NULL,
  `hospitalization` tinyint(1) DEFAULT NULL,
  `hospitalization_reason` varchar(255) DEFAULT NULL,
  `taking_medication` tinyint(1) DEFAULT NULL,
  `medication_details` varchar(255) DEFAULT NULL,
  `use_tobacco` tinyint(1) DEFAULT NULL,
  `use_drugs` tinyint(1) DEFAULT NULL,
  `allergic_medicine` varchar(255) DEFAULT NULL,
  `pregnant` tinyint(1) DEFAULT NULL,
  `nursing` tinyint(1) DEFAULT NULL,
  `taking_birth_control` tinyint(1) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `blood_pressure` varchar(50) DEFAULT NULL,
  `illness_conditions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `member_id` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('M','F') DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `cellphone_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `member_id`, `last_name`, `first_name`, `middle_name`, `birthdate`, `age`, `sex`, `nickname`, `religion`, `nationality`, `cellphone_no`, `email`, `home_address`, `occupation`) VALUES
(38, 'M-5220', 'Manalo', 'Heart Jasmine', 'Relox', '2004-02-05', 20, 'F', 'Heart', 'Born Again', 'Filipino', '09666988117', 'hjasminermanalo@gmil.com', 'Imus City Cavite', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`guardian_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `guardian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `guardians`
--
ALTER TABLE `guardians`
  ADD CONSTRAINT `guardians_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;