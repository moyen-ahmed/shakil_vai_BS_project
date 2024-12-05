-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 03:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `Bus_Id` int(11) NOT NULL,
  `Bus_number` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`Bus_Id`, `Bus_number`, `capacity`) VALUES
(2, '11212', 222222);

-- --------------------------------------------------------

--
-- Table structure for table `bus_station`
--

CREATE TABLE `bus_station` (
  `Station_Id` int(11) NOT NULL,
  `Station_name` varchar(100) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Contact_Info` varchar(100) DEFAULT NULL,
  `Operating_Hours` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_station`
--

INSERT INTO `bus_station` (`Station_Id`, `Station_name`, `Location`, `Contact_Info`, `Operating_Hours`) VALUES
(1, 'dhka to bsl', 'nhk', '0173442362456', '12');

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `Counter_Id` int(11) NOT NULL,
  `Counter_Name` varchar(50) NOT NULL,
  `Operating_Hours` varchar(20) DEFAULT NULL,
  `Tickets_Sold` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`Counter_Id`, `Counter_Name`, `Operating_Hours`, `Tickets_Sold`) VALUES
(1, 'Noakhali-1', '4.00 AM- 12.00 PM', 5);

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `Driver_Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `License_Number` varchar(20) NOT NULL,
  `Experience` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`Driver_Id`, `Name`, `License_Number`, `Experience`) VALUES
(1, 'Abbas', '1234567890', 4),
(2, 'kasem', '1231231241', 12),
(3, 'kasemer rt', '1231231241', 234);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `password` varchar(123) NOT NULL,
  `Role` enum('Driver','Manager','Bus_Supervisior') NOT NULL,
  `Contact_Info` varchar(100) NOT NULL,
  `Shift_Schedule` varchar(50) NOT NULL,
  `Station_Id` int(11) NOT NULL,
  `ppic` varchar(233) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Employee_Id`, `Name`, `password`, `Role`, `Contact_Info`, `Shift_Schedule`, `Station_Id`, `ppic`) VALUES
(1, 'mo', '1234', 'Driver', '1234', '1234', 1, ''),
(3, 'Shakil Ahmed', '1234', 'Driver', '123456', '12', 1, 'uploads/409755786_3619641525020608_69526215536074079_n.jpg'),
(4, 'Mr. Sajeeb', '1234', 'Manager', '019224455', '8 AM -3 PM', 1, 'uploads/kuuta.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `Feedback_Id` int(11) NOT NULL,
  `Bus_Id` int(11) NOT NULL,
  `Driver_Id` int(11) NOT NULL,
  `Feedback_Text` text NOT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` between 1 and 5),
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`Feedback_Id`, `Bus_Id`, `Driver_Id`, `Feedback_Text`, `Rating`, `Date`) VALUES
(1, 2, 2, 'nice', 2, '2024-12-14'),
(2, 2, 1, 'nice', 2, '2024-12-21'),
(3, 2, 1, 'nice', 2, '2024-12-21'),
(4, 2, 3, 'good', 4, '2024-12-28'),
(5, 2, 2, 'good', 2, '2025-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_log`
--

CREATE TABLE `fuel_log` (
  `Fuel_Log_Id` int(11) NOT NULL,
  `Bus_Id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Fuel_Amount` decimal(10,2) NOT NULL,
  `Cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuel_log`
--

INSERT INTO `fuel_log` (`Fuel_Log_Id`, `Bus_Id`, `Date`, `Fuel_Amount`, `Cost`) VALUES
(1, 2, '2024-12-12', 4.00, 7.00),
(2, 2, '2024-12-12', 4.00, 7.00);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_record`
--

CREATE TABLE `maintenance_record` (
  `Maintenance_Id` int(11) NOT NULL,
  `Bus_Id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Issue_Description` text NOT NULL,
  `Resolution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `Route_Id` int(11) NOT NULL,
  `Route_Name` varchar(100) NOT NULL,
  `Total_Distance` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`Route_Id`, `Route_Name`, `Total_Distance`) VALUES
(2, 'dhaka-india', 1.00),
(3, 'ctg-nkh', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `Schedule_Id` int(11) NOT NULL,
  `Bus_Id` int(11) DEFAULT NULL,
  `Route_Id` int(11) DEFAULT NULL,
  `Departure_Time` time DEFAULT NULL,
  `Arrival_Time` time DEFAULT NULL,
  `Driver_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_Id`, `Bus_Id`, `Route_Id`, `Departure_Time`, `Arrival_Time`, `Driver_Id`) VALUES
(5, 2, 2, '15:22:00', '13:22:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`Bus_Id`);

--
-- Indexes for table `bus_station`
--
ALTER TABLE `bus_station`
  ADD PRIMARY KEY (`Station_Id`);

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`Counter_Id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`Driver_Id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_Id`),
  ADD KEY `Station_Id` (`Station_Id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`Feedback_Id`),
  ADD KEY `Bus_Id` (`Bus_Id`),
  ADD KEY `Driver_Id` (`Driver_Id`);

--
-- Indexes for table `fuel_log`
--
ALTER TABLE `fuel_log`
  ADD PRIMARY KEY (`Fuel_Log_Id`),
  ADD KEY `Bus_Id` (`Bus_Id`);

--
-- Indexes for table `maintenance_record`
--
ALTER TABLE `maintenance_record`
  ADD PRIMARY KEY (`Maintenance_Id`),
  ADD KEY `Bus_Id` (`Bus_Id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`Route_Id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`Schedule_Id`),
  ADD KEY `Bus_Id` (`Bus_Id`),
  ADD KEY `Route_Id` (`Route_Id`),
  ADD KEY `Driver_Id` (`Driver_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `Bus_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bus_station`
--
ALTER TABLE `bus_station`
  MODIFY `Station_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `Counter_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `Driver_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Employee_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `Feedback_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fuel_log`
--
ALTER TABLE `fuel_log`
  MODIFY `Fuel_Log_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `maintenance_record`
--
ALTER TABLE `maintenance_record`
  MODIFY `Maintenance_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `Route_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `Schedule_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`Station_Id`) REFERENCES `bus_station` (`Station_Id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`Bus_Id`) REFERENCES `bus` (`Bus_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`Driver_Id`) REFERENCES `driver` (`Driver_Id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_log`
--
ALTER TABLE `fuel_log`
  ADD CONSTRAINT `fuel_log_ibfk_1` FOREIGN KEY (`Bus_Id`) REFERENCES `bus` (`Bus_Id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_record`
--
ALTER TABLE `maintenance_record`
  ADD CONSTRAINT `maintenance_record_ibfk_1` FOREIGN KEY (`Bus_Id`) REFERENCES `bus` (`Bus_Id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`Bus_Id`) REFERENCES `bus` (`Bus_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`Route_Id`) REFERENCES `route` (`Route_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`Driver_Id`) REFERENCES `driver` (`Driver_Id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
