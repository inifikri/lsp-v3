<script>
function confirmdelete(delUrl) {
   if (confirm("Anda yakin ingin menghapus?")) {
      document.location = delUrl;
   }
}
</script>


<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){

  echo "
  </head>
  <body class='special-page'>
  <div id='container'>
  <section id='error-number'>
  
  <img src='img/lock.png'>
  <h1>MODUL TIDAK DAPAT DIAKSES</h1>
  
  <p><span class style=\"font-size:14px; color:#ccc;\">Untuk mengakses modul, Anda harus login dahulu!</p></span><br/>
  
  </section>
  
  <section id='error-text'>
  <p><a class='button' href='index.php'>&nbsp;&nbsp; <b>ULANGI LAGI</b> &nbsp;&nbsp;</a></p>
  </section>
  </div>";
}
else{

//cek hak akses user
if($cek==1 OR $_SESSION[leveluser]=='admin'){

$aksi="modul/mod_modul/aksi_modul.php";
switch($_GET[act]){
  // Tampil Modul
  default:
  
  
   echo "
   <div id='main-content'>
   <div class='container_12'>
   <div class=grid_12> 
   <br/>
   <a href='?module=modul&act=tambahmodul' class='button'>
   <span>Tambahkan Modul</span>
   </a><br/><br/>
   - Apabila PUBLISH = Y, maka Modul ditampilkan di halaman pengunjung. <br />
   - Apabila AKTIF = Y, maka Modul ditampilkan di halaman administrator pada daftar menu yang berada di bagian kiri.
   </div>
   
   <div class='grid_12'>
   <div class='block-border'>
   <div class='block-header'>
   <h1>MANAJEMEN MODUL</h1>
   <span></span> 
   </div>
   <div class='block-content'>
		  
   <table id='table-example' class='table'>	  
	         
   <thead><tr>			  
		  
  <th>Urutan</th>
  <th>Nama Modul</th>
  <th>Link</th>
  <th>Publish</th>
  <th>Aktif</th>
  <th>Status</th>
  <th>Aksi</th>
  
  </thead>
  <tbody>";
		  

    if ($_SESSION[leveluser]=='admin'){
      $tampil = $conn->query("SELECT * FROM modul ORDER BY urutan DESC");
    }
    else{
      $tampil=$conn->query("SELECT * FROM modul 
                           WHERE username='$_SESSION[namauser]'       
                           ORDER BY urutan DESC");
    }
  
 
  while ($r=$tampil->fetch_assoc()){
    $no=$r[urutan];
    $lebar=strlen($no);
    switch($lebar){
      case 1:
      {
        $g="0".$no;
        break;     
      }
      case 2:
      {
        $g=$no;
        break;     
      }      
    } 
	
   echo "<tr class=gradeX>
   <td width=70><center>$g</center></td>
   <td>$r[nama_modul]</td>
   <td><a href=$r[link]>$r[link]</a></td>
   <td align=center>$r[publish]</td>
   <td align=center>$r[aktif]</td>
   <td>$r[status]</td>
	
   <td width=80>
   <a href=?module=modul&act=editmodul&id=$r[id_modul] title='Edit' class='with-tip'>
   <center><img src='img/edit.png'></a>
   
   <a href=javascript:confirmdelete('$aksi?module=modul&act=hapus&id=$r[id_modul]') 
   title='Hapus' class='with-tip'>
   &nbsp;&nbsp;&nbsp;&nbsp;<img src='img/hapus.png'></center></a> 
	   
   </td></tr>"; }
	
    echo "</table>";
    break;

  case "tambahmodul":
  
  
 echo "
  <div id='main-content'>
  <div class='container_12'>

  <div class='grid_12'>
  <div class='block-border'>
  <div class='block-header'>
   
  <h1>TAMBAHKAN MODUL</h1>
  </div>
  <div class='block-content'>	
 
  <form method=POST action='$aksi?module=modul&act=input'>

   <p class=inline-small-label> 
   <label for=field4>Nama Modul</label>
 <input type=text name='nama_modul' size=60>
   </p>	  
   
   <p class=inline-small-label> 
   <label for=field4>Link</label>
  <input type=text name='link' size=60>
   </p>	  
   
   <p class=inline-small-label> 
   <label for=field4>Publish</label>
  <input type=radio name='publish' value='Y' checked>Ya  
  <input type=radio name='publish' value='N'> Tidak
   </p>	  

   <p class=inline-small-label> 
   <label for=field4>Aktif</label>
  <input type=radio name='aktif' value='Y' checked>Ya  
  <input type=radio name='aktif' value='N'> Tidak
   </p>	 
  
  
   <p class=inline-small-label> 
   <label for=field4>Status</label>
   <input type=radio name='status' value='admin' checked>admin 
   <input type=radio name='status' value='user'>user
   </p>	
  
    <div class=block-actions> 
   <ul class=actions-right> 
   <li>
   <a class='button red' id=reset-validate-form href='?module=modul'>Batal</a>
   </li> </ul>
   <ul class=actions-left> 
   <li>
   <input type='submit' name='upload' class='button' value=' &nbsp;&nbsp;&nbsp;&nbsp; Simpan &nbsp;&nbsp;&nbsp;&nbsp;'>
   </li> </ul>
   </form>";
		  		  
		  
  break;
  case "editmodul":
  
    $edit = $conn->query("SELECT * FROM modul WHERE id_modul='$_GET[id]'");
    $r    = $edit->fetch_assoc();

   echo "
   <div id='main-content'>
   <div class='container_12'>

   <div class='grid_12'>
   <div class='block-border'>
   <div class='block-header'>
   
   <h1>EDIT MODUL</h1>
   </div>
   <div class='block-content'>	
    
    <form method=POST action=$aksi?module=modul&act=update>
    <input type=hidden name=id value='$r[id_modul]'>
	
   <p class=inline-small-label> 
   <label for=field4>Nama Modul</label>
   <input type=text name='nama_modul' size=60 value='$r[nama_modul]'>
   </p>	  

   <p class=inline-small-label> 
   <label for=field4>Link</label>
   <input type=text name='link' size=60 value='$r[link]'>
   </p>";
		  
										 
   if ($r[publish]=='Y'){
   echo "
   <p class=inline-small-label> 
   <label for=field4>Publish</label>
   <input type=radio name='publish' value='Y' checked>Ya 
   <input type=radio name='publish' value='N'>Tidak
   </p>";}
									  
   else{
   echo "
   <p class=inline-small-label> 
   <label for=field4>Publish</label>
   <input type=radio name='publish' value='Y'>Ya 
   <input type=radio name='publish' value='N' checked>Tidak
   </p>";}
			
 									 
   if ($r[aktif]=='Y'){
   echo "
   <p class=inline-small-label> 
   <label for=field4>Aktif</label>
   <input type=radio name='aktif' value='Y' checked>Ya 
   <input type=radio name='aktif' value='N'>Tidak
   </p>";}
									  
   else{
   echo "
   <p class=inline-small-label> 
   <label for=field4>Aktif</label>
   <input type=radio name='aktif' value='Y'>Ya 
   <input type=radio name='aktif' value='N' checked>Tidak
   </p>";}
				
				
 									 
   if ($r[status]=='user'){
   echo "
   <p class=inline-small-label> 
   <label for=field4>Status</label>
   <input type=radio name='status' value='user' checked>user  
   <input type=radio name='status' value='admin'> admin
   </p>";}
									  
   else{
   echo "
   <p class=inline-small-label> 
   <label for=field4>Status</label>
   <input type=radio name='status' value='user'>user  
   <input type=radio name='status' value='admin' checked>admin
   </p>";}
					
    echo "
	<p class=inline-small-label> 
   <label for=field4>Urutan</label>
   <input type=text name='urutan' size=1 value='$r[urutan]'>
   </p>
          
	
    <div class=block-actions> 
   <ul class=actions-right> 
   <li>
   <a class='button red' id=reset-validate-form href='?module=modul'>Batal</a>
   </li> </ul>
   <ul class=actions-left> 
   <li>
   <input type='submit' name='upload' class='button' value=' &nbsp;&nbsp;&nbsp;&nbsp; Simpan &nbsp;&nbsp;&nbsp;&nbsp;'>
   </li> </ul>
   </form>";
   
   
    break;
	
   }
    //kurawal akhir hak akses module
    } else {
	echo akses_salah();
    }
    }
    ?>

   </div> 
   </div>
   </div>
   <div class='clear height-fix'></div> 
   </div></div>