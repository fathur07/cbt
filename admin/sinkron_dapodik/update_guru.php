<?php
//session_start();
require_once '../../config/config.default.php';
include_once '../../config/config.function.php';
require_once '../../config/inc.library.php';

$semester_id = $_SESSION['semester_id'];
$pass = genPass(5);
$passx = password_hash($pass, PASSWORD_BCRYPT);
$level = "guru";

include '../../config/koneksi1.php';
$qptkini = pg_query("SELECT DISTINCT  a.ptk_id,a.nama,a.nip,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nuptk,a.jenis_ptk_id,a.alamat_jalan,a.status_keaktifan_id,a.soft_delete, b.ptk_terdaftar_id,b.tahun_ajaran_id,b.jenis_keluar_id,b.soft_delete AS soft_delete2 FROM ptk AS a INNER JOIN ptk_terdaftar AS b ON b.ptk_id = a.ptk_id INNER JOIN ref.semester AS c ON c.tahun_ajaran_id = b.tahun_ajaran_id WHERE c.semester_id = '$semester_id'  AND a.jenis_ptk_id IN (4, 5, 12,13, 14,20) AND b.jenis_keluar_id IS NULL  ORDER BY b.tahun_ajaran_id DESC");

while ($ptkkini = pg_fetch_array($qptkini)) {
	$ptk_id = $ptkkini['ptk_id'];
	$nama = pg_escape_string($ptkkini['nama']);
	$nip = $ptkkini['nip'];
	$jenis_kelamin = $ptkkini['jenis_kelamin'];
	$tempat_lahir = pg_escape_string($ptkkini['tempat_lahir']);
	$tanggal_lahir = $ptkkini['tanggal_lahir'];
	$nuptk = $ptkkini['nuptk'];
	$jenis_ptk_id = $ptkkini['jenis_ptk_id'];
	$alamat_jalan = pg_escape_string($ptkkini['alamat_jalan']);
	$status_keaktifan_id = $ptkkini['status_keaktifan_id'];
	//echo  $ptk_id;
	if (0 < $status_keaktifan_id) {
		$status_keaktifan_id = '1';
	} else {
		$status_keaktifan_id = $status_keaktifan_id;
	}

	if (strlen($nip) < 5) {
		$useridx = preg_replace('/[^A-Za-z0-9\\-]/', '', $nama);
		$userid = strtolower(substr($useridx, 0, 18));
	} else {
		$userid = preg_replace('/[^A-Za-z0-9\\-]/', '', $nip);
	}


	$soft_delete = $ptkkini['soft_delete'];
	$ptk_terdaftar_id = $ptkkini['ptk_terdaftar_id'];
	$tahun_ajaran_id = $ptkkini['tahun_ajaran_id'];
	$jenis_keluar_id = $ptkkini['jenis_keluar_id'];
	$soft_delete2 = $ptkkini['soft_delete2'];
	$dpng = '';
	$blkg = '';
	$ptk_idcek = $ptkkini['ptk_id'];



	include '../../config/koneksi1.php';

	$qglrdpn = pg_query("SELECT a.ptk_id, c.posisi_gelar, c.kode FROM ptk AS a INNER JOIN rwy_pend_formal AS b ON b.ptk_id = a.ptk_id INNER JOIN ref.gelar_akademik AS c ON b.gelar_akademik_id = c.gelar_akademik_id WHERE b.soft_delete = 0 AND a.soft_delete = 0 AND b.gelar_akademik_id IS NOT NULL AND b.status_kuliah = 0 AND a.ptk_id= '$ptk_idcek'  AND c.posisi_gelar=1 AND b.jenjang_pendidikan_id >=30 ORDER BY a.ptk_id ASC, b.jenjang_pendidikan_id ASC");

	while ($cgdpn = pg_fetch_array($qglrdpn)) {
		$dpng = $cgdpn['kode'] . '. ';
	}

	include '../../config/koneksi1.php';

	while ($cgblk = pg_fetch_array($qglrdpn)) {
		$blkg = $cgblk['kode'] . '. ';
	}

	if (20 < strlen($dpng)) {
		$dpng = '';
	} else {
		$dpng = $dpng;
	}

	if (20 < strlen($blkg)) {
		$blkg = '';
	} else {
		$blkg = $blkg;
	}

	//$nm_lengkap = $dpng.$nama.$blkg ;

	require_once '../../config/config.default.php';
	$qcariptk = mysqli_query($koneksi, "SELECT * FROM pengawas where ptk_id='$ptk_id'");
	$cariptk = mysqli_fetch_array($qcariptk);
	$txtptk = $cariptk['ptk_id'];


	if ($txtptk) {
		$sqlptkupdate = mysqli_query($koneksi, "UPDATE pengawas set 
		nama = '$nama', 
		username = '$userid', 
		password = '$pass', 
		nip = '$nip', 
		jenis_ptk = '$jenis_ptk_id',
		level = '$level', 
		jenis_kelamin = '$jenis_kelamin', 
		tempat_lahir = '$tempat_lahir',
		tgl_lahir = '$tanggal_lahir',
		nuptk = '$nuptk' , 
		alamat_jalan = '$alamat_jalan', 
		status_aktif = '$status_keaktifan_id', 
		WHERE ptk_id = '$txtptk'");
		/*$qrySaveptk = mysql_query($sqlptkupdate);*/
	} else {
		$qrySaveptk = mysqli_query($koneksi, "INSERT INTO pengawas (ptk_id, nama, username, password,nip, jenis_ptk, level, jenis_kelamin, tempat_lahir, tgl_lahir, nuptk, alamat_jalan, status_aktif) VALUES(
		'$ptk_id', 
		'$nama', 
		'$userid', 
		'$pass', 
		
		'$nip' , 
		'$jenis_ptk_id', 
		'$level', 
		'$jenis_kelamin',  
		'$tempat_lahir',  
		'$tanggal_lahir',   
		'$nuptk',  
		'$alamat_jalan',  
		'$status_keaktifan_id')");
		/*$qrySaveptk = mysql_query($qrySaveptk); */
	}
}

$qcariptk = mysqli_query($koneksi, "SELECT Count(level) AS jumlahptk FROM pengawas WHERE level ='$level'");
$cariptk = mysqli_fetch_array($qcariptk);
$jumlahptk = $cariptk['jumlahptk'];
echo '<font size="2">' . $jumlahptk . ' Sudah berhasil disinkronisasi</font>';
