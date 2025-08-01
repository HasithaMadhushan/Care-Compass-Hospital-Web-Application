-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 26, 2025 at 05:26 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `care_compass_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('scheduled','completed','cancelled','unassigned') DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` enum('low','medium','high','urgent') DEFAULT 'low',
  `special_preparation` tinyint(1) DEFAULT '0',
  `doctor_name` varchar(255) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `date`, `time`, `status`, `created_at`, `updated_at`, `priority`, `special_preparation`, `doctor_name`, `payment_status`) VALUES
(88, 31, 1, '2025-02-27', '09:13:00', 'scheduled', '2025-02-26 03:40:59', '2025-02-26 03:40:59', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(87, 31, 1, '2025-02-19', '09:13:00', 'scheduled', '2025-02-26 03:40:19', '2025-02-26 03:40:19', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(86, 31, 1, '2025-02-10', '01:08:00', 'scheduled', '2025-02-26 03:38:27', '2025-02-26 03:38:27', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(84, 31, 7, '2025-03-01', '05:44:00', 'scheduled', '2025-02-26 00:09:17', '2025-02-26 00:09:17', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(83, 31, 7, '2025-02-28', '08:38:00', 'scheduled', '2025-02-26 00:08:37', '2025-02-26 00:08:37', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(82, 31, 7, '2025-02-28', '11:36:00', 'scheduled', '2025-02-26 00:06:58', '2025-02-26 00:06:58', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(80, 31, 7, '2025-02-27', '08:38:00', 'scheduled', '2025-02-26 00:02:16', '2025-02-26 00:02:16', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(81, 31, 7, '2025-02-27', '09:34:00', 'scheduled', '2025-02-26 00:04:27', '2025-02-26 00:04:27', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(79, 31, 7, '2025-02-27', '08:34:00', 'completed', '2025-02-26 00:01:15', '2025-02-26 02:01:43', 'low', 0, 'Dr.Ranasinge', 'paid'),
(69, 31, 4, '2025-02-26', '10:00:00', 'completed', '2025-02-25 00:40:32', '2025-02-25 03:01:21', 'low', 0, 'Dr. Alice Johnson', 'unpaid'),
(70, 31, 1, '2025-02-20', '17:00:00', 'cancelled', '2025-02-25 09:28:16', '2025-02-25 23:45:29', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(71, 31, 2, '2025-02-07', '15:00:00', 'scheduled', '2025-02-25 19:27:51', '2025-02-25 19:27:51', 'low', 0, 'Dr. Bob Williams', 'Paid'),
(72, 31, 2, '2025-02-07', '06:00:00', 'scheduled', '2025-02-25 19:36:07', '2025-02-25 19:36:07', 'low', 0, 'Dr. Bob Williams', 'Paid'),
(73, 31, 1, '2025-02-07', '06:16:00', 'cancelled', '2025-02-25 19:42:25', '2025-02-25 23:45:35', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(74, 31, 1, '2025-02-07', '09:16:00', 'cancelled', '2025-02-25 19:45:02', '2025-02-25 23:45:32', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(85, 31, 1, '2025-02-27', '09:09:00', 'scheduled', '2025-02-26 03:36:25', '2025-02-26 03:36:25', 'low', 0, 'Dr. Alice Johnson', 'Paid'),
(77, 31, 5, '2025-02-27', '05:31:00', 'scheduled', '2025-02-25 23:57:54', '2025-02-26 02:03:09', 'low', 0, 'Dr.Ranasinge', 'Paid'),
(78, 31, 6, '2025-02-27', '08:31:00', 'scheduled', '2025-02-26 00:00:23', '2025-02-26 02:03:33', 'low', 0, 'Dr.Ranasinge', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

DROP TABLE IF EXISTS `contact_inquiries`;
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'bname', 'hasitha@gmail.com', 'dsadas', '2025-01-20 22:29:48'),
(2, 'hasitha', 'dsada@gmail.com', 'sssssssssssssss', '2025-01-22 12:25:59'),
(3, 'daraya', 'daraya1@gmail.com', 'fuck you', '2025-02-19 15:49:54'),
(4, 'daraya', 'daraya1@gmail.com', 'fuck you', '2025-02-19 15:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `qualifications` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `availability` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `qualifications`, `branch`, `availability`, `picture`) VALUES
(1, 'Dr. Alice Johnson', 'Cardiologist', 'MBBS, MD', 'Kandy', 'Available on weekdays', '67bc714b822455.48943367.jpg'),
(2, 'Dr. Bob Williams', 'Dermatologist', 'MBBS, MD', 'Colombo', 'Available on weekends', '67bc716a498cc0.54768819.jpg'),
(3, 'Dr. Clara Davis', 'Orthopedic Surgeon', 'MBBS, MS', 'Kurunegala', 'Available on weekdays', '67bc71c7aa6603.84039566.jpg'),
(4, 'Dr. Alice Smith', 'Cardiologist', 'MBBS, MD', 'Kandy', 'Available on weekends', '67bc71f49d4e99.55450324.jpg'),
(5, 'Dr. John Doe', 'Neurologist', 'MBBS, MD', 'Colombo', 'Available on weekdays', '67bc71ffacf5e9.75894831.jpg'),
(6, 'Dr. Sarah Lee', 'Pediatrician', 'MBBS, MD', 'Kurunegala', 'Available on weekends', '67bc725ab0e471.09994889.jpg'),
(7, 'Dr.Ranasinge', 'phycology', 'Mbbs/MB', 'Colombo', 'Weekend', '67bc6dbd616fc3.25759419.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`) VALUES
(1, 'How can I book an appointment?', 'You can book an appointment using the Services Dashboard or by contacting us directly.'),
(2, 'What are your working hours?', 'Our hospital is open 24/7, but specific departments have different schedules.'),
(3, 'Do you offer emergency services?', 'Yes, we provide 24/7 emergency services.'),
(4, 'How can I access my medical records?', 'Log in to your Patient Dashboard to view and download medical records.'),
(5, 'What payment methods do you accept?', 'We accept cash, credit cards, and online payments.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `patient_name`, `feedback`, `submitted_at`) VALUES
(17, 'daraya', 'ss', '2025-02-24 18:23:33'),
(18, 'daraya', 'sssss', '2025-02-24 18:26:21'),
(19, 'daraya', 'zzzzzzzzzzzzzz', '2025-02-24 18:45:16'),
(20, 'daraya', 'saaaaaaaaaaaaaaaaaa', '2025-02-24 18:47:49'),
(21, 'daraya', 'vvvvvvvvvvvv', '2025-02-24 18:49:00'),
(22, 'daraya', 'vvvvvvvvvvvv', '2025-02-24 18:50:22'),
(23, 'daraya', 'bbbbbbbbbbbbbbbbbb', '2025-02-24 18:50:31'),
(24, 'daraya', 'zxzxzx', '2025-02-24 18:52:08'),
(25, 'daraya', 'ssss', '2025-02-25 23:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `restock_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `category`, `quantity`, `status`, `restock_date`, `created_at`) VALUES
(1, 'Syringes', 'Medical Supplies', 4, 'available', '2025-02-01', '2025-01-21 12:56:19'),
(2, 'Stethoscopes', 'Medical Equipment', 0, 'available', '2025-01-25', '2025-01-21 12:56:19'),
(3, 'Gauze', 'Medical Supplies', 8, 'unavailable', '2025-01-30', '2025-01-21 12:56:19'),
(4, 'Wheelchairs', 'Medical Equipment', 0, 'available', '2025-03-15', '2025-01-21 12:56:19'),
(5, 'Face Masks', 'PPE', 0, 'available', '0000-00-00', '2025-01-21 12:56:19'),
(6, 'Oxygen Tanks', 'Medical Equipment', 0, 'available', '2025-02-15', '2025-01-21 12:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'hasitha', 'hasitha@gmail.com', 'ssssssssssssssssssssssss', '2025-02-24 19:33:45'),
(2, 'hasitha', 'hasitha@gmail.com', 'nnnnnnnnnnn', '2025-02-24 19:38:07'),
(3, 'bname', 'staff@gmail.com', 'bbbbbbbbb', '2025-02-24 19:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `supply_requests`
--

DROP TABLE IF EXISTS `supply_requests`;
CREATE TABLE IF NOT EXISTS `supply_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `supply_requests`
--

INSERT INTO `supply_requests` (`id`, `staff_id`, `item_name`, `quantity`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(10, 32, 'gose', 1000, 'wrap', 'approved', '2025-02-25 04:30:00', '2025-02-25 04:39:27'),
(12, 32, 'wax', 100, 'wrap', 'pending', '2025-02-25 04:31:46', '2025-02-25 04:31:46'),
(13, 32, 'bandage red', 100, 'need', 'pending', '2025-02-25 04:32:07', '2025-02-25 04:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL,
  `task_description` text NOT NULL,
  `status` enum('pending','in-progress','completed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `staff_id`, `task_description`, `status`, `created_at`, `updated_at`) VALUES
(16, 32, 'cleaning', 'pending', '2025-02-25 02:59:01', '2025-02-26 04:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `message`, `created_at`) VALUES
(1, 'John D.', 'Care Compass Hospitals saved my life! The doctors and staff are amazing.', '2025-01-20 18:34:04'),
(2, 'Sarah P.', 'The best hospital experience I have ever had. Highly recommend their services.', '2025-01-20 18:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','patient') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT '',
  `status` enum('active','archived') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `first_name`, `last_name`, `phone`, `status`) VALUES
(4, 'admin@gmail.com', '$2y$10$r/yfn8SsKLpGbGq03JPg1uBiboq2ECmfq7KN5iEX.2AaBwf58aw22', 'admin', '2025-01-21 07:44:01', 'admin', '1', '', 'active'),
(31, 'daraya@gmail.com', '$2y$10$O1qBRp.yOqndoJVQpq7ZoO4.zJwjAk7kOo5kmaUYflUJM6VWT0j62', 'patient', '2025-02-24 11:58:51', 'daraya', 'ukuwel', '0987654321', 'active'),
(32, 'staff@gmail.com', '$2y$10$Jw09Z/WLQNoQ6UmVFY2wyue62RqNmFgIpXZQoYQ60hyKKSM1ZsqqS', 'staff', '2025-02-25 01:53:56', 'staff', 'one', '0987654321', 'active');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `supply_requests`
--
ALTER TABLE `supply_requests`
  ADD CONSTRAINT `supply_requests_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
