-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Nov 2024 pada 01.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Semua'),
(2, 'Pendidikan'),
(3, 'Fasilitas Umum'),
(4, 'Tempat Wisata'),
(5, 'Potensi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `image_url`, `name`, `deskripsi`, `latitude`, `longitude`, `kategori_id`) VALUES
(41, 'uploads/85e92935-914a-43f5-bd10-2c7579b4e4a8.jpeg', 'jakarta monas', NULL, -6.17543386, 106.82732105, NULL),
(43, 'uploads/Focalors_Furina.jpeg', 'Kota Surabaya', NULL, -7.24410707, 112.74118423, NULL),
(50, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'tanggul', NULL, -8.16249021, 113.45229149, NULL),
(60, '', 'aa', 'aa4x\r\n', -8.07851322, 113.72789383, NULL),
(61, '', 'a', 'a', -8.06695592, 113.70883942, NULL),
(62, '', 'a', '', -8.09601851, 113.70506287, NULL),
(63, '', 'a', 'a', -8.11777158, 113.69459152, NULL),
(65, 'uploads/Business Model Whiteboard (1).png', 'a', 'a', -8.12096151, 113.73398781, NULL),
(66, '', 'a', 'a2', -8.07910099, 113.65695171, NULL),
(67, '', 'a2', '', -8.07910099, 113.65695171, NULL),
(68, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'aaaa', 'aaaa', -8.11052777, 114.13474017, NULL),
(69, 'uploads/genshin-impact-furina-art-hd-wallpaper-uhdpaper.com-8@1@m.jpg', 'aaaa', 'aaaa', -8.11052777, 114.13474017, NULL),
(71, 'uploads/Karina aespa.jpeg', 'Rama Ganteng', 'Tempat Rama Mandi', -8.54506863, 113.60127429, 2),
(72, 'url_gambar_pantai_papuma', 'Pantai Papuma', 'Deskripsi Pantai Papuma', -8.44210000, 113.77630000, NULL),
(73, 'url_gambar_teluk_love', 'Teluk Love', 'Deskripsi Teluk Love', -8.42430000, 113.78120000, NULL),
(74, 'url_gambar_pantai_puger', 'Pantai Puger', 'Deskripsi Pantai Puger', -8.43230000, 113.81510000, NULL),
(75, '', 'Gunung Argopuro', '', -7.96723456, 113.57254028, NULL),
(77, '', 'Perhutani', '', -8.30210584, 113.89088631, NULL),
(79, '', 'Gunung Manggar', '', -8.32214910, 113.59090805, NULL),
(81, '', 'Gumuk Wuluan', '', -7.99157339, 113.91663551, NULL),
(83, '', 'Darungan', 'Rumahnya Ari', -8.25309747, 113.83698463, 4),
(85, '', '', NULL, 0.00000000, 0.00000000, NULL),
(86, '', '', NULL, 0.00000000, 0.00000000, NULL),
(87, '', '', NULL, 0.00000000, 0.00000000, NULL),
(88, '', '', NULL, 0.00000000, 0.00000000, NULL),
(89, '', '', NULL, 0.00000000, 0.00000000, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `constraint1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
