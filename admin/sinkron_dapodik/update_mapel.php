<?php
//error_reporting(0);
require_once  '../../config/config.default.php';
require_once '../../config/config.function.php';
$semester_id=$_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

include '../../config/koneksi1.php';
$qmapelkini = pg_query('SELECT DISTINCT a.mata_pelajaran_id, b.nama FROM pembelajaran AS a INNER JOIN ref.mata_pelajaran AS b ON b.mata_pelajaran_id = a.mata_pelajaran_id WHERE b.expired_date IS NULL');

while ($mapelkini = pg_fetch_array($qmapelkini)) {
	$mata_pelajaran_id = $mapelkini['mata_pelajaran_id'];
	$nama = pg_escape_string($mapelkini['nama']);
	//$singkatan = cutText($nama, 0, 1);
	 $arr = explode(' ', $nama);
	$singkatan = '';
	foreach($arr as $kata)
	{
	$singkatan .= substr($kata, 0, 1);
	} 
	
require_once  '../../config/config.default.php';

	$qcarimapel2 = mysqli_query($koneksi, 'SELECT * FROM mata_pelajaran WHERE mata_pelajaran_id=\'' . $mata_pelajaran_id . '\'');
	$carimapel2 = mysqli_fetch_array($qcarimapel2);
	$txtmapel2 = $carimapel2['mata_pelajaran_id'];
	

	if ($txtmapel2) {
		$sqlmapelupdate2 = mysqli_query($koneksi, "UPDATE mata_pelajaran SET kode_mapel='$singkatan', nama_mapel='$nama' WHERE mata_pelajaran_id='$mata_pelajaran_id'");
		
	}
	else {
		$sqlmapelupdate = mysqli_query($koneksi, "INSERT INTO mata_pelajaran (kode_mapel, nama_mapel, mata_pelajaran_id)VALUES('$singkatan', '$nama', '$mata_pelajaran_id')");
		
	}


}

/* include '../../config/koneksi1.php';
$qekstrakini = pg_query('SELECT ref.ekstra_kurikuler.id_ekskul, ref.ekstra_kurikuler.nm_ekskul, ref.ekstra_kurikuler.u_sd, ref.ekstra_kurikuler.u_smp, ref.ekstra_kurikuler.u_sma, ref.ekstra_kurikuler.u_smk FROM ref.ekstra_kurikuler WHERE ref.ekstra_kurikuler.expired_date IS NULL');

while ($ekstrakini = pg_fetch_array($qekstrakini)) {
	$id_ekskul = $ekstrakini['id_ekskul'];
	$nm_ekskul = pg_escape_string($ekstrakini['nm_ekskul']);
	$u_sd = $ekstrakini['u_sd'];
	$u_smp = $ekstrakini['u_smp'];
	$u_sma = $ekstrakini['u_sma'];
	$u_smk = $ekstrakini['u_smk'];

	include '../../config/config.default.php';
	$qcariekstra = mysql_query('SELECT * FROM refekstra_kurikuler where id_ekskul=\'' . $id_ekskul . '\'');
	$cariekstra = mysql_fetch_array($qcariekstra);
	$txtekstra = $carimapel['id_ekskul'];

	if ($txtekstra) {
		$sqlekstraupdate = 'UPDATE refekstra_kurikuler set nm_ekskul=\'' . $nm_ekskul . '\',u_sd=\'' . $u_sd . '\',u_smp=\'' . $u_smp . '\',u_sma=\'' . $u_sma . '\',u_smk=\'' . $u_smk . '\' where id_ekskul=\'' . $txtekstra . '\'';
		$qrSaveekstra = mysql_query($sqlekstraupdate);
	}
	else {
		$qrySaveekstra = 'INSERT INTO refekstra_kurikuler VALUES(\'' . $id_ekskul . '\', \'' . $nm_ekskul . '\',\'' . $u_sd . '\',\'' . $u_smp . '\',\'' . $u_sma . '\',\'' . $u_smk . '\')';
		$qrySavemapel = mysql_query($qrySaveekstra);
	}
}
*/
require_once  '../../config/config.default.php';
$qcarimapel = mysqli_query($koneksi, 'SELECT count(mata_pelajaran_id) AS jumlah FROM mata_pelajaran');
$carimapel = mysqli_fetch_array($qcarimapel);
$jumlmapel = $carimapel['jumlah'];

/* include '../../config/config.default.php';
$qcariekstra = mysql_query('SELECT Count(a.id_kelas_ekskul) AS jumlah FROM public.tabel_kelas_ekskul AS a INNER JOIN public.tabel_kelas AS b ON a.rombongan_belajar_id = b.rombongan_belajar_id WHERE b.semester_id = \'' . $semester_id . '\'');
$cariekstraq = mysql_fetch_array($qcariekstra);
$jumlekstra = $cariekstraq['jumlah'];
*/
echo '<font size="2">'.$jumlmapel . ' Sudah berhasil disinkronisasi</font>';


?>
