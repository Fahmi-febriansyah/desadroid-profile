-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 09:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pakar_mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `detail_konsultasi`
--

CREATE TABLE `detail_konsultasi` (
  `id_detail` int(11) NOT NULL,
  `id_konsultasi` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `kode_gejala` varchar(10) DEFAULT NULL,
  `nama_gejala` varchar(255) DEFAULT NULL,
  `cf_user` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_konsultasi`
--

INSERT INTO `detail_konsultasi` (`id_detail`, `id_konsultasi`, `id_gejala`, `kode_gejala`, `nama_gejala`, `cf_user`) VALUES
(1, 1, 1, 'G01', 'Mesin susah dihidupkan', 0.80),
(2, 1, 2, 'G02', 'Keluar asap hitam dari knalpot', 0.60),
(3, 1, 3, 'G03', 'Mesin brebet saat digas', 0.40);

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` int(11) NOT NULL,
  `kode_gejala` varchar(10) NOT NULL,
  `nama_gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`id_gejala`, `kode_gejala`, `nama_gejala`) VALUES
(1, 'G01', 'Mesin susah dihidupkan'),
(2, 'G02', 'Keluar asap hitam dari knalpot'),
(3, 'G03', 'Mesin brebet saat digas'),
(4, 'G04', 'Suara mesin kasar'),
(5, 'G05', 'Mobil sering mati mendadak'),
(6, 'G06', 'Aki cepat tekor'),
(7, 'G07', 'Starter tidak berfungsi'),
(8, 'G08', 'Konsumsi bensin boros'),
(9, 'G09', 'Mesin cepat panas'),
(10, 'G10', 'Lampu indikator mesin menyala'),
(11, 'G11', 'Tenaga mobil berkurang'),
(12, 'G12', 'Getaran mesin berlebihan'),
(13, 'G13', 'Rem kurang pakem'),
(14, 'G14', 'AC mobil tidak dingin'),
(15, 'G15', 'Oli mesin cepat habis');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `id_kerusakan` int(11) NOT NULL,
  `kode_kerusakan` varchar(10) NOT NULL,
  `nama_kerusakan` varchar(255) NOT NULL,
  `solusi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`id_kerusakan`, `kode_kerusakan`, `nama_kerusakan`, `solusi`) VALUES
(1, 'K01', 'Kerusakan Injektor', 'Periksa dan bersihkan injektor lalu lakukan penggantian jika diperlukan'),
(2, 'K02', 'Kerusakan Busi', 'Ganti busi dan lakukan pengecekan sistem pengapian'),
(3, 'K03', 'Kerusakan Aki', 'Lakukan pengisian ulang atau ganti aki baru'),
(4, 'K04', 'Kerusakan Radiator', 'Periksa radiator dan sistem pendingin kendaraan'),
(5, 'K05', 'Kerusakan Kampas Rem', 'Ganti kampas rem dan lakukan pengecekan pengereman'),
(6, 'K06', 'Kerusakan AC Mobil', 'Periksa freon, kompresor dan evaporator AC'),
(7, 'K07', 'Kerusakan Pompa Bahan Bakar', 'Periksa fuel pump dan saluran bahan bakar'),
(8, 'K08', 'Kerusakan Oli Mesin', 'Periksa kebocoran dan lakukan penggantian oli');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `merk_mobil` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `hasil_diagnosa` varchar(255) DEFAULT NULL,
  `nilai_cf` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id_konsultasi`, `nama_user`, `no_hp`, `merk_mobil`, `tanggal`, `hasil_diagnosa`, `nilai_cf`) VALUES
(1, 'Fahmi', '08123456789', 'Toyota Avanza', '2026-05-11 14:06:31', 'Kerusakan Injektor', 84.13);

-- --------------------------------------------------------

--
-- Table structure for table `rule_cf`
--

CREATE TABLE `rule_cf` (
  `id_rule` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `kode_gejala` varchar(10) NOT NULL,
  `id_kerusakan` int(11) NOT NULL,
  `kode_kerusakan` varchar(10) NOT NULL,
  `cf_pakar` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule_cf`
--

INSERT INTO `rule_cf` (`id_rule`, `id_gejala`, `kode_gejala`, `id_kerusakan`, `kode_kerusakan`, `cf_pakar`) VALUES
(1, 1, 'G01', 1, 'K01', 0.80),
(2, 2, 'G02', 1, 'K01', 0.70),
(3, 3, 'G03', 1, 'K01', 0.75),
(4, 8, 'G08', 1, 'K01', 0.65),
(5, 11, 'G11', 1, 'K01', 0.60),
(6, 1, 'G01', 2, 'K02', 0.70),
(7, 3, 'G03', 2, 'K02', 0.80),
(8, 4, 'G04', 2, 'K02', 0.60),
(9, 5, 'G05', 2, 'K02', 0.75),
(10, 12, 'G12', 2, 'K02', 0.65),
(11, 6, 'G06', 3, 'K03', 0.90),
(12, 7, 'G07', 3, 'K03', 0.85),
(13, 5, 'G05', 3, 'K03', 0.60),
(14, 9, 'G09', 4, 'K04', 0.90),
(15, 4, 'G04', 4, 'K04', 0.50),
(16, 10, 'G10', 4, 'K04', 0.60),
(17, 13, 'G13', 5, 'K05', 0.95),
(18, 14, 'G14', 6, 'K06', 0.95),
(19, 1, 'G01', 7, 'K07', 0.60),
(20, 5, 'G05', 7, 'K07', 0.70),
(21, 11, 'G11', 7, 'K07', 0.75),
(22, 15, 'G15', 8, 'K08', 0.95),
(23, 4, 'G04', 8, 'K08', 0.50),
(24, 9, 'G09', 8, 'K08', 0.65);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `detail_konsultasi`
--
ALTER TABLE `detail_konsultasi`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`id_kerusakan`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`);

--
-- Indexes for table `rule_cf`
--
ALTER TABLE `rule_cf`
  ADD PRIMARY KEY (`id_rule`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_konsultasi`
--
ALTER TABLE `detail_konsultasi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rule_cf`
--
ALTER TABLE `rule_cf`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
