-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 06:40 AM
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
-- Database: `bkk_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_pendaftar` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pendaftar`, `tanggal`, `jumlah`, `bukti`, `status`) VALUES
(1, 1, '2025-12-02 09:22:51', 20000000, 'bukti_1_1764642171.jpg', 'approved'),
(2, 2, '2025-12-02 10:26:04', 120000, 'bukti_2_1764645964.png', 'approved'),
(3, 3, '2025-12-02 11:01:02', 100000, 'bukti_3_1764648062.png', 'approved'),
(4, 4, '2025-12-02 11:33:22', 12445, 'bukti_4_1764650002.png', 'approved'),
(5, 7, '2025-12-02 12:38:10', 12113, 'bukti_7_1764653890.jpg', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nkk` varchar(20) DEFAULT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `pengalaman` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `nama`, `nkk`, `jenis_kelamin`, `asal_sekolah`, `email`, `no_hp`, `pengalaman`, `alamat`, `password`) VALUES
(1, 'Muhammad Rama Raditya', '1234567819', 'Laki-laki', 'SMK PGRI 2 PONOROGO', 'm.rama43710@gmail.com', '083131130557', 'Belum Ada', 'JL sukimay', '$2y$10$jU5DE9/0aJ3RDq3mcsAA1OQvpymy6x9S1YHjXtt2daVQcfGjbFcI6'),
(2, 'bahlul', '1234567818', 'Laki-laki', 'SMK DANYANG', 'baim@mail.com', '087846458904', '1-3 Tahun', 'Danyang sukosari', '$2y$10$rljnOn64V2Y9oD.eTHAkt.JT0aEjDoUYY9RrXU7N5F5DzJv8izIxm'),
(3, 'Muhammad Rama Raditya', '1234567816', 'Laki-laki', 'SMK PGRI 2 PONOROGO', 'rama@mail.com', '083131130557', '< 1 Tahun', 'Jl. Imam Bonjol', '$2y$10$lNi0PY9aipD8pGTgTXU/OeOfd3f1eBvUOxGBtMc4ypG5bolZ5pG06'),
(4, 'ramadx', '12345678190', 'Laki-laki', 'SMK PGRI 2 PONOROGO', 'ram@mail.com', '083131130557', '> 3 Tahun', 'JL. AIDNA', '$2y$10$3YlCSqdnZP/bP1kK9WoEpeQ1WBiqZnyUue5k3E7nOCaGJpf8EcVNy'),
(5, 'Muhammad Rama Raditya', '12345678166', 'Laki-laki', 'SMK PGRI 2 PONOROGO', 'ra@mail.com', '083131130557', '< 1 Tahun', 'JL. ADKS', '$2y$10$cpp7Wuyf9HcMIDJjDgtMnuGO3oUYoaam3mRLAHruuZrc0J2QilpJC'),
(6, 'bahlul', '123456781900', 'Laki-laki', 'SMK DANYANG', 'bah@mail.com', '087846458904', 'Belum Ada', 'wsq', '$2y$10$lGADBvsgXozOci4leHAr8uuzL2HsGdBibnUXAtRFgH9DoRpj6yNZW'),
(7, 'Muhammad Rama Raditya', '12345678191', 'Laki-laki', 'SMK PGRI 2 PONOROGO', 'radit@mail.com', '083131130557', '< 1 Tahun', 'JL. RANDU ALAS', '$2y$10$oMUDRHf1Bp6CpgC/omwTROPLnRfHwwqD7prbATtTeq.Alv/wMZtqS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indexes for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
