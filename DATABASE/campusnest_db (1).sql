-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 11:48 AM
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
-- Database: `campusnest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('super','staff') NOT NULL DEFAULT 'super',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$jOjKzTMUZ2rgx63LtBFAhOfvVjlweBukqbQLXnCkHxmXWioY2DsX6', 'Super Admin', 'super', '2025-08-27 16:01:41', '2025-08-27 16:01:41');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_payments`
--

CREATE TABLE `hostel_payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Paid','Unpaid') DEFAULT 'Unpaid',
  `txn_id` varchar(50) DEFAULT NULL,
  `payment_month` int(11) NOT NULL,
  `payment_year` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel_payments`
--

INSERT INTO `hostel_payments` (`id`, `student_id`, `plan_id`, `amount`, `status`, `txn_id`, `payment_month`, `payment_year`, `created_at`) VALUES
(2, 4, 4, 5000.00, 'Paid', 'HTXN17579491172489', 9, 2025, '2025-09-15 15:11:57');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_plans`
--

CREATE TABLE `hostel_plans` (
  `id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `category` enum('UG','PG','PhD','Scholar') NOT NULL,
  `type` enum('AC','Non-AC') NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel_plans`
--

INSERT INTO `hostel_plans` (`id`, `plan_name`, `category`, `type`, `cost`, `created_at`) VALUES
(1, 'UG Non-AC', 'UG', 'Non-AC', 3000.00, '2025-09-08 15:46:58'),
(2, 'UG AC', 'UG', 'AC', 4500.00, '2025-09-08 15:46:58'),
(3, 'PG Non-AC', 'PG', 'Non-AC', 3500.00, '2025-09-08 15:46:58'),
(4, 'PG AC', 'PG', 'AC', 5000.00, '2025-09-08 15:46:58'),
(5, 'PhD Non-AC', 'PhD', 'Non-AC', 4000.00, '2025-09-08 15:46:58'),
(6, 'PhD AC', 'PhD', 'AC', 6000.00, '2025-09-08 15:46:58'),
(7, 'Scholar Non-AC', 'Scholar', 'Non-AC', 5000.00, '2025-09-08 15:46:58'),
(8, 'Scholar AC', 'Scholar', 'AC', 7000.00, '2025-09-08 15:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_rooms`
--

CREATE TABLE `hostel_rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `category` enum('UG','PG','PhD','Scholar') NOT NULL,
  `type` enum('AC','Non-AC') NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 1,
  `occupied` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','Full','Maintenance') NOT NULL DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel_rooms`
--

