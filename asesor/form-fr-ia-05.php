<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include 'fpdf-easytable-master/html2exfpdf.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi = $conn->query($sqlasesi);
$as = $asesi->fetch_assoc();
$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal = $conn->query($sqljadwal);
$jdq = $jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk = $conn->query($sqltuk);
$tq = $tuk->fetch_assoc();
$sqllsp = "SELECT * FROM `lsp` WHERE `id`='$tq[lsp_induk]'";
$lsp = $conn->query($sqllsp);
$lq = $lsp->fetch_assoc();
$sqlskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema = $conn->query($sqlskema);
$sq = $skema->fetch_assoc();
$skemakkni = $sq['judul'];

$sqlwil1 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1 = $conn->query($sqlwil1);
$wil1 = $wilayah1->fetch_assoc();
$sqlwil2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2 = $conn->query($sqlwil2);
$wil2 = $wilayah2->fetch_assoc();
$sqlwil3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3 = $conn->query($sqlwil3);
$wil3 = $wilayah3->fetch_assoc();

$sqlwil1b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b = $conn->query($sqlwil1b);
$wil1b = $wilayah1b->fetch_assoc();
$sqlwil2b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b = $conn->query($sqlwil2b);
$wil2b = $wilayah2b->fetch_assoc();
$sqlwil3b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b = $conn->query($sqlwil3b);
$wil3b = $wilayah3b->fetch_assoc();


// $pdf=new exFPDF();
$pdf = new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->AddFont('FontUTF8', '', 'Arimo-Regular.php');
$pdf->AddFont('FontUTF8', 'B', 'Arimo-Bold.php');
$pdf->AddFont('FontUTF8', 'I', 'Arimo-Italic.php');
$pdf->AddFont('FontUTF8', 'BI', 'Arimo-BoldItalic.php');


// kop LSP ======================================================

//tampilan Form
$id_wilayah = trim($wil1['nm_wil']);
$id_wilayah2 = trim($wil2['nm_wil']) . ", " . trim($wil3['nm_wil']);
$namalsp = strtoupper($lq['nama']);
$alamatlsp = $lq['alamat'] . " " . $lq['kelurahan'] . " " . $id_wilayah;
$alamatlsp2 = $id_wilayah2 . " Kodepos : " . $lq['kodepos'];
$telpemail = "Telp./Fax.: " . $lq['telepon'] . " / " . $lq['fax'] . " Email : " . $lq['email'] . ", " . $lq['website'];
$tampilperiode = "Periode " . $jdq['periode'] . " Tahun " . $jdq['tahun'] . " Gelombang " . $jdq['gelombang'];
$nomorlisensi = "Nomor Lisensi : " . $lq['no_lisensi'];

$alamatlsptampil = $alamatlsp . " " . $alamatlsp2 . " " . $telpemail;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write = new easyTable($pdf, '{30, 120, 30}', 'width:180; align:C; font-style:B; font-family:arial;');
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

$write = new easyTable($pdf, 1, 'width:180;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.05. PERTANYAAN TERTULIS PILIHAN GANDA', 'align:L;');
$write->printRow();
$write->endTable(5);

$write = new easyTable($pdf, '{50, 20, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:12');
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
$sqlgetjenistuk = "SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$getjenistuk = $conn->query($sqlgetjenistuk);
$jnstuk = $getjenistuk->fetch_assoc();
$write->easyCell($jnstuk['jenis_tuk'], 'align:L; font-size:10; border:LTBR');
$write->printRow();

$noasr = 1;
$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
while ($gas = $getasesor->fetch_assoc()) {
	$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
	$asesor = $conn->query($sqlasesor);
	$asr = $asesor->fetch_assoc();
	if (!empty($asr['gelar_depan'])) {
		if (!empty($asr['gelar_blk'])) {
			$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
		} else {
			$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
		}
	} else {
		if (!empty($asr['gelar_blk'])) {
			$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
		} else {
			$namaasesor = $asr['nama'];
		}
	}
	$noregasesor = $asr['no_induk'];
	$namaasesor = $noasr . '. ' . $namaasesor;
	$noregasesor = $noasr . '. ' . $noregasesor;

	$noasr++;
}


