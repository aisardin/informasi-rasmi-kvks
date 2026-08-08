-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2026 at 04:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irkvks`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktiviti`
--

CREATE TABLE `aktiviti` (
  `id` int(11) NOT NULL,
  `nama_aktiviti` varchar(200) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tarikh` date DEFAULT NULL,
  `masa` time DEFAULT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `status` enum('aktif','tamat') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aktiviti_peserta`
--

CREATE TABLE `aktiviti_peserta` (
  `id` int(11) NOT NULL,
  `aktiviti_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `pensyarah_id` int(11) DEFAULT NULL,
  `tajuk` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `fail` varchar(255) DEFAULT NULL,
  `tarikh_cipta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kod_subjek` varchar(30) NOT NULL,
  `nama_kelas` varchar(150) NOT NULL,
  `program` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `sesi` varchar(50) DEFAULT NULL,
  `pensyarah_id` int(11) NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `masa_mula` time DEFAULT NULL,
  `masa_tamat` time DEFAULT NULL,
  `lokasi` varchar(120) DEFAULT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas_pelajar`
--

CREATE TABLE `kelas_pelajar` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `pelajar_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tajuk` varchar(200) DEFAULT NULL,
  `mesej` text DEFAULT NULL,
  `status` enum('baru','dibaca') DEFAULT 'baru',
  `tarikh` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `tajuk` varchar(200) DEFAULT NULL,
  `kandungan` text DEFAULT NULL,
  `sasaran` enum('semua','pelajar','pensyarah') DEFAULT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif',
  `tarikh_cipta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugasan`
--

CREATE TABLE `tugasan` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `tajuk` varchar(200) DEFAULT NULL,
  `penerangan` text DEFAULT NULL,
  `tarikh_mula` date DEFAULT NULL,
  `tarikh_akhir` datetime DEFAULT NULL,
  `status` enum('aktif','tutup') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugasan_pelajar`
--

CREATE TABLE `tugasan_pelajar` (
  `id` int(11) NOT NULL,
  `tugasan_id` int(11) DEFAULT NULL,
  `pelajar_id` int(11) DEFAULT NULL,
  `fail_jawapan` varchar(255) DEFAULT NULL,
  `status` enum('belum_selesai','siap') DEFAULT 'belum_selesai',
  `markah` decimal(5,2) DEFAULT NULL,
  `tarikh_hantar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pensyarah','pelajar') NOT NULL,
  `gambar` varchar(255) DEFAULT 'default.png',
  `telefon` varchar(30) DEFAULT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif',
  `tarikh_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `gambar`, `telefon`, `status`, `tarikh_daftar`) VALUES
(17, 'Administrator IR-KVKS', 'admin@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8Q4f8', 'admin', 'default.png', '03-00000000', 'aktif', '2026-08-08 10:08:56'),
(18, 'Ahmad Fauzi', 'ahmad@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pensyarah', 'default.png', '012-3456789', 'aktif', '2026-08-08 10:08:56'),
(19, 'Siti Nur Aisyah', 'siti@irkvks.edu.my', '$2y$10$YAkNce1nkFhOYsAF8CtUK.tU94wQMhQpqTCHMJfqHqXgK33pQJHRa', 'pelajar', 'default.png', '013-1111111', 'aktif', '2026-08-08 10:08:56'),
(20, 'Muhammad Danish', 'danish@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pelajar', 'default.png', '013-2222222', 'aktif', '2026-08-08 10:08:56'),
(21, 'Nur Aina', 'aina@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pelajar', 'default.png', '013-3333333', 'aktif', '2026-08-08 10:08:56'),
(22, 'Amir Hakim', 'amir@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pelajar', 'default.png', '013-4444444', 'aktif', '2026-08-08 10:08:56'),
(23, 'Nur Syafiqah', 'syafiqah@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pelajar', 'default.png', '013-5555555', 'aktif', '2026-08-08 10:08:56'),
(24, 'Adam Irfan', 'adam@irkvks.edu.my', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8M8Z6f6Qf8Q4f8', 'pelajar', 'default.png', '013-6666666', 'aktif', '2026-08-08 10:08:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktiviti`
--
ALTER TABLE `aktiviti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aktiviti_peserta`
--
ALTER TABLE `aktiviti_peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aktiviti_id` (`aktiviti_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `pensyarah_id` (`pensyarah_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pensyarah_id` (`pensyarah_id`);

--
-- Indexes for table `kelas_pelajar`
--
ALTER TABLE `kelas_pelajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `pelajar_id` (`pelajar_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugasan`
--
ALTER TABLE `tugasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indexes for table `tugasan_pelajar`
--
ALTER TABLE `tugasan_pelajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugasan_id` (`tugasan_id`),
  ADD KEY `pelajar_id` (`pelajar_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktiviti`
--
ALTER TABLE `aktiviti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aktiviti_peserta`
--
ALTER TABLE `aktiviti_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kelas_pelajar`
--
ALTER TABLE `kelas_pelajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugasan`
--
ALTER TABLE `tugasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugasan_pelajar`
--
ALTER TABLE `tugasan_pelajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktiviti_peserta`
--
ALTER TABLE `aktiviti_peserta`
  ADD CONSTRAINT `aktiviti_peserta_ibfk_1` FOREIGN KEY (`aktiviti_id`) REFERENCES `aktiviti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aktiviti_peserta_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bahan`
--
ALTER TABLE `bahan`
  ADD CONSTRAINT `bahan_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bahan_ibfk_2` FOREIGN KEY (`pensyarah_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`pensyarah_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_pelajar`
--
ALTER TABLE `kelas_pelajar`
  ADD CONSTRAINT `kelas_pelajar_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_pelajar_ibfk_2` FOREIGN KEY (`pelajar_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugasan`
--
ALTER TABLE `tugasan`
  ADD CONSTRAINT `tugasan_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugasan_pelajar`
--
ALTER TABLE `tugasan_pelajar`
  ADD CONSTRAINT `tugasan_pelajar_ibfk_1` FOREIGN KEY (`tugasan_id`) REFERENCES `tugasan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tugasan_pelajar_ibfk_2` FOREIGN KEY (`pelajar_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
