<?php
session_start();
include "../config/koneksi.php";
	function uploadFoto($file){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG'); // ekstensi yang diijinkan
		$destination = "../foto_buktiasesmen/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];
		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}
// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `asesi_asesmen` WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;
$fotobuktiasesmen='uploadfotoasesmen'.$_POST[id_asesi];
		$file = $_FILES['file'];
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$gaso['foto_buktiasesmen'];
		}else {
			unlink('../foto_buktiasesmen/'.$gaso['foto_buktiasesmen']);
			$alamatfile = uploadFoto($file);
		}

if (isset($_REQUEST[$fotobuktiasesmen])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `asesi_asesmen` SET `foto_buktiasesmen`='$alamatfile' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Anda telah mengunggah foto bukti asesmen asesi $_POST[nama_asesi]'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}

?>