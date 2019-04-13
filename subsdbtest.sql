-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 13, 2019 at 11:47 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `subsdbtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(10) NOT NULL,
  `seller_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(64) NOT NULL,
  `edition` varchar(20) NOT NULL,
  `year_published` smallint(4) NOT NULL,
  `publisher` varchar(64) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `book_condition` varchar(10) NOT NULL,
  `details` text NOT NULL,
  `photo` varchar(100) NOT NULL,
  `location` varchar(64) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_published` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `seller_id`, `title`, `author`, `edition`, `year_published`, `publisher`, `category`, `price`, `book_condition`, `details`, `photo`, `location`, `date_created`, `date_published`) VALUES
(1, 28, 'Pride and Prejudice', 'Jane Austen', '', 1970, '', 'Fiction', '200', 'Old', 'sdsd', '', 'Metro Manila', '2019-04-12 23:07:25', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `userinfo_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `location` varchar(100) NOT NULL,
  `mobile` char(11) NOT NULL,
  `shortbio` text NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `profile_photo` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`userinfo_id`, `user_id`, `location`, `mobile`, `shortbio`, `birthdate`, `gender`, `profile_photo`) VALUES
(1, 27, '', '', '', '0000-00-00', '', ''),
(2, 28, 'Metro Manila', '09062038475', 'dsdsdsd', '1992-09-30', 'male', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `user_type`, `password`, `email`, `first_name`, `last_name`) VALUES
(17, 'svizcara', 'user', '081299403fb28f81875b64f91b2f5db4', 'svizcara@gmail.com', 'Sheryl', 'Vizcara'),
(18, 'sdsds', 'user', '6e3269c0fc47518b2fecefd22314e67a', 'sds@gmail.com', 'sdsds', 'sd'),
(19, 'svizcara2', 'user', 'c91f28b7f2082faeefdd30cac4756152', 'sheryl.vizcara@gmail.com', 'Sheryl', 'Vizcara'),
(20, 'svizcara3', 'user', 'e10551b8e7afe6f13a602f5a0d1a0a33', 'sheryl.vizcara1@gmail.com', 'Sheryl', 'Vizcara'),
(21, 'svizcar1223', 'user', 'a51a4f22846e0a9e24aed2f2ac9d20db', 'svizcara1212@gmail.com', 'sheryl', 'vizcara'),
(22, 'dsdsdsd', 'user', '2f1ce7c650cebcead01ec966567ac794', 'sdsdsds@dwfwf', 'sdsd', 'sd'),
(23, 'svizcara8', 'user', 'cb391e1aea620868bdd4bbfce4e86195', 'sheryl.vizcara2@gmail.com', 'Sheryl', 'Vizcara'),
(24, 'sheryl', 'user', 'aef8f63a92cbd02c424b5e26c2b3b24b', 'sheryl.vizcara3@gmail.com', 'sheryl', 'vizcara'),
(25, 'sd', 'user', 'f61419e3201e5b4ba86c4a13d7b4562a', 'sdsd@gmail.com', 'sdsd', 'sds'),
(26, 'sds', 'user', '35725a916924cff7b6b5c56421adda5a', 'sdsdsdsd@gmail.com', 'sdsd', 'sdsd'),
(27, 'user', 'user', '894a24ea4a8850ced956e1f6d3769bbb', 'user@trial.email', 'user first', 'user last'),
(28, 'user2', 'user', '9f767b0f38cb33d8a6a4b13319c25693', 'user2@test.email', 'user', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`userinfo_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `userinfo_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