$write->easyCell('Nama Asesor', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($namaasesor, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($as['nama'], 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->endTable(5);

/* $sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$jumunitnya=$getunitkompetensi->num_rows;
$jumrowspan=2*$jumunitnya;
//while unitkompetensi ==================================================================
$write=new easyTable($pdf, '{40, 30, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:10');
$write->easyCell('Unit Kompetensi', 'align:L; rowspan:'.$jumrowspan.'; border:LTBR');
while ($unk=$getunitkompetensi->fetch_assoc()){
		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
}
$write->endTable(5);

$write=new easyTable($pdf, '{10,10,160}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Jawab semua pertanyaan di berikut:', 'align:L; valign:T; colspan:2; font-size:11;');
$write->printRow(); */

$sqlgetunitkompetensib = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`=7";
$getunitkompetensib = $conn->query($sqlgetunitkompetensib);

$noku = 1;
$nopp = 1;

function tablePertanyaan($pdf_obj)
{
	return new easyTable($pdf_obj, '{10,10,160}', 'width:180; align:C; font-family:arial; font-size:12');
}

function domStyle($element)
{
	$style = [];
	foreach (explode(';', $element->getAttribute('style') ?: '') as $item) {
		$key_value = explode(':', $item);
		$style[trim($key_value[0])] = isset($key_value[1]) ? trim($key_value[1]) : NULL;
	}
	return $style;
}

while ($unkb = $getunitkompetensib->fetch_assoc()) {
	$write = new easyTable($pdf, '{40, 30, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:10');
	$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTBR');
	$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
	$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
	$write->easyCell($unkb['kode_unit'], 'align:L; font-size:10; border:LTBR');
	$write->printRow();
	$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
	$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
	$write->easyCell($unkb['judul'], 'align:L; font-size:10; border:LTBR');
	$write->printRow();
	$write->endTable(5);

	$write = tablePertanyaan($pdf);
	$write->easyCell('Jawab semua pertanyaan berikut:', 'align:L; valign:T; colspan:3; font-size:11;');
	$write->printRow();

	$sqlgetpertanyaan = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
	$getpertanyaan = $conn->query($sqlgetpertanyaan);
	$jumpertanyaan = $getpertanyaan->num_rows;

	while ($gpp = $getpertanyaan->fetch_assoc()) {
		if (!empty($pertanyaan = $gpp['pertanyaan'])) {
			$start_idx = 0;
			$any_table = ($table_idx = stripos($pertanyaan, '<table', $start_idx)) !== FALSE;
			$write->easyCell($nopp . '.', 'align:L; valign:T; font-size:12; border:LT' . ($any_table ? '' : 'B'));
			$pertanyaan = str_replace(
				["<p>",	"</p>",	"&hellip;",	"&lsquo;",	"&rsquo;",	"&ldquo;",	"&nbsp;",	"<em>",	"</em>"],
				["", "", ";", "'", "'", '"', " ", "<i>", "</i>", "’"],
				$pertanyaan
			);
			$pilihan_a = str_replace("’", "", $gpp['pilihan_a']);
			$pilihan_b = str_replace("’", "", $gpp['pilihan_b']);
			$pilihan_c = str_replace("’", "", $gpp['pilihan_c']);
			$pilihan_d = str_replace("’", "", $gpp['pilihan_d']);
			$pilihan_e = str_replace("’", "", $gpp['pilihan_e']);

			while (($table_idx = stripos($pertanyaan, '<table', $start_idx)) !== FALSE && $start_idx >= 0) {
				$write->easyCell(substr($pertanyaan, $start_idx, $table_idx - $start_idx), 'align:L; valign:T; font-size:12; colspan:2; border:TR');
				$write->printRow();
				$write->endTable(0);

				//--parse table
				$endtable_idx = stripos($pertanyaan, '</table>', $table_idx);
				$html_table = substr($pertanyaan, $table_idx, $endtable_idx - $table_idx + 8);
				$dom_table = DOMDocument::loadHTML($html_table);
				$table = $dom_table->getElementsByTagName('table')[0];
				$tbody = $table->getElementsByTagName('tbody');
				$rows = NULL;
				$cols_count = 0;
				if ($tbody) {
					foreach ($rows = $tbody[0]->getElementsByTagName('tr') as $tr) {
						$cells = $tr->getElementsByTagName('td');

						if ($cols_count < ($count = count($cells)))
							$cols_count = $count;
						else {
							$count = 0;
							foreach ($cells as $td) {
								$count += $td->getAttribute('colspan') ?: 1;
							}
							$cols_count = $count;
						}
					}
				}
				//--render table
				$table = new easyTable($pdf, '{' . rtrim(str_repeat((180 / ($cols_count ?: 1)) . ',', $cols_count ?: 1), ',') . '}', 'width:180; font-family:arial; font-size:11; border:TRLB;');
				if ($rows) {
					foreach ($rows as $tr) {
						foreach ($tr->getElementsByTagName('td') as $td) {
							$colspan = $td->getAttribute('colspan');
							$rowspan = $td->getAttribute('rowspan');
							$style = domStyle($td);
							$table->easyCell(
								$td->textContent,
								'align:' . (isset($style['text-align']) ? strtoupper($style['text-align'][0]) : 'L') . ';'
									. 'valign:' . (isset($style['vertical-align']) ? strtoupper($style['vertical-align'][0]) : 'T') . ';'
									. 'font-size:11;'
									. ($colspan ? 'colspan:' . $colspan . ';' : '')
									. ($rowspan ? 'rowspan:' . $rowspan . ';' : '')
									. (isset($style['width']) ? 'width:' . $style['width'] . ';' : '')
							);
						}
						$table->printRow();
					}
				}
				$table->endTable(0);
				$start_idx = $table_idx + 1;

				$write = tablePertanyaan($pdf);
				if (($nexttable_idx = stripos($pertanyaan, '<table', $endtable_idx + 8)) === FALSE) {
					$write->easyCell('', 'border:TL');
					$write->easyCell(substr($pertanyaan, $endtable_idx + 8), 'align:L; valign:T; font-size:12; colspan:2; border:TR');
					$write->printRow();
				}

				$any_table = TRUE;
			}

			if (!$any_table) {
				$write->easyCell($pertanyaan, 'align:L; valign:T; font-size:12; colspan:3; border:TRB');
				$write->printRow();
			}
			if (!empty($gpp['fotosoal1'])) {
				$write->easyCell('', 'img:../foto_soal/' . $gpp['fotosoal1'] . ', w110, h110; colspan:3; align:L;');
				$write->printRow();
			}
			$write->easyCell('', 'align:L; valign:T; font-size:12; border:L' . ($any_table ? '' : 'T'));
			$write->easyCell('a. ', 'align:L; valign:T; font-size:12; border:' . ($any_table ? '' : 'T'));
			$write->easyCell($pilihan_a, 'align:L; valign:T; font-size:12; border:R' . ($any_table ? '' : 'T'));
			$write->printRow();
			$write->easyCell('', 'align:L; valign:T; font-size:12; border:L');
			$write->easyCell('b. ', 'align:L; valign:T; font-size:12;');
			$write->easyCell($pilihan_b, 'align:L; valign:T; font-size:12; border:R');
			$write->printRow();
			if (!empty($gpp['pilihan_d'])) {
				$write->easyCell('', 'align:L; valign:T; font-size:12; border:L');
				$write->easyCell('c. ', 'align:L; valign:T; font-size:12;');
				$write->easyCell($pilihan_c, 'align:L; valign:T; font-size:12; border:R');
				$write->printRow();
				if (!empty($gpp['pilihan_e'])) {
					$write->easyCell('', 'align:L; valign:T; font-size:12; border:L');
					$write->easyCell('d. ', 'align:L; valign:T; font-size:12;');
					$write->easyCell($pilihan_d, 'align:L; valign:T; font-size:12; border:R');
					$write->printRow();
					$write->easyCell('', 'align:L; valign:T; font-size:12; border:LB');
					$write->easyCell('e. ', 'align:L; valign:T; font-size:12; border:B');
					$write->easyCell($pilihan_e, 'align:L; valign:T; font-size:12; border:RB');
					$write->printRow();
				} else {
					$write->easyCell('', 'align:L; valign:T; font-size:12; border:LB');
					$write->easyCell('d. ', 'align:L; valign:T; font-size:12;');
					$write->easyCell($pilihan_d, 'align:L; valign:T; font-size:12; border:RB');
					$write->printRow();
				}
			} else {
				$write->easyCell('', 'align:L; valign:T; font-size:12; border:LB');
				$write->easyCell('c. ', 'align:L; valign:T; font-size:12; border:B');
				$write->easyCell($pilihan_c, 'align:L; valign:T; font-size:12; border:RB');
				$write->printRow();
			}
			$nopp++;
		}
	}
	$write->endTable(5);
}
$write = new easyTable($pdf, '{10,170}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Catatan:', 'align:L; valign:T; colspan:2; font-style:I; font-size:11;');
$write->printRow();
$write->easyCell(chr(149), 'align:L; valign:T; font-style:I; font-size:11;');
$write->easyCell('Pertanyaan bisa dalam bentuk benar dan salah, pilihan ganda, dan menjodohkan.', 'align:L; valign:T; font-style:I; font-size:11;');
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

$write = new easyTable($pdf, '{70,60,60}', 'width:190; align:L; font-family:arial; font-size:12');
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
$fileoutputnya = "FR-IA-05-" . $skemakkni . "-" . $_GET['idj'] . "-" . $_GET['ida'] . ".pdf";
$pdf->Output($fileoutputnya, 'I');

ob_end_flush();
