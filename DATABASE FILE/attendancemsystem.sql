-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2023 at 08:47 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblacademic`
--

CREATE TABLE `tblacademic` (
  `id` int(40) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `semesterId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `StartDate` varchar(40) NOT NULL,
  `EndDate` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblacademic`
--

INSERT INTO `tblacademic` (`id`, `sessionName`, `semesterId`, `isActive`, `dateCreated`, `StartDate`, `EndDate`) VALUES
(2, '2017/2018', '2', 'InActive', '2023-06-21', ' ', ' '),
(1, '2017/2018', '1', 'InActive', '2023-06-21', ' ', ' '),
(3, '2018/2019', '1', 'Active', '2023-06-21', '2023-08-02', ' '),
(4, '2018/2019', '2', 'InActive', '2023-06-21', ' ', ' ');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `username`, `password`) VALUES
(1, 'vinoj', 'FAS', 'FasAdmin_1', '$2y$10$.W8RrxIZzITp3mXpFVGNQufYgR40KZux8rEO3svCQLvYfWL5mOxxu');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL,
  `Academic_year` varchar(40) NOT NULL,
  `Year` varchar(30) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `Cid` int(40) NOT NULL,
  `Lid` int(40) NOT NULL,
  `RegNo` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dateTaken` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `Academic_year`, `Year`, `semester`, `Cid`, `Lid`, `RegNo`, `status`, `dateTaken`) VALUES
