<?php
  session_start();
  session_destroy();
  echo "<script>alert('Anda telah keluar dari halaman Pengusul'); window.location = 'index.php'</script>";
?>