-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 03:04 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leaflet`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `image_url`, `name`, `deskripsi`, `latitude`, `longitude`) VALUES
(41, 'uploads/85e92935-914a-43f5-bd10-2c7579b4e4a8.jpeg', 'jakarta monas', NULL, '-6.17543386', '106.82732105'),
(43, 'uploads/Focalors_Furina.jpeg', 'Kota Surabaya', NULL, '-7.24410707', '112.74118423'),
(50, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'tanggul', NULL, '-8.16249021', '113.45229149'),
(60, '', 'aa', 'aa4x\r\n', '-8.07851322', '113.72789383'),
(61, '', 'a', 'a', '-8.06695592', '113.70883942'),
(62, '', 'a', '', '-8.09601851', '113.70506287'),
(63, '', 'a', 'a', '-8.11777158', '113.69459152'),
(65, 'uploads/Business Model Whiteboard (1).png', 'a', 'a', '-8.12096151', '113.73398781'),
(66, '', 'a', 'a2', '-8.07910099', '113.65695171'),
(67, '', 'a2', '', '-8.07910099', '113.65695171'),
(68, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'aaaa', 'aaaa', '-8.11052777', '114.13474017'),
(69, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'aaaa', 'aaaa', '-8.11052777', '114.13474017'),
(71, 'uploads/Karina aespa.jpeg', 'Rama Ganteng', 'Tempat Rama Mandi', '-8.54506863', '113.60127429'),
(72, 'url_gambar_pantai_papuma', 'Pantai Papuma', 'Deskripsi Pantai Papuma', '-8.44210000', '113.77630000'),
(73, 'url_gambar_teluk_love', 'Teluk Love', 'Deskripsi Teluk Love', '-8.42430000', '113.78120000'),
(74, 'url_gambar_pantai_puger', 'Pantai Puger', 'Deskripsi Pantai Puger', '-8.43230000', '113.81510000'),
(75, '', 'Gunung Argopuro', '', '-7.96723456', '113.57254028'),
(77, '', 'Perhutani', '', '-8.30210584', '113.89088631'),
(79, '', 'Gunung Manggar', '', '-8.32214910', '113.59090805'),
(81, '', 'Gumuk Wuluan', '', '-7.99157339', '113.91663551'),
(83, '', 'Darungan', 'Rumahnya Ari', '-8.25309747', '113.83698463');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
