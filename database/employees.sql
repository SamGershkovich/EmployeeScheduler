-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2021 at 02:48 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `000801766_`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_number` int(5) NOT NULL,
  `password` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `department` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_number`, `password`, `name`, `position`, `department`) VALUES
(1, 0, '', 'Admin', 1, 'Produce'),
(19, 11111, '', 'Kory Kelly', 2, 'Produce'),
(20, 22222, '', 'Ryan Gibson', 3, 'Produce'),
(21, 33333, '', 'Rob Bili', 5, 'Produce'),
(22, 44444, '', 'Pat Lee', 5, 'Produce'),
(23, 55555, '', 'Sam Robinson', 4, 'Produce'),
(24, 66666, '', 'Shawn Blaylock', 5, 'Produce'),
(25, 77777, '', 'Joel Hagen', 5, 'Produce'),
(26, 88888, '', 'Jim Panganiban', 3, 'Produce'),
(29, 99999, '', 'Drew-Erin Juette', 5, 'Produce'),
(30, 10101, '', 'Justin Notarianni', 5, 'Produce'),
(32, 12121, '', 'Peter Mancini', 2, 'Chef'),
(33, 13131, '', 'Johnathan Perelli', 2, 'Bakery'),
(34, 14141, '', 'Paul Newbor', 2, 'Meat & Fish'),
(35, 15151, '', 'Andrea McCarthy', 2, 'Deli & Cheese'),
(36, 16161, '', 'Margaret Holmes', 2, 'Service'),
(37, 17171, '', 'Jakub Pace', 5, 'Chef'),
(38, 19191, '', 'Luca Hatfield', 5, 'Chef'),
(39, 20000, '', 'Josephine Faulkner', 3, 'Chef'),
(40, 21212, '', 'Lennie Villanueva', 3, 'Chef'),
(41, 23232, '', 'Jesse Ferreira', 4, 'Chef'),
(42, 24242, '', 'Samantha Kline', 4, 'Chef'),
(43, 25252, '', 'Jane Nash', 3, 'Bakery'),
(44, 26262, '', 'Darlene Croft', 3, 'Bakery'),
(45, 27272, '', 'Ellesha Oliver', 4, 'Bakery'),
(46, 28282, '', 'Caroline Fitzgerald', 4, 'Bakery'),
(47, 29292, '', 'Krisha Bonner', 5, 'Bakery'),
(48, 30000, '', 'Carmen Medina', 5, 'Bakery'),
(49, 31313, '', 'Bryson Hume', 5, 'Bakery'),
(50, 32323, '', 'Payton Shelton', 4, 'Bakery'),
(51, 34343, '', 'Sky Valdez', 5, 'Bakery'),
(52, 36363, '', 'Dakota Higgins', 5, 'Bakery'),
(53, 37373, '', 'Warren Garza', 5, 'Bakery'),
(54, 37373, '', 'Arun Turnbull', 5, 'Bakery'),
(55, 38383, '', 'Barnaby Tate', 5, 'Chef'),
(56, 39393, '', 'Katelin Everett', 5, 'Bakery'),
(57, 40000, '', 'Oliver Bouvet', 3, 'Meat & Fish'),
(58, 41414, '', 'Fynn Wallis', 3, 'Meat & Fish'),
(59, 42424, '', 'Ember Finley', 4, 'Meat & Fish'),
(60, 43434, '', 'Kingston Rahman', 4, 'Meat & Fish'),
(61, 45454, '', 'Khushi Connelly', 5, 'Meat & Fish'),
(62, 46464, '', 'Mikael Xiong', 5, 'Meat & Fish'),
(63, 47474, '', 'Federico Sharp', 5, 'Meat & Fish'),
(64, 48484, '', 'Coby Farrington', 5, 'Meat & Fish'),
(65, 49494, '', 'Dollie Markham', 5, 'Meat & Fish'),
(66, 50000, '', 'Carolyn Delaney', 3, 'Deli & Cheese'),
(67, 51515, '', 'Devante Lyon', 3, 'Deli & Cheese'),
(68, 52525, '', 'Farhaan Oneill', 3, 'Deli & Cheese'),
(69, 53535, '', 'Ethel Sloan', 4, 'Deli & Cheese'),
(70, 54545, '', 'Priyanka Bradshaw', 4, 'Deli & Cheese'),
(71, 56565, '', 'Arlene Dunkley', 5, 'Deli & Cheese'),
(72, 57575, '', 'Amalia Strong', 5, 'Deli & Cheese'),
(73, 58585, '', 'Kaden Lucero', 5, 'Deli & Cheese'),
(74, 59595, '', 'Sanjeev Kumar', 3, 'Service'),
(75, 60000, '', 'Roscoe Aldred', 3, 'Service'),
(76, 61616, '', 'Enrico Crane', 4, 'Service'),
(77, 62626, '', 'Bruno Aguilar', 4, 'Service'),
(78, 63636, '', 'Julian Mckeown', 5, 'Service'),
(79, 64646, '', 'Kendra Becker', 5, 'Service'),
(80, 65656, '', 'Emaan Reynolds', 5, 'Service'),
(81, 67676, '', 'Ingrid Hughes', 5, 'Service'),
(82, 68686, '', 'Jakub Wright', 5, 'Service'),
(83, 69696, '', 'Thalia Gregory', 5, 'Service'),
(84, 70000, '', 'Katarina Spooner', 5, 'Service');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
