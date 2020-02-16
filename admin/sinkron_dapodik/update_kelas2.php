<?php

include '../../config/config.default.php';
include '../../config/config.function.php';
include '../../config/inc.library.php';
$semester_id = $_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

 include 'koneksi1.php';
$qkelaskini = pg_query("SELECT a.rombongan_belajar_id, a.sekolah_id, a.semester_id, a.jurusan_sp_id, a.jenis_rombel, a.ptk_id, a.nama, a.tingkat_pendidikan_id, a.kurikulum_id FROM rombongan_belajar AS a WHERE a.semester_id = '$semester_id' AND  a.soft_delete = 0  AND a.last_update<=a.last_sync");

while ($kelaskini = pg_fetch_array($qkelaskini)) {
	$nama = pg_escape_string($kelaskini['nama']);
	$nama_jurusan_sp = pg_escape_string($kelaskini['jurusan_sp_id']);
	$rombongan_belajar_id = $kelaskini['rombongan_belajar_id'];
	$sekolah_id = $kelaskini['sekolah_id'];
	$semester_id2 = $kelaskini['semester_id'];
	$kurikulum_id = $kelaskini['kurikulum_id'];
	$tingkat_pendidikan_id = $kelaskini['tingkat_pendidikan_id'];
	if ($tingkat_pendidikan_id == "1"){$nmtingkat ="Tingkat 1";} 
	elseif($tingkat_pendidikan_id == "2"){$nmtingkat ="Tingkat 2";} 
	elseif($tingkat_pendidikan_id == "3"){$nmtingkat ="Tingkat 3";}
	elseif($tingkat_pendidikan_id == "4"){$nmtingkat ="Tingkat 4";} 
	elseif($tingkat_pendidikan_id == "5"){$nmtingkat ="Tingkat 5";} 
	elseif($tingkat_pendidikan_id == "6"){$nmtingkat ="Tingkat 6";} 
	elseif($tingkat_pendidikan_id == "7"){$nmtingkat ="Tingkat 7";}
	elseif($tingkat_pendidikan_id == "8"){$nmtingkat ="Tingkat 8";}
	elseif($tingkat_pendidikan_id == "9"){$nmtingkat ="Tingkat 9";}
	elseif($tingkat_pendidikan_id == "10"){$nmtingkat ="Tingkat 10";}
	elseif($tingkat_pendidikan_id == "11"){$nmtingkat ="Tingkat 11";}
	elseif($tingkat_pendidikan_id == "12"){$nmtingkat ="Tingkat 12";}
	else{$nmtingkat ="Tingkat 0";}


	$jenis_rombel = $kelaskini['jenis_rombel'];
	if (($jenis_rombel == '1') || ($jenis_rombel == '8') || ($jenis_rombel == '9')) {
		$ptk_id = $kelaskini['ptk_id'];
	}
	else {
		$ptk_id = 'Null';
	}

	 include 'koneksi.php';
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurusan where jurusan_id='SMP0000'");
	$dtq = mysqli_fetch_array($q);
	$jurusan_id = $dtq['jurusan_id'];
	$nama_jurusan_sp = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dtq['nama_jurusan_sp']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$jurusan_sp_id = $dtq['jurusan_sp_id'];

	$qcarikelas = mysqli_query($GLOBALS["___mysqli_ston"], " SELECT rombongan_belajar_id FROM kelas where id_kelas= '$rombongan_belajar_id' AND semester_id='$semester_id2' ");
	$carikelas = mysqli_fetch_array($qcarikelas);
	$txtkelas = $carikelas['rombongan_belajar_id'];

	if ($txtkelas) {
		$sqlkelasupdate = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kelas set 
			id_kelas='$nama',
			nama='$nama',
			level= '$tingkat_pendidikan_id',
			semester_id= '$semester_id2' 
			where rombongan_belajar_id= '$rombongan_belajar_id' ");
		//$qrySavekelass = mysql_query($sqlkelasupdate);
	}
	else {
		$qrySavekelas = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kelas VALUES(
			'$nama',
			'$nama',
			'$tingkat_pendidikan_id',
			'$rombongan_belajar_id',
			'$semester_id2') ");
		//$qrySavekelas2 = mysql_query($qrySavekelas);
	}

	

	//data level lokal
	 include 'koneksi.php';
	$qcarilevel = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_kelas FROM kelas where id_kelas='$rombongan_belajar_id' ");
	$carilevel = mysqli_fetch_array($qcarilevel);
	$txtkelas = $carilevel['id_kelas'];

	if ($txtkelas) {
		$sqllevelupdate = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE level set 
			kode_level='$tingkat_pendidikan_id', 
			keterangan='$nmtingkat' 
			where rombongan_belajar_id='$txtkelas' ");
		//$qrylevelupdate = mysql_query($sqllevelupdate);
	}
	else {
		$sqllevelupdate1 = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO level VALUES('$tingkat_pendidikan_id', '$nmtingkat', '$rombongan_belajar_id') ");
		//$qrylevelupdate1 = mysql_query($sqllevelupdate1);
	}

}


$qcarikelas = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT Count(id_kelas) AS jumlahrombel FROM kelas WHERE semester_id = '$semester_id'");
$carikelas = mysqli_fetch_array($qcarikelas);
$jumrombel = $carikelas['jumlahrombel'];
echo $jumrombel . ' Data Rombel/Kelas <br/>Sudah berhasil disinkronisasi';

?>
