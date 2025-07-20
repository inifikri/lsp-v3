<?php
// Tampilkan semua error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "classes/class.phpmailer.php"; // PHPMailer versi lama
include "config/koneksi.php";
date_default_timezone_set("Asia/Jakarta");

// Ambil data dari POST
$host   = $_POST['host'] ?? '';
$port   = $_POST['port'] ?? 587;
$tujuan = $_POST['tujuan'] ?? '';
$nama   = $_POST['nama'] ?? 'User';

// Validasi email tujuan
if (!filter_var($tujuan, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Email tujuan tidak valid: $tujuan";
    exit;
}

// Inisialisasi PHPMailer
$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = 2; // Debug verbose

// Konfigurasi SMTP tanpa autentikasi
$mail->Host       = $host;
$mail->Port       = $port;
$mail->SMTPSecure = ''; // Tanpa TLS/SSL
$mail->SMTPAuth   = false;

// Opsi bypass SSL error (optional, untuk dev)
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true
    ]
];

// Set email pengirim (dummy aja untuk dev)
$mail->setFrom('no-reply@ppm-manajemen.ac.id', 'Sistem Informasi LSP');
$mail->addReplyTo('no-reply@ppm-manajemen.ac.id', 'Sistem Informasi LSP');

// Penerima
$mail->addAddress($tujuan, $nama);

// Isi email
$mail->Subject = "Testing Email Sistem Informasi LSP Berhasil";
$mail->MsgHTML("✅ Bila email ini diterima, maka sistem berhasil mengirim email melalui SMTP dev (tanpa login dan TLS).");

// Kirim email
if ($mail->send()) {
    echo "✅ Email berhasil dikirim ke <strong>$tujuan</strong>";
} else {
    echo "❌ Gagal kirim email: " . $mail->ErrorInfo;
}

echo "<br><br><a href='admin/media.php?module=smtp'>Kembali</a>";
?>