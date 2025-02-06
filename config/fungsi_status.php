<?php
ini_set('display_errors',0);
function get_cek_status_apl02 ($connection,$idasesi='10', $idjadwal='10') {
    $query_status_apl02="SELECT status_apl02 , jml_doc	FROM vw_get_apl02_status t WHERE t.id_asesi='$idasesi' AND t.id_jadwal='$idjadwal'";
    $dt_status_apl02=$connection->query($query_status_apl02);#var_dump($query_status_apl02);
    $dt_apl02=$dt_status_apl02->fetch_assoc();
    $statusaplo2 = ($dt_apl02['status_apl02']== 'A' && $dt_apl02['jml_doc']>0) ? TRUE : FALSE;
    
	return $statusaplo2;
}

function get_cek_status_asesor ($connection,$idasesor='10', $idjadwal='10') {
    $query_status_asesor="SELECT * FROM `jadwal_asesor` t WHERE  t.id_asesor='$idasesor' AND t.id_jadwal='$idjadwal'";
    $dt_status_asesor=$connection->query($query_status_asesor);
    $jumas2=$dt_status_asesor->num_rows;
   
    if($jumas2>0){
        $statusapasesor['nilai']= TRUE;
        $statusapasesor['keterangan']= "<font color='red'><b>Asesor ini telah  bertugas sebagai asesor penguji pada tanggal tersebut</b></font>";
    }else{
        $statusapasesor['nilai']= FALSE;
        $statusapasesor['keterangan']= "<font color='green'><b>Asesor ini direkomendasikan dipilih, karena ditugaskan pada tanggal dan TUK yang sama</b></font>";
     }    
    
	return $statusapasesor;
}

function get_id_asesi ($connection,$id='10') {
    $queryID="SELECT * FROM `asesi_asesmen` t WHERE  t.id='$id'";
    $dt_IDasesi=$connection->query($queryID);
    $dt_apl02=$dt_IDasesi->fetch_assoc();
	return $dt_apl02['id_asesi'];
}


?>