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
-- Struktur dari tabel `persyaratan_tukjarakjauh`
--

CREATE TABLE `persyaratan_tukjarakjauh` (
  `id` int(5) NOT NULL,
  `persyaratan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `persyaratan_tukjarakjauh`
--

INSERT INTO `persyaratan_tukjarakjauh` (`id`, `persyaratan`) VALUES
(1, 'TUK Memiliki Pencahayaan Yang Baik'),
(2, 'TUK Memiliki Ruangan Khusus'),
(3, 'TUK Memiliki Laptop/PC Dengan Kamera Yang Dapat Berfungsi Dengan Baik dan Dapat Mengakses Lingkungan Sekitar Lokasi Ujian. Jarak Jangkauan Kamera Minimal Dapat Menjangkau Setengah Bdan Tampilan Asesi'),
(4, 'TUK Memiliki Akses Jaringan Yang Stabil. Minimal 600 Kbps (down) for high quality video dan 1.2 Mbps (down) for HD Video'),
(5, 'TUK Memiliki Laptop/PC Yang Dapat Mengeluarkan Suara Dengan Jelas'),
(6, 'TUK Memiliki Laptop/PC Yang Memiliki Aplikasi Zoom'),
(7, 'TUK Memiliki Akun Zoom Yang Aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `persyaratan_tukjarakjauh`
--
ALTER TABLE `persyaratan_tukjarakjauh`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `persyaratan_tukjarakjauh`
--
ALTER TABLE `persyaratan_tukjarakjauh`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
