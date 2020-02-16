<?php
//error_reporting(E_ALL);
require_once  '../../config/config.default.php';
require_once '../../config/config.function.php';

$semester_id = $_SESSION['semester_id'];
$semester = substr($semester_id, 4, 1);

include '../../config/koneksi1.php';
$qsekolah = pg_query('SELECT * FROM sekolah WHERE soft_delete = \'0\' AND bentuk_pendidikan_id in (6,10,8,29,54,15)');
while ($sekolah = pg_fetch_array($qsekolah)) {
	$sekolah_id = $sekolah['sekolah_id'];
	$nama = pg_escape_string($sekolah['nama']);
	$npsn = $sekolah['npsn'];
	$nss = $sekolah['nss'];
	$alamat_jalan = pg_escape_string($sekolah['alamat_jalan']);
	$kode_pos = $sekolah['kode_pos'];
	$telepon = $sekolah['nomor_telepon'];
	$nomor_fax = $sekolah['nomor_fax'];
	$desa_kelurahan = pg_escape_string($sekolah['desa_kelurahan']);
	$kode_wilayah = $sekolah['kode_wilayah'];
	$website = pg_escape_string($sekolah['website']);
	$email = pg_escape_string(pg_escape_string($sekolah['email']));
	$status_kepemilikan_id = $sekolah['status_kepemilikan_id'];
	$cprov = substr($kode_wilayah, 0, 2);
	$ckab = substr($kode_wilayah, 0, 4);
	$cket = substr($kode_wilayah, 0, 6);
	$kode_propinsi = $cprov . '0000';
	$kode_kab = $ckab . '00';
	$kode_kec = $cket;
	$bentukpend_id = $sekolah['bentuk_pendidikan_id'];
	if ($bentukpend_id == "5") {
		$jenjang = "SD";
	} elseif ($bentukpend_id == "6") {
		$jenjang = "SMP";
	} elseif ($bentukpend_id == "9") {
		$jenjang = "MI";
	} elseif ($bentukpend_id == "10") {
		$jenjang = "MTs";
	} elseif ($bentukpend_id == "13") {
		$jenjang = "SMA";
	} elseif ($bentukpend_id == "16") {
		$jenjang = "MA";
	} elseif ($bentukpend_id == "15") {
		$jenjang = "SMK";
	} else {
		$w = "Belum Ditentukan";
	}
	//$kd_sek = $jenjang . $kode_wilayah;
	include '../../config/koneksi1.php';
	$qcariprov = pg_query('SELECT * FROM ref.mst_wilayah' . "\r\n" . ' WHERE kode_wilayah = \'' . $kode_propinsi . '\' AND id_level_wilayah=\'1\'');
	$cariprov = pg_fetch_array($qcariprov);
	$propinsi = pg_escape_string($cariprov['nama']);
	$qcarikab = pg_query('SELECT * FROM ref.mst_wilayah' . "\r\n" . ' WHERE kode_wilayah = \'' . $kode_kab . '\' AND id_level_wilayah=\'2\'');
	$carikab = pg_fetch_array($qcarikab);
	$kbt = pg_escape_string($carikab['nama']);
	if ((substr($kbt, 0, 4) == 'Kab.') || (substr($kbt, 0, 4) == 'Kota')) {
		$kabupaten = substr($kbt, 4, 60);
	} else {
		$kabupaten = $kbt;
	}

	$qcarikec = pg_query('SELECT * FROM ref.mst_wilayah' . "\r\n" . ' WHERE kode_wilayah = \'' . $kode_kec . '\' AND id_level_wilayah=\'3\'');
	$carikec = pg_fetch_array($qcarikec);
	$kct = pg_escape_string($carikec['nama']);

	if (substr($kct, 0, 4) == 'Kec.') {
		$kecamatan = substr($kct, 4, 60);
	} else {
		$kecamatan = $kct;
	}

	// include '../../config/koneksi1.php';
	// $qcarikepsek = pg_query('SELECT' . "\r\n" . 'a.ptk_id,' . "\r\n" . 'a.nama,' . "\r\n" . 'a.nip,' . "\r\n" . 'a.niy_nigk' . "\r\n" . 'FROM' . "\r\n" . 'ptk AS a' . "\r\n" . 'INNER JOIN ptk_terdaftar AS b ON b.ptk_id= a.ptk_id' . "\r\n" . 'INNER JOIN ref.semester AS c ON c.tahun_ajaran_id = b.tahun_ajaran_id' . "\r\n" . 'WHERE' . "\r\n" . 'a.soft_delete = 0 AND' . "\r\n" . 'b.soft_delete = 0 AND' . "\r\n" . 'c.semester_id = \'' . $semester_id . '\' AND' . "\r\n" . 'a.jenis_ptk_id IN (\'20\') AND' . "\r\n" . 'b.jenis_keluar_id IS NULL');
	// $carikepsek = pg_fetch_array($qcarikepsek);
	// $ptkidcek = $carikepsek['ptk_id'];
	// $nip_kepsek = pg_escape_string($carikepsek['nip']);
	// $niy_kepsek = pg_escape_string($carikepsek['niy_nigk']);
	// $dpng = '';
	// $blkg = '';

	// include '../../config/koneksi1.php';
	// $qglrdpn = pg_query('SELECT' . "\r\n" . 'a.ptk_id,' . "\r\n" . 'c.posisi_gelar,' . "\r\n" . 'c.kode' . "\r\n" . 'FROM' . "\r\n" . 'ptk AS a' . "\r\n" . 'INNER JOIN rwy_pend_formal AS b ON b.ptk_id = a.ptk_id' . "\r\n" . 'INNER JOIN ref.gelar_akademik AS c ON b.gelar_akademik_id = c.gelar_akademik_id' . "\r\n" . 'WHERE' . "\r\n" . 'b.soft_delete = 0 AND' . "\r\n" . 'a.soft_delete = 0 AND' . "\r\n" . 'b.gelar_akademik_id IS NOT NULL AND' . "\r\n" . 'b.status_kuliah = 0 AND' . "\r\n" . 'a.ptk_id=\'' . $ptkidcek . '\' AND c.posisi_gelar=\'1\'' . "\r\n" . 'AND b.jenjang_pendidikan_id >=30' . "\r\n" . 'ORDER BY' . "\r\n" . 'a.ptk_id ASC,' . "\r\n" . 'b.jenjang_pendidikan_id ASC');

	// while ($cgdpn = pg_fetch_array($qglrdpn)) {
	// 	$dpng .= $cgdpn['kode'] . '. ';
	// }

	// include '../../config/koneksi1.php';
	// $qglrblk = pg_query('SELECT' . "\r\n" . 'a.ptk_id,' . "\r\n" . 'c.posisi_gelar,' . "\r\n" . 'c.kode' . "\r\n" . 'FROM' . "\r\n" . 'ptk AS a' . "\r\n" . 'INNER JOIN rwy_pend_formal AS b ON b.ptk_id = a.ptk_id' . "\r\n" . 'INNER JOIN ref.gelar_akademik AS c ON b.gelar_akademik_id = c.gelar_akademik_id' . "\r\n" . 'WHERE' . "\r\n" . 'b.soft_delete = 0 AND' . "\r\n" . 'a.soft_delete = 0 AND' . "\r\n" . 'b.gelar_akademik_id IS NOT NULL AND' . "\r\n" . 'b.status_kuliah = 0 AND' . "\r\n" . 'a.ptk_id=\'' . $ptkidcek . '\' AND c.posisi_gelar=\'2\'' . "\r\n" . 'AND b.jenjang_pendidikan_id >=30' . "\r\n" . 'ORDER BY' . "\r\n" . 'a.ptk_id ASC,' . "\r\n" . 'b.jenjang_pendidikan_id ASC');

	// while ($cgblk = pg_fetch_array($qglrblk)) {
	// 	$blkg .= ', ' . $cgblk['kode'] . '. ';
	// }
	// $nm_kepsek = pg_escape_string(trim($dpng . ' ' . $carikepsek['nama'] . '' . $blkg));
	require_once  '../../config/config.default.php';
	$qcarisekolah1 = mysqli_query($koneksi, 'SELECT id_setting,sekolah_id FROM setting WHERE id_setting=1');
	$carisekl1 = mysqli_num_rows($qcarisekolah1);

	if ($carisekl1 <> 0) {
		$sqlsekolahupdate1 = mysqli_query($koneksi, "UPDATE setting SET 
	aplikasi='$nama', sekolah='$nama', npsn='$npsn',jenjang='$jenjang', kode_sekolah='$npsn',  alamat='$alamat_jalan', kecamatan='$kecamatan', kota='$kabupaten', telp='$telepon', fax='$nomor_fax', web='$website', email='$email', sekolah_id='$sekolah_id' WHERE id_setting='1' ");
		//$qrySavesekl1 = mysql_query($sqlsekolahupdate1);
	}
	echo '<font size="2">Data ' . $nama . ' Sudah berhasil disinkronisasi</font>';
}
