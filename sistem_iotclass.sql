-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 01, 2025 at 10:12 AM
-- Server version: 8.0.42
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_iotclass`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int NOT NULL,
  `serial_number` varchar(8) NOT NULL,
  `sensor_actuator` enum('sensor','actuator') NOT NULL,
  `value` varchar(10) NOT NULL,
  `name` varchar(10) NOT NULL,
  `topic` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `serial_number`, `sensor_actuator`, `value`, `name`, `topic`) VALUES
(8, '12345678', 'sensor', '30', 'suhu', '12345678/suhu'),
(10, '12345678', 'actuator', '180', 'servo', '12345678/pintu'),
(17, '12345678', 'sensor', '40', 'suhu', 'kelasiot/12345678/suhu'),
(18, '12345678', 'sensor', '124', 'kelembaban', 'kelasiot/12345678/kelembaban');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `serial_number` varchar(8) NOT NULL,
  `mcu_type` varchar(15) NOT NULL,
  `location` text NOT NULL,
  `active` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Yes',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`serial_number`, `mcu_type`, `location`, `active`, `created_time`) VALUES
('12345678', 'ESP32', 'Gedung A', 'Yes', '2025-04-29 10:14:06'),
('87654321', 'Test', 'lokasi', 'Yes', '2025-04-29 10:53:27'),
('asdfgh', 'ESP32', 'rumah', 'Yes', '2025-04-29 16:07:42'),
('hae', 'Raspberry Pi', 'Labkom', 'No', '2025-04-29 22:10:14'),
('hai', 'ESP32', 'Rumah', 'No', '2025-04-29 22:09:27'),
('hjan', 'arduino', 'sekolah', 'Yes', '2025-04-29 19:10:19'),
('hujansan', 'merah', 'disono', 'Yes', '2025-04-29 19:36:56'),
('pwwoqw', 'Arduino Nano', 'Labkom', 'No', '2025-04-29 19:30:47'),
('qwertyu', 'ESP32', '-', 'Yes', '2025-04-29 19:09:56'),
('zxcvb', 'arduino', 'mall', 'Yes', '2025-04-29 19:29:09'),
('zxcvbn', 'ESP32', 'pasar', 'Yes', '2025-04-29 19:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `active` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `fullname`, `role`, `active`) VALUES
('asaa', '$2y$10$MzlSdy80P8E7DLOBPTHcDuSf./FPyoAU/2c8l8xvvlR8C9oiHXQ5y', 'dirgaaa', 'Admin', 'Yes'),
('asepp', 'aseppoi', 'aaaaseeeppp', 'User', 'Yes'),
('dopyzero', '$2y$10$uQ5RwBtQZt6KUF8PmQeiuuaRcEz4Hze3MP.PMTll8ABLxDe.GwFpu', 'Dhiaraqi Ahmad Khaizuran', 'Admin', 'Yes'),
('khai', 'khaizuran', 'khaizuran', 'Admin', 'Yes'),
('Khaizuran', '$2y$10$iVwZdY0unB6M7NeXYJojFeyXj0GkCUo5qBrCjl1mp8K5ha1JjW2IO', 'Khaizuran Ahmad Dhiaraqi', 'User', 'Yes'),
('qwer', '$2y$10$Wn.O0VQgmLHu/M4fPtBht.CeteJq5lgvOMQJdIL54ls1c.YrH9/fW', 'qwertyuiop', 'User', 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial_number` (`serial_number`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`serial_number`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`serial_number`) REFERENCES `devices` (`serial_number`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
