-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 01 Sep 2023 pada 08.49
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lsp_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `asesi_tukjarakjauh`
--

CREATE TABLE `asesi_tukjarakjauh` (
  `id` int(5) NOT NULL,
  `asesi_id` varchar(20) NOT NULL,
  `asesor_id` int(5) DEFAULT NULL,
  `skema_id` int(5) NOT NULL,
  `jadwal_id` int(5) DEFAULT NULL,
  `tgl_tuk` date NOT NULL,
  `alamat_tuk` varchar(200) NOT NULL,
  `kepemilikan_tuk` enum('Rumah Milik Sendiri','Rumah Orang Lain','Kantor/Tempat Kerja','TUK PPM Manajemen') NOT NULL,
  `status` enum('P','A','R') NOT NULL,
  `tgl_verifikasi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `asesi_tukjarakjauh`
--
ALTER TABLE `asesi_tukjarakjauh`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `asesi_tukjarakjauh`
--
ALTER TABLE `asesi_tukjarakjauh`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
