<?php
/*
function agamasis($kd) {
include '../../config/koneksi1.php';
	$q = pg_query('SELECT * FROM ref.agama WHERE agama_id =\'' . $kd . '\'');
	$row = pg_fetch_assoc($q);
	$Rows = pg_num_rows($q);
	$kd = $row['nama'];

	if ($Rows < 1) {
		$kd = '-';
	}

	return $kd;
}

function kerjaortu($kd) {
include '../../config/koneksi1.php';
	$q = pg_query('SELECT * FROM ref.pekerjaan WHERE pekerjaan_id =\'' . $kd . '\'');
	$row = pg_fetch_assoc($q);
	$Rows = pg_fetch_assoc($q);
	$kd = $row['nama'];

	if ($Rows < 1) {
		$kd = '-';
	}

	return $kd;
}
*/
require_once '../../config/config.default.php';
require_once '../../config/config.function.php';
require_once '../../config/inc.library.php';
$semester_id = $_SESSION['semester_id'];

include '../../config/koneksi1.php';

$qsiswaini = pg_query("SELECT * FROM peserta_didik AS a INNER JOIN registrasi_peserta_didik AS b ON b.peserta_didik_id = a.peserta_didik_id
INNER JOIN anggota_rombel AS c ON c.peserta_didik_id = b.peserta_didik_id /*INNER JOIN rombongan_belajar AS d ON d.rombongan_belajar_id = c.rombongan_belajar_id*/  WHERE a.soft_delete = 0 AND b.soft_delete = 0 AND c.soft_delete = 0 AND /*d.semester_id = '$semester_id' AND*/ b.jenis_keluar_id IS NULL  AND a.last_update<=a.last_sync AND c.last_update<=c.last_sync /*AND d.jenis_rombel IN ('1','8','9')*/");

while ($siswakini = pg_fetch_array($qsiswaini)) {
	$peserta_didik_id = $siswakini['peserta_didik_id'];
	$nis = pg_escape_string($siswakini['nipd']);
	$nisn = $siswakini['nisn'];
	$nm_siswa = pg_escape_string($siswakini['nama']);
	$tempat_lahir = pg_escape_string($siswakini['tempat_lahir']);
	$tanggal_lahir = $siswakini['tanggal_lahir'];
	$jenis_kelamin = $siswakini['jenis_kelamin'];
	$agama_id = $siswakini['agama_id'];
	$alamat_siswa = pg_escape_string($siswakini['alamat_jalan']);
	$telepon_siswa = $siswakini['nomor_telepon_rumah'];
	$anak_keberapa = number_format($siswakini['anak_keberapa'], 0);
	$diterima_tanggal = $siswakini['tanggal_masuk_sekolah'];
	$nm_ayah = pg_escape_string($siswakini['nama_ayah']);
	$nm_ibu = pg_escape_string($siswakini['nama_ibu_kandung']);
	$pekerjaan_ayah = $siswakini['pekerjaan_id_ayah'];
	$pekerjaan_ibu = $siswakini['pekerjaan_id_ibu'];
	$nm_wali = pg_escape_string($siswakini['nama_wali']);
	$pekerjaan_wali = $siswakini['pekerjaan_id_wali'];
	$status_dalam_kel = 'Anak Kandung';
	$sekolah_asal = pg_escape_string($siswakini['sekolah_asal']);
	$diterima_kelas = 'VII';
	$alamat_ortu = pg_escape_string($siswakini['alamat_jalan']);
	$telepon_ortu = '';
	$alamat_wali = '';
	$telepon_wali = '';
	$anggota_rombel_id = $siswakini['anggota_rombel_id'];

	/* $rombongan_belajar_id = $siswakini['rombongan_belajar_id'];
		$agama = agamasis($agama_id);
		$kerja_ayah = kerjaortu($pekerjaan_ayah);
		$kerja_ibu = kerjaortu($pekerjaan_ibu);
		$kerja_wali = kerjaortu($pekerjaan_wali);
		*/
	/*  include 'koneksi1.php';
		$qkelaskini =  pg_query("SELECT a.anggota_rombel_id, a.rombongan_belajar_id, a.peserta_didik_id,b.semester_id FROM anggota_rombel AS a INNER JOIN rombongan_belajar AS b ON a.rombongan_belajar_id = b.rombongan_belajar_id  WHERE b.semester_id = '$semester_id' AND a.soft_delete = 0 AND a.last_update<=a.last_sync");

		$kelaskini =  pg_fetch_array($qkelaskini);
		$anggota_rombel_id = $kelaskini['anggota_rombel_id'];
		$peserta_didik_id2 = $kelaskini['peserta_didik_id'] ;
		$rombongan_belajar_id = $kelaskini['rombongan_belajar_id'];
		$nama_rombel = $kelaskini['nama'];
		$level = $kelaskini['tingkat_pendidikan_id']; 
			$semesterid = $kelaskini['semester_id'];

		}

	
		$qkelaskini =  pg_query("SELECT a.anggota_rombel_id, a.rombongan_belajar_id, a.peserta_didik_id, b.semester_id FROM anggota_rombel AS a INNER JOIN rombongan_belajar AS b ON a.rombongan_belajar_id = b.rombongan_belajar_id WHERE b.semester_id = '$semester_id' AND a.soft_delete = 0 AND a.last_update<=a.last_sync");

		$kelaskini =  pg_fetch_array($qkelaskini);
		$anggota_rombel_id = $kelaskini['anggota_rombel_id'];
		$rombongan_belajar_id = $kelaskini['rombongan_belajar_id'];
		$semesterid = $kelaskini['semester_id'];
		$peserta_didik_id = $kelaskini['peserta_didik_id'];
	*/

	require_once '../../config/config.default.php';
	$qcarisiswa = mysqli_query($koneksi, "SELECT * FROM siswa where peserta_didik_id='$peserta_didik_id' AND semester_id= '$semester_id'");
	$carisiswa = mysqli_fetch_array($qcarisiswa);
	$kd_kelas = $carisiswa['id_kelas'];
	$txtsiswa = $carisiswa['peserta_didik_id'];
	$pass = mt_rand(100000, 999999) . '*';
	$nopes = '19-01-0022-' . mt_rand(1000, 9999);
	if ($txtsiswa) {
		$sqlsiswaupdate = "UPDATE siswa SET 
			nis='$nis', nisn='$nisn',sesi='1',no_peserta='$nopes',username='$nisn',password='$pass',nama='$nm_siswa',tempat_lahir='$tempat_lahir',tanggal_lahir='$tanggal_lahir',jenis_kelamin='$jenis_kelamin', alamat='$alamat_siswa', hp='$telepon_siswa', /*diterima_tanggal='$diterima_tanggal', */nama_ayah='$nm_ayah', nama_ibu='$nm_ibu', nama_wali='$nm_wali', semester_id='$semester_id' where peserta_didik_id ='$txtsiswa'";
		$qrySavesiswa = mysqli_query($koneksi, $sqlsiswaupdate);
	} else {
		$sqlsiswaupdate = "INSERT INTO siswa (idpk,nis, nisn, sesi,no_peserta,username, password, nama,tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, hp, nama_ayah, nama_ibu, nama_wali, peserta_didik_id, semester_id) VALUES(
			'$nis', '$nisn', '1', '$nopes', '$nisn', '$pass', '$nm_siswa', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$alamat_siswa', '$telepon_siswa', /*'$diterima_tanggal',*/ '$nm_ayah', '$nm_ibu', '$nm_wali', '$peserta_didik_id', '$semester_id' )";
		$qrySavesiswa = mysqli_query($koneksi, $sqlsiswaupdate);
	}
}
include '../../config/koneksi1.php';
$qsiswa = pg_query("SELECT *  FROM anggota_rombel AS a INNER JOIN rombongan_belajar AS b ON b.rombongan_belajar_id=a.rombongan_belajar_id where a.soft_delete = 0 AND b.semester_id = '$semester_id' AND a.last_update<=a.last_sync AND b.jenis_rombel IN ('1','8','9')");
while ($siswa_kini = pg_fetch_array($qsiswa)) {

	$nm_rombel = pg_escape_string($siswa_kini['nama']);
	$level	= $siswa_kini['tingkat_pendidikan_id'];
	$arombel	= $siswa_kini['anggota_rombel_id'];
	$idrombel	= $siswa_kini['rombongan_belajar_id'];
	$id_peserta_didik = $siswa_kini['peserta_didik_id'];

	require_once '../../config/config.default.php';
	$q = mysqli_query($koneksi, "SELECT * From siswa where peserta_didik_id='$id_peserta_didik' AND semester_id= '$semester_id'  ");
	$result = mysqli_fetch_array($q);
	$txtrombel = $result['peserta_didik_id'];

	if ($txtrombel) {
		$siswaupdate = "UPDATE siswa Set level='$level', id_kelas='$nm_rombel', /*anggota_rombel_id='$arombel',*/ rombongan_belajar_id='$idrombel' WHERE peserta_didik_id='$txtrombel' ";
		$savesiswa = mysqli_query($koneksi, $siswaupdate);
	} else {
		$siswainsert = "INSERT INTO siswa (level, id_kelas, peserta_didik_id, /*anggota_rombel_id,*/ rombongan_belajar_id) VALUES ('$level','$nm_rombel','$id_peserta_didik', /*'$arombel',*/ '$idrombel') ";
		$savesiswa = mysqli_query($koneksi, $siswainsert);
	}
}




/*  include 'koneksi.php';
			$qcarikelas =  mysql_query("SELECT anggota_rombel_id FROM siswa WHERE semester_id= '$semesterid' AND anggota_rombel_id= '$anggota_rombel_id'");
			$carikelas =  mysql_fetch_array($qcarikelas);
			$txtkelas = $carikelas['anggota_rombel_id'];

			if ($txtkelas) {
			$sqlkelasupdate = mysql_query("UPDATE siswa set peserta_didik_id= '$peserta_didik_id ', rombongan_belajar_id= '$rombongan_belajar_id',semester_id= '$semesterid ' where anggota_rombel_id= '$txtkelas'");
				$qrySavekelass =  mysql_query($sqlkelasupdate);
			}
			else {
			$qrySavekelas = mysql_query("INSERT INTO siswa VALUES( '$anggota_rombel_id',  '$peserta_didik_id', '$rombongan_belajar_id', '$semesterid')");
				$qrySavekelas2 =  mysql_query($qrySavekelas);
			}
		

			$qcarisiswaterdaftar = mysql_query("SELECT * FROM tabel_siswa_pelengkap where peserta_didik_id='$peserta_didik_id'");
			$carisiswaterdaftar = mysql_fetch_array($qcarisiswaterdaftar);
			$txtsiswaterdaftar = $carisiswaterdaftar['peserta_didik_id'];

			if ($txtsiswaterdaftar) {
			$sqlsiswaterdaftar = mysql_query("UPDATE tabel_siswa_pelengkap set status_dalam_kel= '$status_dalam_kel', diterima_kelas='$diterima_kelas', alamat_ortu= '$alamat_ortu',foto_siswa='$foto_siswa',sekolah_asal='$sekolah_asal', anak_ke='$anak_keberapa' where peserta_didik_id='$peserta_didik_id'");
				$qrySavesiswaterdaftar = mysql_query($sqlsiswaterdaftar);
			}
			else {
			$sqlsiswaterdaftar = mysql_query("INSERT INTO tabel_siswa_pelengkap VALUES(gen_uuid(),'$peserta_didik_id', '$status_dalam_kel','$anak_keberapa','$sekolah_asal','$diterima_kelas', '$alamat_ortu', '','','','$foto_siswa')");
				$qrySavesiswaterdaftar = mysql_query($sqlsiswaterdaftar);
			}

		  
			$qcarisiswa = mysql_query("SELECT Count(c.peserta_didik_id) AS jumlahsiswa FROM tabel_anggotakelas AS a INNER JOIN tabel_kelas AS b ON b.rombongan_belajar_id = a.rombongan_belajar_id AND b.semester_id = a.semester_id INNER JOIN tabel_siswa AS c ON c.peserta_didik_id = a.peserta_didik_id WHERE a.semester_id = '$semester_id' AND b.jenis_rombel IN ('1','8','9')");
		*/
$qcarisiswa = mysqli_query($koneksi, "SELECT Count(peserta_didik_id) AS jumlahsiswa FROM siswa");
$carisiswa = mysqli_fetch_array($qcarisiswa);
$jumlah_siswa = $carisiswa['jumlahsiswa'];
echo '<font size="2">' . $jumlah_siswa . ' Siswa berhasil disinkronisasi</font>';
