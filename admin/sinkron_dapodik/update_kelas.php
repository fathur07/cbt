<?php

require_once  '../../config/config.default.php';
require_once '../../config/config.function.php';
require_once '../../config/inc.library.php';

$semester_id = $_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

include '../../config/koneksi1.php';
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

	/* include '../../config/config.default.php';
	  $q = mysql_query("SELECT * FROM jurusan WHERE jurusan_id='SMP0000'");
	  $dtq = mysql_fetch_array($q);
	  $jurusan_id = $dtq['jurusan_id'];
	  $nama_jurusan_sp = mysql_escape_string($dtq['nama_jurusan_sp']);
	  $jurusan_sp_id = $dtq['jurusan_sp_id'];
	*/
	
	/* ===========data kelas lokal=========== */
	require_once  '../../config/config.default.php';
	$qcarikelas2 = mysqli_query($koneksi, "SELECT rombongan_belajar_id FROM kelas WHERE rombongan_belajar_id='$rombongan_belajar_id' AND semester_id='$semester_id2'");
	$carikelas2 = mysqli_fetch_array($qcarikelas2);
	$txtkelas2 = $carikelas2['rombongan_belajar_id'];

	if ($txtkelas2) {
		$sqlkelasupdate2 = mysqli_query($koneksi, "UPDATE kelas set id_kelas='$nama', level='$tingkat_pendidikan_id', nama='$nama', semester_id='$semester_id2' WHERE rombongan_belajar_id='$txtkelas2'");
		
		/*  $sqlkelasupdate2 = mysqli_query($koneksi, "UPDATE kelas set id_kelas='$nama', level='$tingkat_pendidikan_id', nama='$nama', ptk_id='$ptk_id',semester_id='$semester_id2' WHERE rombongan_belajar_id='$txtkelas2'");
		$qrySavekelass2 = mysql_query($sqlkelasupdate2); */
	}
	else {
		$qrySavekls2 = mysqli_query($koneksi, "INSERT INTO kelas VALUES('$nama', '$tingkat_pendidikan_id', '$nama', '$rombongan_belajar_id','$semester_id2')");
		/* $qrySavekls2 = mysqli_query($koneksi, "INSERT INTO kelas VALUES('$nama', '$tingkat_pendidikan_id', '$nama', '$rombongan_belajar_id', '$ptk_id', '$semester_id2')");
		$qrySavekelas22 = mysql_query($qrySavekls2); */
		
	}

	/* ============data level lokal=========== */
	require_once  '../../config/config.default.php';
	$qcarilevel = mysqli_query($koneksi, "SELECT rombongan_belajar_id FROM level WHERE rombongan_belajar_id='$rombongan_belajar_id' ");
	$carilevel = mysqli_fetch_array($qcarilevel);
	$txtkelas = $carilevel['rombongan_belajar_id'];

	if ($txtkelas) {
		$sqllevelupdate = mysqli_query($koneksi, "UPDATE level set kode_level='$tingkat_pendidikan_id', keterangan='$nmtingkat' WHERE rombongan_belajar_id='$txtkelas' ");
		/*$qrylevelupdate = mysql_query($sqllevelupdate); */
	}
	else {
		$sqllevelupdate1 = mysqli_query($koneksi, "INSERT INTO level VALUES('$tingkat_pendidikan_id', '$nmtingkat', '$rombongan_belajar_id') ");
		/* $qrylevelupdate1 = mysql_query($sqllevelupdate1); */
	}

}



$qcarikelas = mysqli_query($koneksi, "SELECT Count(rombongan_belajar_id) AS jumlahrombel FROM kelas WHERE semester_id = '$semester_id'");
$carikelas = mysqli_fetch_array($qcarikelas);
$jumrombel = $carikelas['jumlahrombel'];
echo '<font size="2">'.$jumrombel . ' Sudah berhasil disinkronisasi</font>';

?>
