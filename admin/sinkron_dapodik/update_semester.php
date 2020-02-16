<?php
error_reporting(0);
require_once  '../../config/config.default.php';
require_once '../../config/config.function.php';

$semester_id=$_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

include '../../config/koneksi1.php';
$qminatkini = pg_query('SELECT * FROM ref.semester WHERE ref.semester.tahun_ajaran_id >= 2014');

while ($minatkini = pg_fetch_array($qminatkini)) {
	$semester_id = $minatkini['semester_id'];
	$nama_semester = pg_escape_string($minatkini['nama']);
	$semester = $minatkini['semester'];
	$periode_aktif = $minatkini['periode_aktif'];
	$tanggal_mulai = $minatkini['tanggal_mulai'];
	$tanggal_selesai = $minatkini['tanggal_selesai'];
	$tahun_ajaran_id = $minatkini['tahun_ajaran_id'];
	
require_once  '../../config/config.default.php';
	$qcariminat = mysqli_query($koneksi, 'SELECT * FROM semester where semester_id=\'' . $semester_id . '\'');
	$cariminat = mysqli_fetch_array($qcariminat);
	$txtminat = $cariminat['semester_id'];

	if ($txtminat) {
		
	}
	else {
		$qrySaveminat = 'INSERT INTO semester VALUES(\'' . $semester_id . '\', \'' . $tahun_ajaran_id . '\',\'' . $nama_semester . '\',\'' . $semester . '\', \'' . $periode_aktif . '\',\'' . $tanggal_mulai . '\',\'' . $tanggal_selesai . '\')';
		$qrySaveminat = mysqli_query($koneksi, $qrySaveminat);
	}
}

require_once  '../../config/config.default.php';
$qcarisemester = mysqli_query($koneksi, 'SELECT count(semester_id) AS jumlahsmt FROM semester');
$carismt = mysqli_fetch_array($qcarisemester);
$jumlah_smt = $carismt['jumlahsmt'];
echo '<font size="2">'.$jumlah_smt . ' Sudah berhasil dsinkronisasi</font>';


?>
