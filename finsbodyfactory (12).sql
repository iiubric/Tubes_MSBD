-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2024 at 02:26 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finsbodyfactory`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteRequestMembership` (IN `request_id` INT)   BEGIN
    -- Hapus data dari tabel request_membership berdasarkan id_request
    DELETE FROM request_membership WHERE id_request = request_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bukti_pembayaran`
--

CREATE TABLE `bukti_pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_member` int NOT NULL,
  `id_user` int NOT NULL,
  `bukti_pembayaran` varchar(100) NOT NULL,
  `tanggal_diterima` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bukti_pembayaran`
--

INSERT INTO `bukti_pembayaran` (`id_pembayaran`, `id_member`, `id_user`, `bukti_pembayaran`, `tanggal_diterima`) VALUES
(1, 1, 3, 'uploads/bukti_pembayaran/_1734107198_Ryan-Gosling-HEADER.jpg', '2024-12-13 16:26:47'),
(2, 2, 4, 'uploads/bukti_pembayaran/_1734109794_drippy.jpg', '2024-12-13 17:10:08'),
(30, 34, 23, 'uploads/bukti_pembayaran/_1734110983_1211006.jpg', '2024-12-13 17:29:58'),
(31, 37, 38, 'uploads/bukti_pembayaran/_1734592841_starman.jpg', '2024-12-19 07:22:37'),
(32, 38, 28, 'uploads/bukti_pembayaran/_1734682277_diagram awal fr.jpg', '2024-12-20 08:11:51'),
(33, 40, 29, 'uploads/bukti_pembayaran/_1734682808_Ge7xkriXAAsa3c0.jpg', '2024-12-20 08:21:21'),
(35, 42, 41, 'uploads/bukti_pembayaran/_1734800499_Pertemuan 4.jpg', '2024-12-21 17:02:17'),
(36, 43, 42, 'uploads/bukti_pembayaran/_1734801760_my-image(1).png', '2024-12-21 17:23:20'),
(37, 44, 43, 'uploads/bukti_pembayaran/_1734802381_acdd2a0a155cec2de4620e7cd32902a1.jpg', '2024-12-21 17:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `membership_id` int DEFAULT NULL,
  `membership_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `status` enum('active','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `id_user`, `membership_id`, `membership_date`, `expiration_date`, `status`) VALUES
(1, 3, 4, '2024-12-13', '2026-02-13', 'active'),
(2, 4, 4, '2024-12-13', '2026-02-13', 'active'),
(34, 23, 3, '2024-12-13', '2025-07-13', 'active'),
(35, 38, 1, '2024-12-19', '2025-01-19', 'active'),
(36, 38, 1, '2024-12-19', '2025-01-19', 'active'),
(37, 38, 1, '2024-12-19', '2025-01-19', 'active'),
(38, 28, 1, '2024-12-20', '2025-01-20', 'active'),
(39, 29, 3, '2024-12-20', '2025-07-20', 'active'),
(40, 29, 3, '2024-12-20', '2025-07-20', 'active'),
(42, 41, 1, '2024-12-21', '2025-01-21', 'active'),
(43, 42, 1, '2024-12-21', '2025-01-21', 'active'),
(44, 43, 1, '2024-12-21', '2025-01-21', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_id` int NOT NULL,
  `membership_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `duration_months` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `benefits` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `membership_name`, `duration_months`, `price`, `benefits`) VALUES
(1, 'Monthly-Pass', 1, 300000.00, '1 month'),
(2, '3-Months', 3, 800000.00, '3 bulan'),
(3, 'Premium', 7, 1350000.00, '6 months + 1 bonus month'),
(4, 'Platinum', 14, 2600000.00, '12 months + 2 bonus months + T-shirt');

-- --------------------------------------------------------

--
-- Table structure for table `member_detail`
--

CREATE TABLE `member_detail` (
  `id_detail` int NOT NULL,
  `id_member` int NOT NULL,
  `nomor_hp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_kartu_identitas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member_detail`
--

INSERT INTO `member_detail` (`id_detail`, `id_member`, `nomor_hp`, `foto_kartu_identitas`) VALUES
(1, 1, '081262109282', 'uploads/foto_kartu_id/_1734107198_400px X 600px.jpg'),
(2, 2, '087768872086', 'uploads/foto_kartu_id/_1734109794_KTP.jpg'),
(29, 34, '087768872086', 'uploads/foto_kartu_id/_1734110983_acdd2a0a155cec2de4620e7cd32902a1.jpg'),
(30, 37, '087768872086', 'uploads/foto_kartu_id/_1734592841_starman.jpg'),
(31, 38, '087768872086', 'uploads/foto_kartu_id/_1734682277_flochapnapisfnpi.jpg'),
(32, 40, '087768872086', 'uploads/foto_kartu_id/_1734682808_starman.jpg'),
(34, 42, '087768872086', 'uploads/foto_kartu_id/_1734800499_starman.jpg'),
(35, 43, '087768872086', 'uploads/foto_kartu_id/_1734801760_starman.jpg'),
(36, 44, '087768872086', 'uploads/foto_kartu_id/_1734802381_starman.jpg');

-- --------------------------------------------------------

--
-- Stand-in structure for view `member_info`
-- (See below for the actual view)
--
CREATE TABLE `member_info` (
`expiration_date` date
,`foto_kartu_identitas` varchar(100)
,`id` int
,`membership_date` date
,`membership_id` int
,`nama` varchar(100)
,`nomor_hp` varchar(15)
,`status` enum('active','expired')
);

-- --------------------------------------------------------

--
-- Table structure for table `request_membership`
--

CREATE TABLE `request_membership` (
  `id_request` int NOT NULL,
  `id_user` int NOT NULL,
  `id_membership` int NOT NULL,
  `foto_kartu_identitas` varchar(100) DEFAULT NULL,
  `bukti_pembayaran` varchar(100) NOT NULL,
  `nomor_hp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal_request` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_pembayaran` enum('pending','diterima','ditolak') NOT NULL DEFAULT 'pending',
  `level` enum('User','Member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `level` enum('Admin','Cashier','Member','User') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `nama`, `username`, `password`, `level`) VALUES
(1, 'admin@test.com', 'Jofin', 'admin', '$2y$10$t6jnL0hr0qT.u5Th912meu9g5GYvtEgIhOIVswEMSeSCkOtDnglfK', 'Admin'),
(3, 'irsyadfauzi36@gmail.com', 'irsyad', 'irsada', '$2y$10$8iJL9kM3jJejyLBdMMKR6uoF6JP7mWdM0u58B96.uxMt6CK4t6ZJK', 'Member'),
(4, 'anggi.majuterus75@gmail.com', 'Anggi', 'skibidi', '$2y$10$L5jlECcd76VleNum3LOSiOgNSfAg7AHYRgSaJuLdTibGOF42rWBMa', 'Member'),
(23, 'iiubric@gmail.com', 'Ilyas', 'iiubric', '$2y$10$4ob11RpEln6Lu7GT/AJPZeeM4wen44oCHGAWaV22sBQqtSggp6a1K', 'Member'),
(24, 'john@gmail.com', 'John', 'weetod', '$2y$10$6PpLKStlWh78X/8515D81O3w7/IBwujDpNTEso86HGLV7Uv.dKO0q', 'User'),
(27, 'tes1@email.com', 'tes1', 'tes1', '$2y$10$5zgJi6oYpZi6wVxY3ZJEwubORXYkBI5BCrxlkK9v9WmkJ0evqSGuG', 'User'),
(28, 'tes2@gmail.com', 'tes2', 'tes2', '$2y$10$lqYKeFfnMMkR7QOktBrUiO/u32KGlYByjnrjdfqKpXODnH1C16vkm', 'Member'),
(29, 'tes3@gmail.com', 'tes3', 'tes3', '$2y$10$JdXg4ATZQKjSdB0nP08c1ORiocpeBS2JpyfACE1JuTioy04i.500u', 'Member'),
(30, 'tes4@gmail.com', 'tes4', 'tes4', '$2y$10$kSH.5aoPFGV4FWiPn8JBbusL.P.npZqUh5yc9KvKUwA.CLSx0kHSu', 'User'),
(31, 'tes5@gmail.com', 'tes5', 'tes5', '$2y$10$kAq3tAqxN3VhEPV6TQaMv.C78K.soVKP5.h/ptWyTeDXPq6DQqfWW', 'User'),
(32, 'tes6@gmail.com', 'tes6', 'tes6', '$2y$10$bA4f/AAD0K4zFKBSAM/5L.G5.dRhb7aGv29q6eMpIc/TdwW/MKth6', 'User'),
(33, 'asdf@gmail.com', 'Adi', 'aa1', '$2y$10$HNTUlPt7KvriCWMf8wFJ3.gm6yN1MZQUGT/xUHyecDDFfMkGLBd4G', 'User'),
(34, 'saya@gmail.com', 'Saya', 'saya91', '$2y$10$AGU7vnqA1w6eqP90C.URPu9u0BoK/DIaHQphdjx2fYp4LuxACc0um', 'User'),
(35, 'aisnz@gmail.com', 'Aiya', 'Me21', '$2y$10$KXsVgu4cT8oFQJ6z8ukQneyaUT1X1NJ4uzMG8rsd4IB.6ZiHipqgq', 'User'),
(38, 'tahla@gmail.com', 'budi', 'tah', '$2y$10$ByijO7v2ar1oAa6D29LPW.EPaDSpmpDHd5YjngdmLn0X0Hp9.5SOi', 'Member'),
(41, 'asd@email.com', 'Ilyas', 'kono', '$2y$10$PwwOkvFZYdyGMwKxTTgAwOwcDFzwNfrq4WGyJioXsbkCjdf8Se/IW', 'Member'),
(42, 'asdasd@email.com', 'Ilyas', 'koni', '$2y$10$tY6i8Q8keRGfpupoKr7dTeOo8pqcEmsPUo/vY/jG3OXVvxngbBN32', 'Member'),
(43, 'ilyas@gmail.com', 'Ilyas', 'kona', '$2y$10$s.Ivc1dFYlVMpaUkz/XuvujJYW1oxQCf/R3Sr69HlI3c5HMgs24Gq', 'Member');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `unique_username` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1 FROM users WHERE username = NEW.username
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Username Already Exists';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_email` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.email NOT LIKE '%@%.%' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `member_info`
--
DROP TABLE IF EXISTS `member_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `member_info`  AS SELECT `m`.`id` AS `id`, `u`.`nama` AS `nama`, `md`.`nomor_hp` AS `nomor_hp`, `md`.`foto_kartu_identitas` AS `foto_kartu_identitas`, `m`.`membership_id` AS `membership_id`, `m`.`membership_date` AS `membership_date`, `m`.`expiration_date` AS `expiration_date`, `m`.`status` AS `status` FROM ((`member` `m` join `member_detail` `md` on((`m`.`id` = `md`.`id_member`))) join `users` `u` on((`m`.`id_user` = `u`.`id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bukti_pembayaran`
--
ALTER TABLE `bukti_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_member` (`id_member`),
  ADD KEY `fk_bukti_pembayaran_users` (`id_user`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`id_user`),
  ADD KEY `membership_id` (`membership_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `member_detail`
--
ALTER TABLE `member_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `request_membership`
--
ALTER TABLE `request_membership`
  ADD PRIMARY KEY (`id_request`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_membership` (`id_membership`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bukti_pembayaran`
--
ALTER TABLE `bukti_pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member_detail`
--
ALTER TABLE `member_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `request_membership`
--
ALTER TABLE `request_membership`
  MODIFY `id_request` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bukti_pembayaran`
--
ALTER TABLE `bukti_pembayaran`
  ADD CONSTRAINT `bukti_pembayaran_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bukti_pembayaran_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`membership_id`) REFERENCES `membership` (`membership_id`) ON DELETE SET NULL;

--
-- Constraints for table `member_detail`
--
ALTER TABLE `member_detail`
  ADD CONSTRAINT `member_detail_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_membership`
--
ALTER TABLE `request_membership`
  ADD CONSTRAINT `request_membership_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_membership_ibfk_2` FOREIGN KEY (`id_membership`) REFERENCES `membership` (`membership_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `check_expired_memberships` ON SCHEDULE EVERY 1 DAY STARTS '2024-12-15 18:33:17' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Update status anggota yang telah melewati expiration_date
    UPDATE member
    SET status = 'expired',
        membership_id = NULL,
        membership_date = NULL,
        expiration_date = NULL
    WHERE expiration_date < CURRENT_DATE();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