(271, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0001', '1', '2023-08-03'),
(270, '2018/2019', '1', '2', 3, 1, 'SEU18FAS0003', '0', '2023-08-03'),
(269, '2018/2019', '1', '2', 3, 1, 'SEU18FAS0001', '0', '2023-08-03'),
(268, '2018/2019', '1', '1', 3, 1, 'SEU18FAS0004', '1', '2023-08-03'),
(267, '2018/2019', '1', '1', 3, 1, 'SEU18FAS0002', '1', '2023-08-03'),
(266, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0004', '1', '2023-08-02'),
(265, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0003', '1', '2023-08-02'),
(264, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0002', '1', '2023-08-02'),
(263, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0001', '1', '2023-08-02'),
(272, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0002', '1', '2023-08-03'),
(273, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0003', '1', '2023-08-03'),
(274, '2018/2019', '2', '1', 3, 1, 'SEU18FAS0004', '1', '2023-08-03');

-- --------------------------------------------------------

--
-- Table structure for table `tblcoursetype`
--

CREATE TABLE `tblcoursetype` (
  `id` int(11) NOT NULL,
  `Type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcoursetype`
--

INSERT INTO `tblcoursetype` (`id`, `Type`) VALUES
(1, 'Main'),
(2, 'Compulsary'),
(3, 'Elective'),
(4, 'Auxiliary');

-- --------------------------------------------------------

--
-- Table structure for table `tblfiles`
--

CREATE TABLE `tblfiles` (
  `id` int(11) NOT NULL,
  `Title` varchar(300) NOT NULL,
  `Name` varchar(1000) NOT NULL,
  `Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfiles`
--

INSERT INTO `tblfiles` (`id`, `Title`, `Name`, `Type`) VALUES
(1, 'Students', 'Students.csv', 'application/vnd.ms-excel'),
(2, 'Lecturer', 'Lecturer.csv', 'application/vnd.ms-excel'),
(3, 'Course', 'Course.csv', 'application/vnd.ms-excel');

-- --------------------------------------------------------

--
-- Table structure for table `tblhoursremain`
--

CREATE TABLE `tblhoursremain` (
  `Id` int(11) NOT NULL,
  `courseId` int(40) NOT NULL,
  `RemainHours` int(20) NOT NULL,
  `Lid` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhoursremain`
--

INSERT INTO `tblhoursremain` (`Id`, `courseId`, `RemainHours`, `Lid`) VALUES
(1, 1, 30, 3),
(2, 3, 23, 1),
(3, 4, 30, 3),
(4, 5, 30, 4),
(5, 7, 45, 3),
(6, 8, 45, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbllectureenroll`
--

CREATE TABLE `tbllectureenroll` (
  `id` int(40) NOT NULL,
  `Cid` int(10) NOT NULL,
  `Lid` int(10) NOT NULL,
  `EnrollKey` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbllectureenroll`
--

INSERT INTO `tbllectureenroll` (`id`, `Cid`, `Lid`, `EnrollKey`) VALUES
(81, 2, 1, 'CSM12032'),
(82, 6, 2, 'CHS32083'),
(83, 4, 3, 'CSM31022'),
(84, 7, 3, 'CSS41033'),
(85, 5, 4, 'CSE11042'),
(86, 5, 4, 'CSE11042'),
(87, 8, 1, 'CSS41083'),
(88, 1, 3, 'CSM11022'),
(89, 3, 1, 'CSM21012');

-- --------------------------------------------------------

--
-- Table structure for table `tbllecturer`
--

CREATE TABLE `tbllecturer` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `Nicno` varchar(30) NOT NULL,
  `Title` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbllecturer`
--

INSERT INTO `tbllecturer` (`Id`, `firstName`, `lastName`, `emailAddress`, `username`, `password`, `phoneNo`, `dateCreated`, `Nicno`, `Title`) VALUES
(3, 'Elon', 'Reeve Musk', 'elonmusk@tesla.com', 'elonmusk@tesla.com', '$2y$10$/NbjStcZEZdnhFhdKGCF/uLDgNkEzDMoUI8ZW5FEx.TYExcRFEp9C', '54782135', '2023-06-27', '7854125631', 'Dr'),
(2, 'Albert', 'Einstein', 'prof_albert@icloud.com', 'prof_albert@icloud.com', '$2y$10$W0lvpfPt./ZXs1F7VEO/TO6sVSGrs2I.8Z0bojSdQfE4Z4Ez1Nr5G', '85479625', '2023-06-27', '548796541', 'Prof'),
(1, 'Steven', 'Paul Jobs', 'SteveJobs@icloud.com', 'SteveJobs@icloud.com', '$2y$10$xRUn1FCIp6tKhCGzKcpiX.zhXqSXMZciKQqhpmWBZcdk0WfuzNfO.', '785421358', '2023-06-27', '558742561', 'Prof'),
(4, 'Mark', 'Elliot Zuckerberg', 'Markmama@facebook.com', 'Markmama@facebook.com', '$2y$10$05DIIWWLykM0Je68nb7rxOViWumXXBINYmpLXF3m9aKODIHIQ.yOC', '45214852', '2023-06-27', '548752131', 'Dr');

-- --------------------------------------------------------

--
-- Table structure for table `tblprofile`
--

CREATE TABLE `tblprofile` (
  `Student` varchar(40) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblprofile`
--

INSERT INTO `tblprofile` (`Student`, `Name`) VALUES
('SEU17FAS0001', 'SEU17FAS0001.png'),
('SEU18FAS0001', 'SEU18FAS0001.JPG'),
('SEU18FAS0002', 'SEU18FAS0002.JPG'),
('SEU18FAS0003', 'SEU18FAS0003.jpg'),
('SEU18FAS0004', 'SEU18FAS0004.jpg'),
('SEU19FAS0001', 'SEU19FAS0001.jpg'),
('SEU19FAS0002', 'SEU19FAS0002.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblsemester`
--

CREATE TABLE `tblsemester` (
  `Id` int(10) NOT NULL,
  `SemesterName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsemester`
--

INSERT INTO `tblsemester` (`Id`, `SemesterName`) VALUES
(1, '1st Semester'),
(2, '2nd Semester');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudentenroll`
--

CREATE TABLE `tblstudentenroll` (
  `id` int(100) NOT NULL,
  `Cid` int(40) NOT NULL,
  `SRegNumber` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudentenroll`
--

INSERT INTO `tblstudentenroll` (`id`, `Cid`, `SRegNumber`) VALUES
(42, 3, 'SEU18FAS0001'),
(43, 3, 'SEU18FAS0002'),
(44, 3, 'SEU18FAS0003'),
(45, 3, 'SEU18FAS0004');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `id` int(40) NOT NULL,
  `Title` varchar(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `Academic_year` varchar(40) NOT NULL,
  `RegNumber` varchar(255) NOT NULL,
  `Nicno` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `CourseEnrollStatus` int(10) NOT NULL,
  `email` varchar(40) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`id`, `Title`, `firstName`, `lastName`, `Academic_year`, `RegNumber`, `Nicno`, `username`, `password`, `dateCreated`, `CourseEnrollStatus`, `email`, `status`) VALUES
(1, 'Mr', 'Kumar', 'Sangakkara', '2018/2019', 'SEU18FAS0001', '785496457V', 'SEU18FAS0001', '$2y$10$TCmmDJu57DnhLzlcE0SZCudLHt.OohjrlDFMdHiUTn0pI2vK9Tqfm', '2023-06-28', 0, 'ks1978@outlook.com', 'Active'),
(2, 'Mr', 'TM', 'Dilshan', '2018/2019', 'SEU18FAS0002', '997854216V', 'SEU18FAS0002', '$2y$10$SPKJlfl2PQEnxTTNri3FyuM1pHpogTnBCJN/09gCDSaduNjmdljSO', '2023-06-28', 0, 'dilshan99@yahoo.com', 'Active'),
(3, 'Miss', 'Sampath', 'Angelina', '2018/2019', 'SEU18FAS0003', '995487215V', 'SEU18FAS0003', '$2y$10$S5JTH7A.hQnv3Cz4vaq1juVxlgH0CBj7qpK1yrHKPc490Gcc/gaia', '2023-06-28', 0, 'angelina99@outlook.com', 'Active'),
(4, 'Miss', 'Kamal', 'Diya', '2018/2019', 'SEU18FAS0004', '996587410V', 'SEU18FAS0004', '$2y$10$weyHUDDJQvrpPmyU5tAHfOqEg8s8R/bbVp.qkKlAiSspX2NX8HoqW', '2023-06-28', 0, 'diya99@gmail.com', 'Active'),
(1, 'Mrs', 'Nayan', 'Praphu', '2019/2020', 'SEU19FAS0001', '200043299', 'SEU19FAS0001', '$2y$10$pwzzo/.B3kxQ3wZNx5tQb.dNxqtiOhbkip2.4Jhr13QL3GMZXZxVG', '2023-06-28', 0, 'praphu20@icloud.com', 'Active'),
(2, 'Mr', 'Mahela', 'Vikram', '2019/2020', 'SEU19FAS0002', '998754624V', 'SEU19FAS0002', '$2y$10$eTrLrafSPSnLmOX4gt58pe4wV1rohYci7KP6TyTg5zBBWrFdHtAb6', '2023-06-28', 0, 'vikram99@yahoo.com', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubject`
--

CREATE TABLE `tblsubject` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Type` varchar(100) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `year` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `isAssigned` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `DateAdded` varchar(40) NOT NULL,
  `NoofHours` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubject`
--

INSERT INTO `tblsubject` (`id`, `code`, `Name`, `Type`, `SubjectName`, `year`, `semester`, `isAssigned`, `DateAdded`, `NoofHours`) VALUES
(1, 'CSM11022', 'Fundamentals Of Programming Languages', 'Main', 'Computer Science', '1', '1', '1', '2023-06-28', 30),
(2, 'CSM12032', 'OOP,Analysis and design', 'Main', 'Computer Science', '1', '2', '1', '2023-06-28', 30),
(3, 'CSM21012', 'Operating  System', 'Main', 'Computer Science', '2', '1', '1', '2023-06-28', 30),
(4, 'CSM31022', 'DATABASE', 'Main', 'Computer Science', '3', '1', '1', '2023-06-28', 30),
(5, 'CSE11042', 'Visual Basis', 'Elective', 'Other', '1', '1', '1', '2023-06-28', 30),
(6, 'CHS32083', 'Advanced Topics in Physical chemistry', 'Main', 'Chemistry', '3', '2', '1', '2023-06-28', 45),
(7, 'CSS41033', 'Advanced Database', 'Main', 'Computer Science', '4', '1', '1', '2023-06-28', 45),
(8, 'CSS41083', 'Information Theory, Coding and Cryptography', 'Main', 'Computer Science', '4', '1', '1', '2023-06-28', 45);

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjectname`
--

CREATE TABLE `tblsubjectname` (
  `Id` int(40) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubjectname`
--

INSERT INTO `tblsubjectname` (`Id`, `Name`) VALUES
(1, 'Mathematics'),
(2, 'Computer Science'),
(3, 'Biology'),
(4, 'Physics'),
(5, 'Chemistry'),
(6, 'Applied Statistics'),
(7, 'Earth Science'),
(8, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `tblyear`
--

CREATE TABLE `tblyear` (
  `Id` int(11) NOT NULL,
  `Year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblyear`
--

INSERT INTO `tblyear` (`Id`, `Year`) VALUES
(1, '1st year'),
(2, '2nd Year'),
(3, '3rd Year'),
(4, '4th Year'),
(5, '5th Year');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblacademic`
--
ALTER TABLE `tblacademic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblcoursetype`
--
ALTER TABLE `tblcoursetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblfiles`
--
ALTER TABLE `tblfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblhoursremain`
--
ALTER TABLE `tblhoursremain`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbllectureenroll`
--
ALTER TABLE `tbllectureenroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbllecturer`
--
ALTER TABLE `tbllecturer`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblprofile`
--
ALTER TABLE `tblprofile`
  ADD PRIMARY KEY (`Student`);

--
-- Indexes for table `tblsemester`
--
ALTER TABLE `tblsemester`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudentenroll`
--
ALTER TABLE `tblstudentenroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`RegNumber`) USING BTREE;

--
-- Indexes for table `tblsubject`
--
ALTER TABLE `tblsubject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsubjectname`
--
ALTER TABLE `tblsubjectname`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblyear`
--
ALTER TABLE `tblyear`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblacademic`
--
ALTER TABLE `tblacademic`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `tblfiles`
--
ALTER TABLE `tblfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblhoursremain`
--
ALTER TABLE `tblhoursremain`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbllectureenroll`
--
ALTER TABLE `tbllectureenroll`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbllecturer`
--
ALTER TABLE `tbllecturer`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tblsemester`
--
ALTER TABLE `tblsemester`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudentenroll`
--
ALTER TABLE `tblstudentenroll`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tblsubject`
--
ALTER TABLE `tblsubject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblsubjectname`
--
ALTER TABLE `tblsubjectname`
  MODIFY `Id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
