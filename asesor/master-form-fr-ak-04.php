<?php
ini_set('display_errors',0); 
/*error_reporting(E_ALL);
header('Content-type: application/pdf');

session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}*/	

include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";


$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$skemakkni=$sq['judul'];

$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1=$conn->query($sqlwil1);
$wil1=$wilayah1->fetch_assoc();
$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2=$conn->query($sqlwil2);
$wil2=$wilayah2->fetch_assoc();
$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3=$conn->query($sqlwil3);
$wil3=$wilayah3->fetch_assoc();

$sqlwil1b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b=$conn->query($sqlwil1b);
$wil1b=$wilayah1b->fetch_assoc();
$sqlwil2b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b=$conn->query($sqlwil2b);
$wil2b=$wilayah2b->fetch_assoc();
$sqlwil3b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b=$conn->query($sqlwil3b);
$wil3b=$wilayah3b->fetch_assoc();

//memanggil fpdf
require_once ("fpdf/fpdf.php");

$pdf = new FPDF('P','mm',array(210,297), false, 'ISO-8859-15',array(3, 20, 3, 0));
$pdf->AddPage();

//$nomorform='Nomor : '.$noijazah.'/IKIPVET.H4/F/'.$bulanromawi.'/'.$tahunskpi;
//tampilan Form
$id_wilayah=trim($wil1['nm_wil']);
$id_wilayah2=trim($wil2['nm_wil']).", ".trim($wil3['nm_wil']);
$namalsp=strtoupper($lq['nama']);
$alamatlsp=$lq['alamat']." ".$lq['kelurahan']." ".$id_wilayah;
$alamatlsp2=$id_wilayah2." Kodepos : ".$lq['kodepos'];
$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];
//Disable automatic page break
//$pdf->SetAutoPageBreak(false);
// Foto atau Logo
$pdf->Image('../images/logolsp.jpg',15,15,25,25);
$pdf->Image('../images/logo-bnsp.jpg',170,15,25,25);

//tampilan Judul Laporan
$pdf->SetFont('Arial','B','12'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 3, '', '0', 1, 'C');
$pdf->Cell(0, 5, '', '0', 1, 'C');

$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$namalsp,'',''));
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$nomorlisensi,'',''));
$pdf->SetFont('Arial','','8'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$alamatlsp,'',''));
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$alamatlsp2,'',''));
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$telpemail,'',''));

//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();

//Headernya Identitas
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
$pdf->SetFont('Arial','B','12');
$pdf->Cell(190,6,'FR.AK.04. BANDING ASESMEN', '', 0,'L');
$pdf->Ln();
//$pdf->SetWidths(array(190));
//$keterangan1="Persetujuan Asesmen ini untuk menjamin bahwa peserta telah diberi arahan secara rinci tentang proses asesmen";
//$pdf->RowContent(array($keterangan1));
$namaasesi=ucwords(strtolower($as['nama']));
//$pdf->Cell(190,10,'Persetujuan Asesmen ini untuk menjamin bahwa peserta telah diberi arahan secara rinci tentang proses asesmen', 'LTRB', 0,'L');
$pdf->SetFont('Arial','','12');
$pdf->Cell(50,6,'Nama Asesi', 'LTB', 0,'L');
$pdf->SetFont('Arial','B','12');
$pdf->Cell(140,6,'', 'LTBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','12');
$pdf->Cell(50,6,'Nama Asesor', 'LB', 0,'L');
$pdf->SetFont('Arial','B','12');
$pdf->Cell(140,6,'', 'LBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','12');
$pdf->Cell(50,6,'Tanggal Asesmen', 'LB', 0,'L');
$pdf->SetFont('Arial','B','12');
$pdf->Cell(140,6,'', 'LBR', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','','12');
$pdf->setFillColor(198,198,198);
$pdf->Cell(150,8,'Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :', 'LB', 0,'L',TRUE);
$pdf->Cell(20,8,'YA', 'LB', 0,'C',TRUE);
$pdf->Cell(20,8,'TIDAK', 'LBR', 0,'C',TRUE);
$pdf->Ln();

$pdf->SetFont('Arial','','12');
$pdf->setFillColor(255,255,255);
$pdf->SetWidths(array(150,20,20));
$pdf->RowContent(array('Apakah Proses Banding telah dijelaskan kepada Anda?','',''));
$pdf->RowContent(array('Apakah Anda telah mendiskusikan Banding dengan Asesor?','',''));
$pdf->RowContent(array('Apakah Anda mau melibatkan “orang lain” membantu Anda dalam Proses Banding?','',''));
$pdf->Cell(190,6,'Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi ', 'LR', 0,'L');
$pdf->Ln();
$pdf->Cell(190,6,'(Kualifikasi/ Klaster/ Okupasi) berikut :', 'LR', 0,'L');
$pdf->Ln();


$sqlunitkompetensi="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$pdf->SetFont('Arial','','12');
$unk=$unitkompetensi->fetch_assoc();
$pdf->Cell(50,7,'Skema Sertifikasi', 'L', 0,'L');
$pdf->Cell(5,7,':', '', 0,'L');
$pdf->Cell(135,7,$unk['judul'], 'R', 0,'L');
$pdf->Ln();
$pdf->Cell(50,7,'No. Skema Sertifikasi', 'BL', 0,'L');
$pdf->Cell(5,7,':', 'B', 0,'L');
$pdf->Cell(135,7,$unk['kode_skema'], 'BR', 0,'L');
$pdf->Ln();

$pdf->Cell(190,7,'Banding ini diajukan atas alasan sebagai berikut :', 'LTR', 0,'L');
$pdf->Ln();
$pdf->Cell(190,25,'', 'LBR', 0,'L');
$pdf->Ln();

$pdf->SetWidths(array(190));
$pdf->RowContent(array('Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.'));

$pdf->SetFont('Arial','','12');
$pdf->Cell(190,14,'', 'LTR', 0,'L');
$pdf->Ln();
$pdf->Cell(35,7,'Tandatangan Asesi', 'L', 0,'L');
$pdf->Cell(5,7,':', '', 0,'C');
$pdf->SetFont('Arial','B','12');
$pdf->Cell(70,7,'', '', 0,'L');
$pdf->SetFont('Arial','','12');
$pdf->Cell(15,7,'Tanggal', '', 0,'L');
$pdf->Cell(5,7,':', '', 0,'C');
$pdf->SetFont('Arial','B','12');
$pdf->Cell(60,7,'', 'R', 0,'L');
$pdf->Ln();
$pdf->Cell(190,7,'', 'LBR', 0,'L');
$pdf->Ln();


//Cetak nomor halaman
$pdf->AliasNbPages();
$kode3=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
// riwayat cetak
//mysql_query("INSERT INTO `logcdhujian`(`IP`, `kodethak`, `kodekelas`, `total_peserta`, `prodi`, `kodedosen`, `remark`) VALUES ('$kode3','$tampilperiode','$row2[kodekelas]','$jmsql','$progdinya','$row2[kodedosen]','Dicetak BAAK')");


//output file pdf
$fileoutputnya="Master-FR-AK-04-BANDING-ASEMEN-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');

?>