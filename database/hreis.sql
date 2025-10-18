-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Oct 05, 2025 at 08:41 AM
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
-- Database: `hreis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'System Admin', 'admin@hreis.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-09-09 10:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(255) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_position` varchar(255) NOT NULL,
  `department_description` varchar(255) NOT NULL,
  `department_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `employee_type` varchar(100) DEFAULT NULL,
  `salary_grade` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `cs_eligibility` varchar(255) DEFAULT NULL,
  `working_days` varchar(50) DEFAULT NULL,
  `joining_date` datetime DEFAULT NULL,
  `government_id` varchar(255) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `service_record` varchar(255) DEFAULT NULL,
  `appointment_paper` varchar(255) DEFAULT NULL,
  `tor` varchar(255) DEFAULT NULL,
  `cs_certificate` varchar(255) DEFAULT NULL,
  `pds` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `avatar`, `username`, `firstname`, `lastname`, `mobile_number`, `date_of_birth`, `marital_status`, `gender`, `nationality`, `address`, `city`, `state`, `zip_code`, `designation`, `employee_type`, `salary_grade`, `department`, `cs_eligibility`, `working_days`, `joining_date`, `government_id`, `cv`, `service_record`, `appointment_paper`, `tor`, `cs_certificate`, `pds`, `email`, `password`, `status`) VALUES
(1, 'EMP001', 'uploads/avatars/yuta.jpeg', 'jdoe', 'John', 'Doe', '09171234567', '1995-05-15', 'Married', 'Male', 'Filipinos', '123 Main St', 'Polangui', 'Albay', '4506', 'HR Officer', 'Permanent', 'SG-12', 'HR Department', 'Civil Service Eligible', 'Mon-Fri', '2025-08-05 19:49:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'johndoe@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(4, 'EMP002', 'uploads/avatars/image2.jpg', 'reynald.agustin', 'Reynald', 'Agustin', '09090937257', '2025-09-25', 'Single', 'Male', '12', 'Davao City Diversion Rd', 'Davao City', 'Davao del Sur', '123', 'Accounting Staff', 'Permanent', '13', 'Accounting Office', '12', '1', '2025-09-25 17:01:00', '', '', '', '', '', '', '', 'reynald@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(5, 'EMP003', 'uploads/avatars/13120112.png', 'm.santos', 'Maria', 'Santos', '09181234567', '1993-07-22', 'Married', 'Female', 'Filipino', '456 Mabini St', 'Legazpi', 'Albay', '4500', 'Accounting Clerk', 'Permanent', 'SG-10', 'Accounting Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'maria.santos@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(6, 'EMP004', 'uploads/avatars/image3.png', 'j.cruz', 'Juan', 'Cruz', '09291234567', '1990-01-15', 'Single', 'Male', 'Filipino', '789 Rizal St', 'Naga', 'Camarines Sur', '4400', 'Assistant HR', 'Contractual', 'SG-5', 'HR Department', 'N/A', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'juan.cruz@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(7, 'EMP005', NULL, 'a.delacruz', 'Ana', 'Dela Cruz', '09391234567', '1995-03-25', 'Single', 'Female', 'Filipino', '123 Mabuhay Rd', 'Tabaco', 'Albay', '4511', 'Staff', 'Permanent', 'SG-8', 'Planning and Development Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ana.delacruz@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(8, 'EMP006', NULL, 'r.garcia', 'Roberto', 'Garcia', '09481234567', '1988-09-12', 'Married', 'Male', 'Filipino', '45 Poblacion', 'Sorsogon City', 'Sorsogon', '4700', 'Assessor Staff', 'Permanent', 'SG-9', 'Assessor\'s Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'roberto.garcia@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(9, 'EMP007', NULL, 'l.mendoza', 'Liza', 'Mendoza', '09581234567', '1997-11-05', 'Single', 'Female', 'Filipino', '99 San Isidro', 'Iriga City', 'Camarines Sur', '4431', 'Assessor Clerk', 'Contractual', 'SG-6', 'Assessor\'s Office', 'N/A', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'liza.mendoza@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(10, 'EMP008', NULL, 'p.reyes', 'Pedro', 'Reyes', '09681234567', '1985-05-28', 'Married', 'Male', 'Filipino', '22 Mabuhay St', 'Ligao', 'Albay', '4504', 'Agriculture Officer', 'Permanent', 'SG-11', 'Agriculture Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pedro.reyes@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(11, 'EMP009', 'uploads/avatars/avatar1.png', 'c.ramos', 'Cathy', 'Ramos', '09781234567', '1998-08-19', 'Single', 'Female', 'Filipino', '11 Malaya St', 'Daet', 'Camarines Norte', '4600', 'Engineering Staff', 'Contractual', 'SG-7', 'Engineering Office', 'N/A', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cathy.ramos@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(12, 'EMP010', 'uploads/avatars/mysql-logo-png-image-11660514413jvwkcjh4av-removebg-preview.png', 'e.villanueva', 'Eduardo', 'Villanueva', '09881234567', '1991-12-30', 'Married', 'Male', 'Filipino', '7 Mabini St', 'Legazpi', 'Albay', '4500', 'Aggriculturist', 'Permanent', 'SG-12', 'Agriculture Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eduardo.villanueva@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(13, 'EMP011', NULL, 's.fernandez', 'Sarah', 'Fernandez', '09981234567', '1996-04-14', 'Single', 'Female', 'Filipino', '88 Freedom Rd', 'Sorsogon City', 'Sorsogon', '4700', 'Staff', 'Permanent', 'SG-9', 'Planning and Development Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sarah.fernandez@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(14, 'EMP012', NULL, 'm.lopez', 'Michael', 'Lopez', '09181237654', '1994-10-10', 'Single', 'Male', 'Filipino', '55 Mabuhay Rd', 'Polangui', 'Albay', '4506', 'Engineering Staff', 'Permanent', 'SG-10', 'Engineering Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-15 17:04:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'michael.lopez@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(18, 'EMP013', NULL, 'a.santos', 'Alfredo', 'Santos', '09170001111', '1992-02-15', 'Single', 'Male', 'Filipino', '123 Mabini St', 'Legazpi', 'Albay', '4500', 'Accounting Clerk I', 'Permanent', 'SG-8', 'Accounting Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-19 17:09:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'alfredo.santos@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(19, 'EMP014', NULL, 'b.gonzales', 'Bea', 'Gonzales', '09170002222', '1994-05-20', 'Married', 'Female', 'Filipino', '456 Rizal Ave', 'Naga', 'Camarines Sur', '4400', 'Accounting Clerk II', 'Permanent', 'SG-9', 'Accounting Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-19 17:09:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bea.gonzales@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(20, 'EMP015', NULL, 'c.rosales', 'Carlo', 'Rosales', '09170003333', '1990-08-10', 'Single', 'Male', 'Filipino', '789 Bonifacio St', 'Polangui', 'Albay', '4506', 'Senior Accountant', 'Permanent', 'SG-12', 'Accounting Office', 'Civil Service Eligible', 'Mon-Fri', '2025-09-19 17:09:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'carlo.rosales@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'active'),
(37, 'EMP000', 'uploads/avatars/486390805_1778517839703328_3113170258892281313_n.jpg', 'arzel john.zolina', 'Arzel John', 'Zolina', '09090937257', '2001-09-10', 'Married', 'Male', 'Filipino', 'Purok Masagana 1', 'Polomolok', 'South Cotabato', '9504', 'Staff', 'Permanent', 'SG-10', 'HR Department', 'Not Eligible', 'Mon-Friday', '2025-09-27 05:44:00', 'uploads/gov_id/DTI-CERTIFICATE-RDDZ791217541080 (1).pdf', 'uploads/cv/dummy.pdf', 'uploads/service_record/Wireshark_Lab_Activities.pdf', 'uploads/appointment_paper/dummy.pdf', 'uploads/tor/DTI-CERTIFICATE-RDDZ791217541080 (1).pdf', 'uploads/cs_certificate/Wireshark_Lab_Activities.pdf', '', 'ajmixrhyme@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `leave_user_name` int(11) NOT NULL,
  `leave_duration` varchar(255) NOT NULL,
  `leave_start_date` date NOT NULL,
  `leave_end_date` date NOT NULL,
  `leave_resumption_date` date NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `leave_reason` varchar(255) NOT NULL,
  `leave_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `leave_user_name`, `leave_duration`, `leave_start_date`, `leave_end_date`, `leave_resumption_date`, `leave_type`, `leave_reason`, `leave_status`) VALUES
