<?php

//error_reporting(0);
include '../config/config.default.php';
include '../config/config.function.php';
include '../config/inc.library.php';
$semester_id = '20182';
$semester = substr($semester_id, 4, 1);
include '../config/koneksi1.php';
$qkelaskini =  pg_query("SELECT a.anggota_rombel_id, a.rombongan_belajar_id, a.peserta_didik_id, b.semester_id FROM anggota_rombel AS a INNER JOIN rombongan_belajar AS b ON a.rombongan_belajar_id = b.rombongan_belajar_id WHERE b.semester_id = '$semester_id' AND a.soft_delete = 0  AND a.last_update<=a.last_sync AND b.jenis_rombel IN ('1','8','9')");

while ($kelaskini =  pg_fetch_array($qkelaskini)) {
	$anggota_rombel_id = $kelaskini['anggota_rombel_id'];
	$peserta_didik_id = $kelaskini['peserta_didik_id'];
	$rombongan_belajar_id = $kelaskini['rombongan_belajar_id'];
	$semesterid = $kelaskini['semester_id'];

	//$jenis_rombel = $kelaskini['jenis_rombel'];
	//if (($jenis_rombel == '1') || ($jenis_rombel == '8') || ($jenis_rombel == '9')) {
	//	$ptk_id = $kelaskini['ptk_id'];
	//}
	//else {
	//	$ptk_id = 'Null';
	//}
include '../config/config.default.php';
	$qcarikelas =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT anggota_rombel_id FROM tabel_anggotakelas WHERE semester_id= '$semesterid' AND anggota_rombel_id= '$anggota_rombel_id' ");
	$carikelas =  mysqli_fetch_array($qcarikelas);
	$txtkelas = $carikelas['anggota_rombel_id'];

	if ($txtkelas) {
		$sqlkelasupdate = "UPDATE tabel_anggotakelas set peserta_didik_id= '$peserta_didik_id ', rombongan_belajar_id= '$rombongan_belajar_id ',semester_id= '$semesterid ' where anggota_rombel_id= '$txtkelas '";
		$qrySavekelass =  mysqli_query($GLOBALS["___mysqli_ston"], $sqlkelasupdate);
	}
	else {
		$qrySavekelas = "INSERT INTO tabel_anggotakelas VALUES( '$anggota_rombel_id',  '$peserta_didik_id', '$rombongan_belajar_id', '$semesterid')";
		$qrySavekelas2 =  mysqli_query($GLOBALS["___mysqli_ston"], $qrySavekelas);
	}
}

$qcarianggota =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT Count(anggota_rombel_id) AS jumanggota FROM tabel_anggotakelas WHERE semester_id= '$semester_id'");
$carianggota =  mysqli_fetch_array($qcarianggota);
$jumanggotarombel = $carianggota['jumanggota'];
echo $jumanggotarombel . ' Data Anggota Rombel<br/>Sudah berhasil disinkronisasi';
echo "\r\n\r\n";

?>
