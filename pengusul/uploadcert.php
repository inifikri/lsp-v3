<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";

							function uploadDocCert($filecert){
							//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
							$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
							$destination = "../foto_asesicert/"; // tempat buat upload
							$filename = explode(".", $filecert['name']); 
							$file_name = $filecert['name'];
							$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
							$file_extension = $filename[count($filename)-1];
							$file_weight = $filecert['size'];
							$file_type = $filecert['type'];

							// Jika tidak ada error
							if( $filecert['error'] == 0 ){					
								$dateNow = date_create();
								$time_stamp = date_format($dateNow, 'U');
									if( in_array($file_extension, $ok_ext)):
										//if( $file_weight <= $file_max_weight ):
											$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
											$namafilesertifikat=$fileNewName;
											if( move_uploaded_file($filecert['tmp_name'], $destination.$fileNewName) ):
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
							return $namafilesertifikat;
							}

								$post0=$_POST['idpost'];
								$post1="no_serisertifikat".$post0;
								$post2="no_lisensi".$post0;
								$post3="masa_berlaku".$post0;
								$post4="file".$post0;
								$filecert = $_FILES[$post4];
	
								$namafilesertifikat= uploadDocCert($filecert);

								$sqlupdatecert="UPDATE `asesi_asesmen` SET `no_serisertifikat`='$_POST[$post1]',`no_lisensi`='$_POST[$post2]',`masa_berlaku`='$_POST[$post3]',`foto_sertifikat`='$namafilesertifikat' WHERE `id`='$post0'";
								$conn->query($sqlupdatecert);
							header('location:media.php?module=asesi');

							
?>