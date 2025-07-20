<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
//ambil parameter
$idNegara = $_POST['idNegara'];
 
if($idNegara == ''){
     exit;
}else{
     $sql = "SELECT * FROM data_wilayah WHERE id_wil = '$idNegara' AND id_induk_wilayah!='NULL' ORDER BY nm_wil";
     $getNamaProvinsi = mysql_query($sql,$conn) or die ('Query Gagal');
     while($data = mysql_fetch_array($getNamaProvinsi)){
          echo '<option value="'.$data['id_wil'].'">'.$data['nm_wil'].'</option>';
     }
     exit;    
}
?>