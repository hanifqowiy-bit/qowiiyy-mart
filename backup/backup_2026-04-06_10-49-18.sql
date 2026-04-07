-- DATABASE BACKUP kowi_mart 
-- 2026-04-06 10:49:18


DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` VALUES('1','admin','admin@gmail.com','123');



DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `photo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` VALUES('2','new balance 530','500000','5','nb.jpg');
INSERT INTO `products` VALUES('3','puma speedcat','400000','5','puma.jpg');
INSERT INTO `products` VALUES('4','nike force','600000','5','nike.jpg');
INSERT INTO `products` VALUES('30','adidas samba','700000','5','samba.jpg');
INSERT INTO `products` VALUES('31','converse','700000','5','conversse.jpg');



DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `metode` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaksi` VALUES('33','2','puma speedcat','400000','1','buntu','','2026-02-25',NULL,'ditolak');
INSERT INTO `transaksi` VALUES('35','2','new balance 530','500000','1','lorong','1775444267_s7.jpeg','2026-04-06',NULL,'berhasil');
INSERT INTO `transaksi` VALUES('36','2','adidas samba','700000','1','buntu','','2026-04-06',NULL,'pending');



DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES('1','hanip','h@gmail.com','$2y$10$AFPIG8XQp0iZKCgyz9/yy.QohEiuKvoDFKPVpWSIKZRPB/JvtYlUy',NULL,NULL,'admin',NULL);
INSERT INTO `users` VALUES('2','anif','a@gmail.com','$2y$10$uIkqfkuOn6wB1p30Qf4PG.CbCC.mEVCqeL1v0nnb7XU1oGefy5r2q',NULL,NULL,'user','buntu');
INSERT INTO `users` VALUES('13','jiel','j@gmail.com','$2y$10$Zxr9mpN09eXnav5VA9Ij8.93ORlcpXbv8XBNjdQuIhLWQE7Bvpphi',NULL,NULL,'petugas',NULL);