INSERT INTO `hostel_rooms` (`id`, `room_number`, `category`, `type`, `capacity`, `occupied`, `status`, `created_at`) VALUES
(1, 'UG-101', 'UG', 'Non-AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(2, 'UG-102', 'UG', 'AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(3, 'PG-201', 'PG', 'Non-AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(4, 'PG-202', 'PG', 'AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(5, 'PHD-301', 'PhD', 'Non-AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(6, 'PHD-302', 'PhD', 'AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(7, 'SCH-401', 'Scholar', 'AC', 100, 0, 'Available', '2025-09-08 15:58:27'),
(8, 'SCH-402', 'Scholar', 'Non-AC', 100, 0, 'Available', '2025-09-08 15:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `mess_menu`
--

CREATE TABLE `mess_menu` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `meal1` text DEFAULT NULL,
  `meal2` text DEFAULT NULL,
  `meal3` text DEFAULT NULL,
  `meal4` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mess_menu`
--

INSERT INTO `mess_menu` (`id`, `plan_id`, `day_of_week`, `meal1`, `meal2`, `meal3`, `meal4`, `created_at`) VALUES
(1, 1, 'Monday', 'Idli & Sambar', 'Chapati & Dal', NULL, NULL, '2025-09-05 15:06:00'),
(2, 1, 'Tuesday', 'Poha', 'Veg Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(3, 1, 'Wednesday', 'Upma', 'Dal & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(4, 1, 'Thursday', 'Paratha & Curd', 'Veg Pulao', NULL, NULL, '2025-09-05 15:06:00'),
(5, 1, 'Friday', 'Dosa', 'Paneer Curry & Chapati', NULL, NULL, '2025-09-05 15:06:00'),
(6, 1, 'Saturday', 'Aloo Puri', 'Veg Biryani', NULL, NULL, '2025-09-05 15:06:00'),
(7, 1, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(8, 2, 'Monday', 'Idli & Sambar', 'Chapati & Dal', 'Veg Fried Rice', NULL, '2025-09-05 15:06:00'),
(9, 2, 'Tuesday', 'Poha', 'Veg Curry & Rice', 'Dosa', NULL, '2025-09-05 15:06:00'),
(10, 2, 'Wednesday', 'Upma', 'Dal & Rice', 'Paneer Curry', NULL, '2025-09-05 15:06:00'),
(11, 2, 'Thursday', 'Paratha & Curd', 'Veg Pulao', 'Chapati & Curry', NULL, '2025-09-05 15:06:00'),
(12, 2, 'Friday', 'Dosa', 'Paneer Curry & Chapati', 'Veg Biryani', NULL, '2025-09-05 15:06:00'),
(13, 2, 'Saturday', 'Aloo Puri', 'Veg Biryani', 'Curd Rice', NULL, '2025-09-05 15:06:00'),
(14, 2, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', 'Veg Noodles', NULL, '2025-09-05 15:06:00'),
(15, 3, 'Monday', 'Idli', 'Rice & Dal', 'Chapati & Curry', 'Upma', '2025-09-05 15:06:00'),
(16, 3, 'Tuesday', 'Poha', 'Rice & Veg Curry', 'Chapati & Dal', 'Dosa', '2025-09-05 15:06:00'),
(17, 3, 'Wednesday', 'Upma', 'Dal & Rice', 'Paneer Curry', 'Pulao', '2025-09-05 15:06:00'),
(18, 3, 'Thursday', 'Paratha', 'Rice & Curry', 'Veg Biryani', 'Curd Rice', '2025-09-05 15:06:00'),
(19, 3, 'Friday', 'Dosa', 'Paneer Curry & Chapati', 'Veg Rice', 'Idli', '2025-09-05 15:06:00'),
(20, 3, 'Saturday', 'Aloo Puri', 'Veg Biryani', 'Veg Noodles', 'Pongal', '2025-09-05 15:06:00'),
(21, 3, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', 'Chapati & Curry', 'Dosa', '2025-09-05 15:06:00'),
(22, 4, 'Monday', 'Omelette', 'Chicken Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(23, 4, 'Tuesday', 'Egg Curry', 'Fish Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(24, 4, 'Wednesday', 'Boiled Egg', 'Mutton Curry & Chapati', NULL, NULL, '2025-09-05 15:06:00'),
(25, 4, 'Thursday', 'Egg Bhurji', 'Chicken Biryani', NULL, NULL, '2025-09-05 15:06:00'),
(26, 4, 'Friday', 'Omelette', 'Fish Fry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(27, 4, 'Saturday', 'Egg Curry', 'Chicken 65 & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(28, 4, 'Sunday', 'Bread Omelette', 'Chicken Curry & Chapati', NULL, NULL, '2025-09-05 15:06:00'),
(29, 5, 'Monday', 'Egg Curry', 'Chicken Curry & Rice', 'Fish Fry', NULL, '2025-09-05 15:06:00'),
(30, 5, 'Tuesday', 'Omelette', 'Fish Curry & Rice', 'Chicken Biryani', NULL, '2025-09-05 15:06:00'),
(31, 5, 'Wednesday', 'Boiled Egg', 'Mutton Curry & Chapati', 'Egg Curry', NULL, '2025-09-05 15:06:00'),
(32, 5, 'Thursday', 'Egg Bhurji', 'Chicken Curry & Rice', 'Fish Curry', NULL, '2025-09-05 15:06:00'),
(33, 5, 'Friday', 'Omelette', 'Chicken 65', 'Fish Fry', NULL, '2025-09-05 15:06:00'),
(34, 5, 'Saturday', 'Egg Curry', 'Mutton Curry & Rice', 'Chicken Biryani', NULL, '2025-09-05 15:06:00'),
(35, 5, 'Sunday', 'Bread Omelette', 'Chicken Curry', 'Fish Curry', NULL, '2025-09-05 15:06:00'),
(36, 6, 'Monday', 'Omelette', 'Chicken Curry & Rice', 'Fish Fry', 'Egg Curry', '2025-09-05 15:06:00'),
(37, 6, 'Tuesday', 'Egg Curry', 'Fish Curry & Rice', 'Chicken Biryani', 'Mutton Curry', '2025-09-05 15:06:00'),
(38, 6, 'Wednesday', 'Boiled Egg', 'Mutton Curry & Chapati', 'Egg Curry', 'Fish Fry', '2025-09-05 15:06:00'),
(39, 6, 'Thursday', 'Egg Bhurji', 'Chicken Curry & Rice', 'Fish Curry', 'Chicken 65', '2025-09-05 15:06:00'),
(40, 6, 'Friday', 'Omelette', 'Chicken 65', 'Fish Fry', 'Egg Curry', '2025-09-05 15:06:00'),
(41, 6, 'Saturday', 'Egg Curry', 'Mutton Curry & Rice', 'Chicken Biryani', 'Fish Curry', '2025-09-05 15:06:00'),
(42, 6, 'Sunday', 'Bread Omelette', 'Chicken Curry', 'Fish Curry', 'Mutton Curry', '2025-09-05 15:06:00'),
(43, 7, 'Monday', 'Idli', 'Chicken Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(44, 7, 'Tuesday', 'Poha', 'Fish Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(45, 7, 'Wednesday', 'Upma', 'Paneer Curry & Chapati', NULL, NULL, '2025-09-05 15:06:00'),
(46, 7, 'Thursday', 'Paratha', 'Veg Pulao', NULL, NULL, '2025-09-05 15:06:00'),
(47, 7, 'Friday', 'Omelette', 'Chicken 65 & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(48, 7, 'Saturday', 'Aloo Puri', 'Mutton Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(49, 7, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', NULL, NULL, '2025-09-05 15:06:00'),
(50, 8, 'Monday', 'Idli', 'Chicken Curry & Rice', 'Veg Curry', NULL, '2025-09-05 15:06:01'),
(51, 8, 'Tuesday', 'Poha', 'Fish Curry & Rice', 'Chapati & Curry', NULL, '2025-09-05 15:06:01'),
(52, 8, 'Wednesday', 'Upma', 'Paneer Curry & Chapati', 'Egg Curry', NULL, '2025-09-05 15:06:01'),
(53, 8, 'Thursday', 'Paratha', 'Veg Pulao', 'Chicken Fry', NULL, '2025-09-05 15:06:01'),
(54, 8, 'Friday', 'Dosa', 'Paneer Curry', 'Fish Curry', NULL, '2025-09-05 15:06:01'),
(55, 8, 'Saturday', 'Aloo Puri', 'Veg Biryani', 'Chicken Curry', NULL, '2025-09-05 15:06:01'),
(56, 8, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', 'Mutton Curry', NULL, '2025-09-05 15:06:01'),
(57, 9, 'Monday', 'Idli', 'Chicken Curry & Rice', 'Veg Curry', 'Paneer Dish', '2025-09-05 15:06:01'),
(58, 9, 'Tuesday', 'Poha', 'Fish Curry & Rice', 'Chapati & Curry', 'Veg Pulao', '2025-09-05 15:06:01'),
(59, 9, 'Wednesday', 'Upma', 'Paneer Curry & Chapati', 'Egg Curry', 'Mutton Curry', '2025-09-05 15:06:01'),
(60, 9, 'Thursday', 'Paratha', 'Veg Pulao', 'Chicken Fry', 'Dal Rice', '2025-09-05 15:06:01'),
(61, 9, 'Friday', 'Dosa', 'Paneer Curry', 'Fish Curry', 'Veg Biryani', '2025-09-05 15:06:01'),
(62, 9, 'Saturday', 'Aloo Puri', 'Veg Biryani', 'Chicken Curry', 'Curd Rice', '2025-09-05 15:06:01'),
(63, 9, 'Sunday', 'Pongal', 'Mixed Veg Curry & Rice', 'Mutton Curry', 'Chicken 65', '2025-09-05 15:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `mess_payments`
--

CREATE TABLE `mess_payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `plan_type` tinyint(4) NOT NULL,
  `category` enum('veg','nonveg','both') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Paid','Unpaid') NOT NULL DEFAULT 'Unpaid',
  `txn_id` varchar(100) DEFAULT NULL,
  `payment_month` int(11) NOT NULL,
  `payment_year` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mess_payments`
--

INSERT INTO `mess_payments` (`id`, `student_id`, `plan_id`, `plan_type`, `category`, `amount`, `status`, `txn_id`, `payment_month`, `payment_year`, `created_at`) VALUES
(7, 4, 8, 3, 'both', 2600.00, 'Paid', 'TXN17573364349292', 0, 0, '2025-09-08 18:30:34'),
(8, 4, 9, 3, 'both', 3200.00, 'Paid', 'TXN17598414531449', 2025, 10, '2025-10-07 18:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `mess_plans`
--

CREATE TABLE `mess_plans` (
  `id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_type` enum('2-time','3-time','4-time') NOT NULL,
  `category` enum('Veg','Non-Veg','Both') NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mess_plans`
--

INSERT INTO `mess_plans` (`id`, `plan_name`, `plan_type`, `category`, `cost`, `created_at`) VALUES
(1, 'Veg 2-Time Plan', '2-time', 'Veg', 1500.00, '2025-09-05 14:55:10'),
(2, 'Veg 3-Time Plan', '3-time', 'Veg', 2000.00, '2025-09-05 14:55:10'),
(3, 'Veg 4-Time Plan', '4-time', 'Veg', 2500.00, '2025-09-05 14:55:10'),
(4, 'Non-Veg 2-Time Plan', '2-time', 'Non-Veg', 1800.00, '2025-09-05 14:55:10'),
(5, 'Non-Veg 3-Time Plan', '3-time', 'Non-Veg', 2300.00, '2025-09-05 14:55:10'),
(6, 'Non-Veg 4-Time Plan', '4-time', 'Non-Veg', 2800.00, '2025-09-05 14:55:10'),
(7, 'Both 2-Time Plan', '2-time', 'Both', 2000.00, '2025-09-05 14:55:10'),
(8, 'Both 3-Time Plan', '3-time', 'Both', 2600.00, '2025-09-05 14:55:10'),
(9, 'Both 4-Time Plan', '4-time', 'Both', 3200.00, '2025-09-05 14:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `mess_subscriptions`
--

CREATE TABLE `mess_subscriptions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `plan_type` tinyint(4) NOT NULL,
  `category` enum('veg','nonveg','both') NOT NULL,
  `subscribed_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mess_subscriptions`
--

INSERT INTO `mess_subscriptions` (`id`, `student_id`, `plan_type`, `category`, `subscribed_on`) VALUES
(2, 4, 3, 'both', '2025-10-07 18:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `room_allocations`
--

CREATE TABLE `room_allocations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `allocated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_allocations`
--

INSERT INTO `room_allocations` (`id`, `student_id`, `room_id`, `allocated_on`) VALUES
(2, 4, 3, '2025-09-15 15:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `enrollment_no` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `countryname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `parent_mobile` varchar(15) NOT NULL,
  `course` varchar(50) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `category` enum('SC','ST','OBC','GEN') NOT NULL,
  `degree` enum('UnderGraduation','PostGraduation','PhD Scholar') NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `address_proof` longblob NOT NULL,
  `photo` longblob DEFAULT NULL,
  `signature` longblob DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `enrollment_no`, `username`, `password`, `full_name`, `father_name`, `mother_name`, `countryname`, `email`, `gender`, `dob`, `age`, `address`, `mobile_no`, `parent_mobile`, `course`, `semester`, `category`, `degree`, `department_name`, `address_proof`, `photo`, `signature`, `status`, `created_at`) VALUES
(1, 'ENR001', 'rahul123', '$2y$10$....yourhash....', 'Rahul Sharma', 'Amit Sharma', 'Sunita Sharma', 'India', 'rahul@example.com', 'Male', '2002-05-10', 22, 'Delhi, India', '9876543210', '9123456789', 'BCA', '3', 'GEN', 'UnderGraduation', 'Computer Science', 0x726168756c5f69642e706466, 0x726168756c2e6a7067, 0x726168756c5f7369676e2e6a7067, 1, '2025-08-22 08:53:52'),
(2, 'ENR002', 'priya_k', '$2y$10$OQYuv4dJ6CV6O0OhyRcP0uSaxfZbcycVxhiVXZYnC4uFTfVf0.Y5q', 'Priya Kumari', 'Ramesh Kumar', 'Meena Devi', 'India', 'priya@example.com', 'Female', '2003-08-15', 21, 'Patna, Bihar', '9876501234', '9988776655', 'MCA', '1', 'OBC', 'PostGraduation', 'Information Technology', 0x70726979615f69642e706466, 0x70726979612e6a7067, 0x70726979615f7369676e2e6a7067, 1, '2025-08-22 08:53:52'),
(3, 'ENR003', 'arjun45', '$2y$10$OQYuv4dJ6CV6O0OhyRcP0uSaxfZbcycVxhiVXZYnC4uFTfVf0.Y5q', 'Arjun Verma', 'Suresh Verma', 'Kavita Verma', 'India', 'arjun@example.com', 'Male', '2001-12-01', 23, 'Lucknow, UP', '9998887776', '9776655443', 'B.Tech', '5', 'SC', 'UnderGraduation', 'Electronics', 0x61726a756e5f69642e706466, 0x61726a756e2e6a7067, 0x61726a756e5f7369676e2e6a7067, 1, '2025-08-22 08:53:52'),
(4, '2451001001639', 'Mithlesh123', '$2y$10$/C04IPJ05G5djTEpgcKNCuNiPWTx239IdGP43vFsyejDDVT2fAIHy', 'Mithlesh kumar prajapati', 'Ramji pankaj', 'meena devi', 'india', 'silentkillff09@gmail.com', 'Male', '2002-09-02', 24, 'mirzapur uttar pradesh', '7238035504', '9451622334', 'MCA', '3', 'OBC', 'PostGraduation', 'computer Science', 0x313735353933323832315f454343323030353031382d4e4f33304b2e706466, 0x494d472d32303234303131382d5741303030322e6a7067, 0x494d472d32303234303131382d5741303030312e6a7067, 1, '2025-08-23 07:07:01'),
(8, '2451001001640', 'mithlesh124', '$2y$10$YD3.SoSmuZbj7.81E1HypO/Hs3jm0GVUg4VAuTqH9ggnYzPLJr9E2', 'Mithlesh kumar ', 'Ramji pankaj', 'meena devi', '', 'abc@gmail.com', '', '0000-00-00', 0, 'mirzapur uttar pradesh', '7238035504', '', '', '', '', '', '', 0x313735363536363235325f454343323030353031382d4e4f33304b2e706466, 0x494d472d32303234303131382d5741303030322e6a7067, 0x494d472d32303234303131382d5741303030312e6a7067, 1, '2025-08-30 15:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `visitor`
--

CREATE TABLE `visitor` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `visitor_name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `relation` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `visitor_name` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `relation` varchar(50) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `visit_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hostel_payments`
--
ALTER TABLE `hostel_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `hostel_plans`
--
ALTER TABLE `hostel_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `mess_menu`
--
ALTER TABLE `mess_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plan_id` (`plan_id`,`day_of_week`);

--
-- Indexes for table `mess_payments`
--
ALTER TABLE `mess_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `mess_plans`
--
ALTER TABLE `mess_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plan_type` (`plan_type`,`category`);

--
-- Indexes for table `mess_subscriptions`
--
ALTER TABLE `mess_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `room_allocations`
--
ALTER TABLE `room_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollment_no` (`enrollment_no`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hostel_payments`
--
ALTER TABLE `hostel_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hostel_plans`
--
ALTER TABLE `hostel_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mess_menu`
--
ALTER TABLE `mess_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `mess_payments`
--
ALTER TABLE `mess_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mess_plans`
--
ALTER TABLE `mess_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mess_subscriptions`
--
ALTER TABLE `mess_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_allocations`
--
ALTER TABLE `room_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `visitor`
--
ALTER TABLE `visitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hostel_payments`
--
ALTER TABLE `hostel_payments`
  ADD CONSTRAINT `hostel_payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `hostel_payments_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `hostel_plans` (`id`);

--
-- Constraints for table `mess_menu`
--
ALTER TABLE `mess_menu`
  ADD CONSTRAINT `mess_menu_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `mess_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mess_payments`
--
ALTER TABLE `mess_payments`
  ADD CONSTRAINT `mess_payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mess_subscriptions`
--
ALTER TABLE `mess_subscriptions`
  ADD CONSTRAINT `mess_subscriptions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_allocations`
--
ALTER TABLE `room_allocations`
  ADD CONSTRAINT `room_allocations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `room_allocations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `hostel_rooms` (`id`);

--
-- Constraints for table `visitor`
--
ALTER TABLE `visitor`
  ADD CONSTRAINT `visitor_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
