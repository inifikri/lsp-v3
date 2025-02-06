<?php
ini_set('display_errors',0); 
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
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
$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');
// kop LSP ======================================================
//tampilan Form
$id_wilayah=trim($wil1['nm_wil']);
$id_wilayah2=trim($wil2['nm_wil']).", ".trim($wil3['nm_wil']);
$namalsp=strtoupper($lq['nama']);
$alamatlsp=$lq['alamat']." ".$lq['kelurahan']." ".$id_wilayah;
$alamatlsp2=$id_wilayah2." Kodepos : ".$lq['kodepos'];
$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];
$alamatlsptampil=$alamatlsp." ".$alamatlsp2." ".$telpemail;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write=new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h25; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h25;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);
//===============================================================
$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.06. PERTANYAAN TERTULIS ESAI', 'align:L;');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Skema Sertifikasi (KKNI/Okupasi/Klaster)', 'align:L; rowspan:2; border:LTBR');
$write->easyCell('Judul', 'align:L; font-size:10; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($skemakkni, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Nomor', 'align:L; font-size:10; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($sq['kode_skema'], 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('TUK', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

$write->easyCell('Mandiri/Sewaktu/Tempat Kerja', 'align:L; font-size:10; border:LTBR');
$write->printRow();

$write->easyCell('Nama Asesor', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->endTable(5);
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$jumunitnya=$getunitkompetensi->num_rows;
$jumrowspan=2*$jumunitnya;
//while unitkompetensi ==================================================================
$write=new easyTable($pdf, '{40, 30, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:10');
//$write->easyCell('Unit Kompetensi', 'align:L; rowspan:'.$jumrowspan.'; border:LTBR');
$nounkx=1;
while ($unk=$getunitkompetensi->fetch_assoc()){
		if ($nounkx==1){
			$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTR');
		}else{
			if ($jumunitnya==$nounkx){
				$write->easyCell('', 'align:L; rowspan:2; border:LBR');
			}else{
				$write->easyCell('', 'align:L; rowspan:2; border:LR');
			}
		}
		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$nounkx++;
}
$write->endTable(5);
$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Jawab semua pertanyaan di bawah ini:', 'align:L; valign:T; colspan:2; font-size:11;');
$write->printRow();
$sqlgetunitkompetensib="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensib=$conn->query($sqlgetunitkompetensib);
$noku=1;
$nopp=1;
while ($unkb=$getunitkompetensib->fetch_assoc()){
	$sqlgetpertanyaan="SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
	$getpertanyaan=$conn->query($sqlgetpertanyaan);
	$jumpertanyaan=$getpertanyaan->num_rows;
	while ($gpp=$getpertanyaan->fetch_assoc()){
		if (!empty($gpp['pertanyaan'])){
			$write->easyCell($nopp.'.', 'align:L; valign:T; font-size:12; border:LTB');
			// remove html character =============================
			$string1=$gpp['pertanyaan'];
    			$string1=str_replace("<strong>", "", $string1 );
    			$string1=str_replace("</strong>", "", $string1 );
    			$string1=str_replace("<table>", "", $string1 );
    			$string1=str_replace("</table>", "", $string1 );
    			$string1=str_replace("<tbody>", "", $string1 );
    			$string1=str_replace("</tbody>", "", $string1 );
     			$string1=str_replace("<th>", "", $string1 );
    			$string1=str_replace("</th>", "", $string1 );
				$string1=str_replace("<td>", "", $string1 );
    			$string1=str_replace("</td>", "", $string1 );
    			$string1=str_replace("<tr>", "", $string1 );
    			$string1=str_replace("</tr>", "", $string1 );
    			$string1=str_replace("<p>", "", $string1 );
    			$string1=str_replace("<ol>", "", $string1 );
    			$string1=str_replace("<li>", "", $string1 );
    			$string1=str_replace("<ul>", "", $string1 );
    			$string1=str_replace("</p>", "", $string1 );
    			$string1=str_replace("</ol>", "", $string1 );
    			$string1=str_replace("</li>", "", $string1 );
    			$string1=str_replace("</ul>", "", $string1 );
				$string1=str_replace("&nbsp", " ", $string1 );
    			$string1=str_replace("&", "", $string1 );
    			$string1=str_replace(">", "", $string1 );
    			$string1=str_replace("<", "", $string1 );
    			$string1=str_replace("&agrave;", "", $string1 );
    			$string1=str_replace("&euml;", "", $string1 );
    			$string1=str_replace("\"", "", $string1 );
    			$string1=str_replace("&lt;br /&gt;", "", $string1 );
    			$string1=str_replace("&eacute;", "", $string1 );
    			$string1=str_replace("à", "", $string1 );
			//==================================================
			$write->easyCell($string1, 'align:L; valign:T; font-size:12; border:TRB');
			$write->printRow();
			$nopp++;
		}
	}
}
$write->easyCell('* Pelatih/Asesor harus menggunakan kebijaksanaan (professional justification) saat menentukan apakah jawaban yang diberikan oleh Asesi dapat diterima atau tidak', 'align:L; valign:T; colspan:2; font-style:I; font-size:11;');
$write->printRow();
$write->easyCell('Catatan:', 'align:L; valign:T; colspan:2; font-style:I; font-size:11;');
$write->printRow();
$write->easyCell(chr(149), 'align:L; valign:T; font-style:I; font-size:11;');
$write->easyCell('Daftar pertanyaan dapat berisi pertanyaan dari semua dimensi kompetensi. Jika ada pertanyaan yang tidak dijawab, maka dapat dieksplorasi dari menilai melalui pertanyaan verbal.', 'align:L; valign:T; font-style:I; font-size:11;');
$write->printRow();
$write->easyCell(chr(149), 'align:L; valign:T; font-style:I; font-size:11;');
$write->easyCell('Pertanyaan juga dapat difokuskan pada akurasi dan presisi yang dapat membantu memberikan rekomendasi tindak lanjut untuk menilai.', 'align:L; valign:T; font-style:I; font-size:11;');
$write->printRow();
$write->easyCell(chr(149), 'align:L; valign:T; font-style:I; font-size:11;');
$write->easyCell('Pertanyaan presisi jika tidak dapat dijawab, penilai disarankan untuk menambahkan lebih banyak latihan / bekerja di bawah pengawasan, sedangkan jika pertanyaan akurasi dilewatkan maka penilai direkomendasikan untuk pelatihan ulang.', 'align:L; valign:T; font-style:I; font-size:11;');
$write->printRow();
$write->endTable(5);
//end while unitkompetensi =============================================
$write=new easyTable($pdf, '{70,60,60}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Penyusun dan Validator', 'align:L; colspan:3; valign:T; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Nama', 'align:C; valign:M; border:LTRB; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Jabatan', 'align:C; valign:M;  border:TR; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Tandatangan', 'align:C; valign:M;  border:TR; font-style:B; bgcolor:#C6C6C6;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('', 'align:C; valign:M; border:LTRB;');
$write->easyCell('Penyusun', 'align:L; valign:T;  border:TR;');
$write->easyCell('', 'align:C; valign:T;  border:TR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('', 'align:C; valign:M; border:LTRB;');
$write->easyCell('Validator', 'align:L; valign:T;  border:TRB;');
$write->easyCell('', 'align:C; valign:T;  border:TRB;');
$write->printRow();
$write->endTable(0);
$pdf->AliasNbPages();
//output file pdf
$fileoutputnya="Master-FR-IA-06-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
ob_end_flush();
?>