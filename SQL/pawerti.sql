-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2026 at 01:33 AM
-- Server version: 8.0.44
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pawerti`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Administrator Pawerti', '2026-05-25 03:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `no_pemesanan` varchar(50) DEFAULT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `jumlah_tiket` int NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT 'Transfer Bank',
  `tanggal_booking` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('Belum Bayar','Menunggu Verifikasi','Berhasil','Dibatalkan') DEFAULT 'Belum Bayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `no_pemesanan`, `user_id`, `event_id`, `jumlah_tiket`, `total_harga`, `metode_pembayaran`, `tanggal_booking`, `bukti_pembayaran`, `status`) VALUES
(14, 'PW-BK-0014-202605', 4, 1, 1, 50000.00, 'Transfer Bank', '2026-05-26 06:49:27', NULL, 'Belum Bayar'),
(15, 'PW-BK-0015-202605', 4, 1, 1, 50000.00, 'Transfer Bank', '2026-05-26 06:52:50', 'proof_15_1779778397.png', 'Berhasil');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `judul_event` varchar(255) NOT NULL,
  `deskripsi` text,
  `tanggal_event` date DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `gambar1` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Mendatang','Selesai') DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_kursi` int DEFAULT '0',
  `sisa_kursi` int DEFAULT '0',
  `harga` decimal(10,2) DEFAULT '0.00',
  `gambar2` varchar(255) DEFAULT NULL,
  `gambar3` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `featured_sub` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `judul_event`, `deskripsi`, `tanggal_event`, `lokasi`, `kategori_id`, `gambar1`, `status`, `created_at`, `total_kursi`, `sisa_kursi`, `harga`, `gambar2`, `gambar3`, `is_featured`, `featured_sub`) VALUES
(1, 'Festival Reog Ponorogo', 'Pertunjukan kolosal Reog Ponorogo', '2026-06-01', 'Ponorogo', 1, '4b3592e259af3c91cd86424069d183c2.png', 'Aktif', '2026-05-25 03:34:12', 100, 100, 0.00, NULL, NULL, 0, ''),
(2, 'Seblang Oleh Sari', '', '2026-07-10', 'Banyuwangi, Desa Oleh Sari', 1, 'df859fe983483624b0f06de6253062e2.png', 'Mendatang', '2026-05-25 03:34:12', 2000, 400, 30.00, NULL, NULL, 0, ''),
(34, 'AAA', 'dladajdlakjdlkad', '2026-05-29', 'Madiun', 5, '96fef69af5c88788172f24f8e57c3990.png', 'Aktif', '2026-05-29 06:18:26', 10, 1, 100.00, '', '', 0, ''),
(35, 'BBBdd', 'MDLAMDADffd', '2026-05-30', 'MALANG', 5, 'ecc45bc490b4282d5ebe498cf92eebcc.png', 'Aktif', '2026-05-30 11:47:15', 100, 1, 100.00, '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `event_benefits`
--

CREATE TABLE `event_benefits` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `icon` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_benefits`
--

INSERT INTO `event_benefits` (`id`, `event_id`, `icon`, `title`, `description`, `created_at`) VALUES
(15, 34, '', 'ddd', '', '2026-05-31 10:32:46'),
(17, 35, '', 'BENEfutff', '', '2026-05-31 10:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `event_faqs`
--

CREATE TABLE `event_faqs` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `question` text,
  `answer` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_locations`
--

CREATE TABLE `event_locations` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `nama_tempat` varchar(255) DEFAULT NULL,
  `alamat` text,
  `maps_link` text,
  `catatan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_locations`
--

INSERT INTO `event_locations` (`id`, `event_id`, `nama_tempat`, `alamat`, `maps_link`, `catatan`) VALUES
(2, 1, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `event_rundown`
--

CREATE TABLE `event_rundown` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_speakers`
--

CREATE TABLE `event_speakers` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `bio` text,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_terms`
--

CREATE TABLE `event_terms` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `isi` text NOT NULL,
  `urutan` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `icon`, `created_at`) VALUES
(1, 'Seni Pertunjukan', 'Wayang, tari klasik Jawa, dan pertunjukan seni lainnya', 'fa-theater-masks', '2026-05-25 03:34:12'),
(2, 'Workshop Budaya', 'Workshop batik, keris, dan kerajinan tradisional', 'fa-tools', '2026-05-25 03:34:12'),
(3, 'Kuliner Tradisional', 'Festival makanan dan minuman khas Jawa', 'fa-utensils', '2026-05-25 03:34:12'),
(5, 'Musik', 'Musik Tradisional yang menajubkan ', 'fa-sharp fa-solid fa-music', '2026-05-28 02:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('Menunggu','Dibalas') DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

CREATE TABLE `message_replies` (
  `id` int NOT NULL,
  `message_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `balasan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `city`, `profile_pic`, `created_at`) VALUES
(4, 'rethuyy@gmail.com', '$2y$12$VYwPhLyKIOLB6drUhlxQyuelPAP/Rma8pxOz3C837cOGv/ZNbd1ja', 'Aretha', 'Safira', '081234567689', 'malang', 'e643d89b542b55b74dc07f16f17f3a4b.jpg', '2026-05-26 06:49:04'),
(5, 'tha@gmail.com', '$2y$12$JTy0QuuXlsGPXpyN6KiLp.eY7mCyUv.xLprRjwCsNfRWj1dmQKgzu', 'tha', '', '0812345678', 'Malang', NULL, '2026-05-29 05:16:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `event_benefits`
--
ALTER TABLE `event_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_faqs`
--
ALTER TABLE `event_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_locations`
--
ALTER TABLE `event_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_rundown`
--
ALTER TABLE `event_rundown`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_speakers`
--
ALTER TABLE `event_speakers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_terms`
--
ALTER TABLE `event_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `event_benefits`
--
ALTER TABLE `event_benefits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `event_faqs`
--
ALTER TABLE `event_faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_locations`
--
ALTER TABLE `event_locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_rundown`
--
ALTER TABLE `event_rundown`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_speakers`
--
ALTER TABLE `event_speakers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_terms`
--
ALTER TABLE `event_terms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_benefits`
--
ALTER TABLE `event_benefits`
  ADD CONSTRAINT `event_benefits_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_faqs`
--
ALTER TABLE `event_faqs`
  ADD CONSTRAINT `event_faqs_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_locations`
--
ALTER TABLE `event_locations`
  ADD CONSTRAINT `event_locations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_rundown`
--
ALTER TABLE `event_rundown`
  ADD CONSTRAINT `event_rundown_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_speakers`
--
ALTER TABLE `event_speakers`
  ADD CONSTRAINT `event_speakers_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_terms`
--
ALTER TABLE `event_terms`
  ADD CONSTRAINT `event_terms_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
