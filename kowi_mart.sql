-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 06:47 AM
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
-- Database: `kowi_mart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `nomer_hp` varchar(20) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `payment_method` enum('transfer','cod') NOT NULL,
  `payment_proof` varchar(200) DEFAULT NULL,
  `status` enum('pending','diproses','selesai') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `photo` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `photo`, `description`) VALUES
(2, 'new balance 530', 500000, 5, 'nb.jpg', 'New Balance 530 hadir dengan desain retro modern yang nyaman untuk aktivitas sehari-hari. Bagian atas menggunakan mesh dan suede berkualitas tinggi, memberikan ventilasi optimal dan ketahanan. Sol ENCAP midsole menyerap guncangan dan mendukung stabilitas, sementara outsole karet anti-slip memberikan traksi andal. Kombinasi warna klasik menonjolkan gaya kasual yang sporty dan ikonik.'),
(3, 'puma speedcat', 400000, 5, 'puma.jpg', 'Puma Speedcat Merah adalah sepatu balap ikonik dengan tampilan sporty dan agresif. Bagian atas menggunakan kulit sintetis premium dengan aksen jahitan detail, memberikan daya tahan dan kesan elegan. Sol karet tipis mendukung mobilitas maksimal dan cengkraman optimal, sementara desain low-profile menonjolkan siluet ramping. Warna merah cerah dipadukan logo Puma khas, sempurna untuk gaya kasual maupun aktivitas ringan dengan kesan dinamis.'),
(4, 'nike force', 600000, 5, 'nike.jpg', 'Nike Force adalah sepatu ikonik dengan desain klasik yang tangguh dan stylish. Bagian atas terbuat dari kulit premium yang awet, dilengkapi sol karet tebal untuk cengkraman dan stabilitas maksimal. Desain low-cut mendukung mobilitas kaki, sementara aksen logo Nike menambah kesan sporty dan elegan, cocok untuk gaya kasual maupun aktivitas ringan.'),
(30, 'adidas samba', 700000, 5, 'samba.jpg', 'Sepatu Samba Hitam adalah pilihan klasik untuk gaya santai maupun olahraga ringan. Bagian atas terbuat dari kulit sintetis berkualitas tinggi yang tahan lama, dipadukan dengan panel suede di sisi depan untuk aksen sporty. Sol karet anti-slip memberikan cengkraman optimal, sementara desain low-cut memudahkan gerakan kaki. Dilengkapi tali hitam serasi dan logo ikonik pada lidah sepatu, cocok untuk aktivitas sehari-hari maupun penampilan kasual yang stylish.'),
(33, 'converse', 900000, 5, 'conversse.jpg', 'buat sekolah');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `metode` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `user_id`, `nama_produk`, `harga`, `jumlah`, `alamat`, `bukti`, `tanggal`, `metode`, `status`) VALUES
(33, 2, 'puma speedcat', 400000, 1, 'buntu', '', '2026-02-25', NULL, 'berhasil'),
(35, 2, 'new balance 530', 500000, 1, 'lorong', '1775444267_s7.jpeg', '2026-04-06', NULL, 'berhasil'),
(36, 2, 'adidas samba', 700000, 1, 'buntu', '', '2026-04-06', NULL, 'pending'),
(37, 15, 'nike force', 600000, 1, 'jurang', '', '2026-04-07', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `no_hp`, `role`, `alamat`) VALUES
(1, 'hanip', 'h@gmail.com', '$2y$10$AFPIG8XQp0iZKCgyz9/yy.QohEiuKvoDFKPVpWSIKZRPB/JvtYlUy', NULL, NULL, 'admin', NULL),
(2, 'anif', 'a@gmail.com', '$2y$10$uIkqfkuOn6wB1p30Qf4PG.CbCC.mEVCqeL1v0nnb7XU1oGefy5r2q', NULL, NULL, 'user', 'buntu'),
(13, 'jiel', 'j@gmail.com', '$2y$10$Zxr9mpN09eXnav5VA9Ij8.93ORlcpXbv8XBNjdQuIhLWQE7Bvpphi', NULL, NULL, 'petugas', NULL),
(15, 'nisa', 'nis@gmail.com', '$2y$10$KgWGsETBUBrg1qrkuP7cEes8Rx7EdMCc9i2K0o6EAXv6W2N5oguva', NULL, NULL, 'user', 'jurang');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
