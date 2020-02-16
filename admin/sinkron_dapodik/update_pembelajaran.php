<?php
error_reporting(0);
include '../../config/config.default.php';
include '../../config/config.function.php';

$semester_id=$_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);
include 'koneksi.php';
$qcarisekolah = mysqli_query($koneksi, 'SELECT sekolah_id FROM sekolah');
$carisekl = mysqli_fetch_array($qcarisekolah);
$txtskl = $carisekl['sekolah_id'];
include 'koneksi1.php';
$qpembljkini = pg_query('SELECT * FROM pembelajaran WHERE semester_id =\'' . $semester_id . '\' AND soft_delete = 0  AND last_update<=last_sync');

while ($pembljkini = pg_fetch_array($qpembljkini)) {
	$pembelajaran_id = $pembljkini['pembelajaran_id'];
	$rombongan_belajar_id = $pembljkini['rombongan_belajar_id'];
	$mata_pelajaran_id = $pembljkini['mata_pelajaran_id'];
	$semester_id2 = $pembljkini['semester_id'];
	$ptk_terdaftar_id = $pembljkini['ptk_terdaftar_id'];
	$status_di_kurikulum = $pembljkini['status_di_kurikulum'];
	$nama_mata_pelajaran = pg_escape_string($pembljkini['nama_mata_pelajaran']);
	
	include 'koneksi.php';
	$qcaripem = mysqli_query($koneksi, 'SELECT pembelajaran_id FROM tabel_pembelajaran WHERE semester_id=\'' . $semester_id2 . '\' AND pembelajaran_id=\'' . $pembelajaran_id . '\'');
	$caripem = mysqli_fetch_array($qcaripem);
	$txtpemb = $caripem['pembelajaran_id'];

	if ($txtpemb) {
		$sqlpembljupdate = 'UPDATE tabel_pembelajaran set rombongan_belajar_id=\'' . $rombongan_belajar_id . '\', mata_pelajaran_id=\'' . $mata_pelajaran_id . '\',semester_id=\'' . $semester_id2 . '\',ptk_terdaftar_id=\'' . $ptk_terdaftar_id . '\', status_di_kurikulum=\'' . $status_di_kurikulum . '\',nama_mata_pelajaran=\'' . $nama_mata_pelajaran . '\' where pembelajaran_id=\'' . $pembelajaran_id . '\'';
		$qrySavepemb = mysqli_query($koneksi, $sqlpembljupdate);
	}
	else {
		$qrySavepemblj = 'INSERT INTO tabel_pembelajaran VALUES(\'' . $pembelajaran_id . '\', \'' . $rombongan_belajar_id . '\',\'' . $mata_pelajaran_id . '\',\'' . $semester_id2 . '\',\'' . $ptk_terdaftar_id . '\',\'' . $status_di_kurikulum . '\',\'' . $nama_mata_pelajaran . '\')';
		$qrySave = mysqli_query($koneksi, $qrySavepemblj);
	}
}

$qcaripembelajaran = mysqli_query($koneksi, 'SELECT count(pembelajaran_id) AS jum_pebl FROM tabel_pembelajaran WHERE semester_id=\'' . $semester_id . '\'');
$caripembelajaran = mysqli_fetch_array($qcaripembelajaran);
$jum_pembelajaran = $caripembelajaran['jum_pebl'];
echo $jum_pembelajaran . ' Data Pembelajaran<br/>Sudah berhasil disinkronisasi';
echo "\r\n\r\n";

?>