(1, 1, '3', '2025-09-20', '2025-09-22', '2025-09-23', 'Vacation', 'Lagnat', 'Approved'),
(5, 6, '1', '2025-09-25', '2025-09-25', '2025-09-26', 'Sick', 'Highblood', 'Pending'),
(6, 10, '12', '2025-09-26', '2025-10-07', '2025-10-08', 'Emergency', 'Masakit Ulo', 'Pending'),
(7, 21, '6', '2025-09-25', '2025-09-30', '2025-10-01', 'Vacation', 'Masakit Likod', 'Pending'),
(8, 20, '30', '2025-09-20', '2025-10-19', '2025-10-20', 'Emergency', 'Sumakit Ulo', 'Pending'),
(9, 1, '5', '2025-09-25', '2025-09-29', '2025-09-30', 'Sick', 'broken hearted', 'Pending'),
(10, 13, '12', '2025-09-27', '2025-10-08', '2025-10-09', 'Vacation', 'family bonding', 'Pending'),
(11, 13, '12', '2025-09-27', '2025-10-08', '2025-10-09', 'Vacation', 'family bonding', 'Pending'),
(12, 18, '12', '2025-09-27', '2025-10-08', '2025-10-09', 'Sick', 'dental problem', 'Pending'),
(13, 18, '12', '2025-09-27', '2025-10-08', '2025-10-09', 'Sick', 'dental problem', 'Pending'),
(14, 18, '1', '2025-10-09', '2025-10-09', '2025-10-10', 'Emergency', 'Emergency', 'Pending'),
(15, 37, '4', '2025-10-11', '2025-10-14', '2025-10-15', 'Emergency', 'LockJaw', 'Approved'),
(16, 18, '13', '2025-10-04', '2025-10-16', '2025-10-17', 'Vacation', 'Summer', 'Pending'),
(17, 12, '5', '2025-10-04', '2025-10-08', '2025-10-09', 'Sick', 'lagnat', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
