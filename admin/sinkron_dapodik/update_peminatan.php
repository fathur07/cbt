<?php
require_once '../../config/config.default.php';
require_once '../../config/config.function.php';
require_once '../../config/inc.library.php';

$id_sekolah = $setting['sekolah_id'];
$semester_id = $_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

include '../../config/koneksi1.php';
$q = pg_query('SELECT * FROM ref.jurusan');
while ($jurlist = pg_fetch_array($q)) {
	$nama_jurusan = $jurlist['nama_jurusan'];
	$jurusan_id = $jurlist['jurusan_id'];
	$untuk_sma = $jurlist['untuk_sma'];
	$untuk_smk = $jurlist['untuk_smk'];
	$jenjang_pendidikan_id = $jurlist['jenjang_pendidikan_id'];
	$jurusan_induk = $jurlist['jurusan_induk'];

	require_once '../../config/config.default.php';
	$qcarijur = mysqli_query($koneksi, 'SELECT * FROM referensi_jurusan WHERE jenjang_pendidikan_id=NULL AND nama_jurusan=Null');
	$carijur = mysqli_fetch_array($qcarijur);
	$txtjur = $carijur['jenjang_pendidikan_id'];

	if ($txtjur) {
		$sqljurupdate = 'UPDATE referensi_jurusan SET nama_jurusan=\'' . $nama_jurusan . '\', untuk_sma=\'' . $untuk_sma . '\', untuk_smk=\'' . $untuk_smk . '\', jurusan_id=\'' . $jurusan_id . '\' where jenjang_pendidikan_id=\'' . $txtjur . '\'';
		$qrysavejur = mysqli_query($koneksi, $sqljurupdate);
	} else {
		$qsavejur = 'INSERT INTO referensi_jurusan VALUES(\'' . $jurusan_id . '\', \'' . $nama_jurusan . '\', \'' . $untuk_sma . '\', \'' . $untuk_smk . '\', \'' . $txtjur . '\')';
		$qrysavejuru = mysqli_query($koneksi, $qsavejur);
	}
}

include '../../config/koneksi1.php';
$qminatkini = pg_query("SELECT * FROM public.jurusan_sp AS a WHERE a.soft_delete = 0 ");
while ($minatkini = pg_fetch_array($qminatkini)) {
	$nama_jurusan_sp = pg_escape_string($minatkini['nama_jurusan_sp']);
	$jurusan_id = $minatkini['jurusan_id'];
	$jurusan_sp_id = $minatkini['jurusan_sp_id'];
	$sekolah_id = $minatkini['sekolah_id'];
	$arr = explode(' ', $nama_jurusan_sp);
	$singkatan = '';
	foreach ($arr as $kata) {
		$singkatan .= substr($kata, 0, 1);
	}


	require_once '../../config/config.default.php';
	$qcariminat = mysqli_query($koneksi, 'SELECT * FROM jurusan WHERE jurusan_id=\'' . $jurusan_id . '\'');
	$cariminat = mysqli_fetch_array($qcariminat);
	$txtminat = $cariminat['jurusan_id'];

	if ($txtminat) {
		$sqlminatupdate = 'UPDATE jurusan set nama_jurusan_sp=\'' . $nama_jurusan_sp . '\', jurusan_sp_id=\'' . $jurusan_sp_id . '\',' . "\n\t" . ' sekolah_id=\'' . $sekolah_id . '\', kurikulum=\'SMP\',kurikulum_id=Null' . "\n\t" . ' ' . "\n" . ' WHERE jurusan_id=\'' . $jurusan_id . '\'';
		$qrySaveminat = mysqli_query($koneksi, $sqlminatupdate);
	} else {
		$qrySaveminat = 'INSERT INTO jurusan VALUES(\'' . $jurusan_id . '\', \'' . $nama_jurusan_sp . '\',\'SMP\',\'' . $jurusan_sp_id . '\',Null,\'' . $sekolah_id . '\')';
		$qrySaveminat = mysqli_query($koneksi, $qrySaveminat);
	}


	$qcariminat2 = mysqli_query($koneksi, 'SELECT * FROM pk WHERE jurusan_id=\'' . $jurusan_id . '\'');
	$cariminat2 = mysqli_fetch_array($qcariminat2);

	$txtminat2 = $cariminat2['jurusan_id'];
	if ($txtminat2) {
		$sqlminatupdate2 = 'UPDATE pk set kode=\'' . $singkatan . '\', program_keahlian=\'' . $nama_jurusan_sp . '\', id_pk=\'' . $singkatan . '\'  WHERE jurusan_id=\'' . $jurusan_id . '\'';
		$qrySaveminat2 = mysqli_query($koneksi, $sqlminatupdate2);
	} else {
		$qrySaveminat3 = 'INSERT INTO pk (id_pk,program_keahlian,jurusan_id)VALUES(\'' . $singkatan . '\', \'' . $nama_jurusan_sp . '\', \'' . $jurusan_id . '\')';
		$qrySavemina2 = mysqli_query($koneksi, $qrySaveminat3);
	}
}

/*
require_once '../../config/config.default.php';
$qcariminatx = mysqli_query($koneksi, 'SELECT * FROM jurusan where jurusan_id=\'SMP0000\'');
$cariminatx = mysqli_fetch_array($qcariminatx);
$txtminatx = $cariminatx['jurusan_id'];
$jurusan_sp_id = $cariminatx['jurusan_sp_id'];
if ($txtminatx) {
}
else {
	$q = mysqli_query($koneksi, 'SELECT sekolah_id FROM setting');
	$dq = mysqli_fetch_array($q);
	$sekolah_idx = $dq['sekolah_id'];
	$qrySaveminat = 'INSERT INTO jurusan VALUES(\'SMP0000\', \'Reguler\',\'SMP\',\'' . gen_uuid() . '\',Null,\'' . $sekolah_idx . '\')';
	$qrySaveminatx = mysqli_query($koneksi, $qrySaveminat);
	
}
*/


$qcarijurusan = mysqli_query($koneksi, 'SELECT count(jurusan_id) AS jumlah FROM pk');
$carijurusan = mysqli_fetch_array($qcarijurusan);
$jumljurusan = $carijurusan['jumlah'];
echo '<font size="2">' . $jumljurusan . ' Sudah berhasil disinkronisasi</font>';
