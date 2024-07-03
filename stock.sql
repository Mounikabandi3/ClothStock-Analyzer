-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2024 at 07:19 PM
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
-- Database: `stock`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetItemsByCategory` (IN `category` VARCHAR(50))   BEGIN
    DECLARE tableName VARCHAR(100);

    -- Set the table name based on the category
    SET tableName = category;

    -- Prepare and execute the SQL statement using the determined table name
    SET @sql = CONCAT('SELECT * FROM ', tableName);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetItemsBySectionAndCategory` (IN `section` VARCHAR(50), IN `category` VARCHAR(50))   BEGIN
    SELECT * FROM section WHERE category = category;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_random_id` () RETURNS VARCHAR(36) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    DECLARE random_uuid VARCHAR(36);
    SET random_uuid = UUID();
    RETURN SUBSTRING(random_uuid, 1, 8); -- Adjust length as needed
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `generate_unique_id` () RETURNS INT(11)  BEGIN
    DECLARE new_id INT;
    DECLARE found INT DEFAULT 1;

    WHILE found = 1 DO
        SET new_id = FLOOR(RAND() * 1000000); -- Generates a random number between 0 and 999999
        SET found = (SELECT COUNT(*) FROM shirts WHERE id = new_id);
    END WHILE;

    RETURN new_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dresses`
--

CREATE TABLE `dresses` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `password`) VALUES
(1, 'mounika', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `men_pants`
--

CREATE TABLE `men_pants` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `men_shirts`
--

CREATE TABLE `men_shirts` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `men_trousers`
--

CREATE TABLE `men_trousers` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `men_t_shirts`
--

CREATE TABLE `men_t_shirts` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pants`
--

CREATE TABLE `pants` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shirts`
--

CREATE TABLE `shirts` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shirts`
--

INSERT INTO `shirts` (`id`, `cost_price`, `colour`, `selling_price`, `profit`, `image`, `size`) VALUES
(1, 1000.00, 'hhh', 0.00, 0.00, 'logo2.jpeg', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `skirts`
--

CREATE TABLE `skirts` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `track_pants`
--

CREATE TABLE `track_pants` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_shirts`
--

CREATE TABLE `t_shirts` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `women_blouses`
--

CREATE TABLE `women_blouses` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `women_dresses`
--

CREATE TABLE `women_dresses` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `women_jeans`
--

CREATE TABLE `women_jeans` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `women_leggings`
--

CREATE TABLE `women_leggings` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `women_sarees`
--

CREATE TABLE `women_sarees` (
  `id` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dresses`
--
ALTER TABLE `dresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `men_pants`
--
ALTER TABLE `men_pants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `men_shirts`
--
ALTER TABLE `men_shirts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `men_trousers`
--
ALTER TABLE `men_trousers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `men_t_shirts`
--
ALTER TABLE `men_t_shirts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pants`
--
ALTER TABLE `pants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shirts`
--
ALTER TABLE `shirts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skirts`
--
ALTER TABLE `skirts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `track_pants`
--
ALTER TABLE `track_pants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_shirts`
--
ALTER TABLE `t_shirts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `women_blouses`
--
ALTER TABLE `women_blouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `women_dresses`
--
ALTER TABLE `women_dresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `women_jeans`
--
ALTER TABLE `women_jeans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `women_leggings`
--
ALTER TABLE `women_leggings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `women_sarees`
--
ALTER TABLE `women_sarees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dresses`
--
ALTER TABLE `dresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `men_pants`
--
ALTER TABLE `men_pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `men_shirts`
--
ALTER TABLE `men_shirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `men_trousers`
--
ALTER TABLE `men_trousers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `men_t_shirts`
--
ALTER TABLE `men_t_shirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pants`
--
ALTER TABLE `pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shirts`
--
ALTER TABLE `shirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skirts`
--
ALTER TABLE `skirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `track_pants`
--
ALTER TABLE `track_pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_shirts`
--
ALTER TABLE `t_shirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `women_blouses`
--
ALTER TABLE `women_blouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `women_dresses`
--
ALTER TABLE `women_dresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `women_jeans`
--
ALTER TABLE `women_jeans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `women_leggings`
--
ALTER TABLE `women_leggings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `women_sarees`
--
ALTER TABLE `women_sarees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
