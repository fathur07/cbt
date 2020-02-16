<?php
//session_start();
require_once  '../config/config.default.php';
require_once  '../config/config.function.php';
$cek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM semester where periode_aktif='1'"));

$_SESSION['semester_id'] = $cek['semester_id'];
$semester_id = $_SESSION['semester_id'];

$sql = mysqli_query($koneksi, "SELECT jenjang FROM setting");
$r = mysqli_fetch_array($sql);
$jjang = $r['jenjang'];
if ($jjang == "SMK") {
	$jenjang = "6";
} elseif ($jjang == "SMP") {
	$jenjang = "5";
} elseif ($jjang == "SD") {
	$jenjang = "4";
} else {
	$jenjang = "-";
}
//($r['jenjang']=='SMP') ? $stat ='col-md-4 col-sm-6 hide' : $stat ='col-md-4 col-sm-6';
// Membuat Username dan Password 5 Karakter sebanyak 20 Buah
//echo $semester_id;
include '../config/koneksi1.php';
$semester2 = pg_fetch_array(pg_query("SELECT * FROM ref.semester  WHERE ref.semester.tahun_ajaran_id >= 2016 order by ref.semester.nama"));
$semester_id2 = $semester2['semester_id'];
$semester_nama = $semester2['nama'];
include '../config/koneksi1.php';
$querydp = pg_query('SELECT * FROM sekolah');
$resultdp = pg_fetch_array($querydp);
$kodedp = $resultdp['sekolah_id'];
//echo $semester_nama;
if (!$kodedp) {
	echo '<div class="alert alert-danger fade in m-b-15"><b style="font-size:25px;color:red">DISCONNNECT!</b>&nbsp;
	<p>Server Anda Tidak Terhubung dengan Server Dapodik Lokal
	<p>Fitur ini Hanya untuk digunakan dalam mode Local Jaringan bersama Aplikasi Dapodik<p>
	<span class="close" data-dismiss="alert">×</span></div>';
} else {

	//include '../config/config.default.php';
	$qcarisekolah = mysqli_query($koneksi, 'SELECT sekolah_id, sekolah FROM setting WHERE id_setting=1');
	$carisekolah = mysqli_fetch_assoc($qcarisekolah);
	$sekolah_id = $carisekolah['sekolah_id'];
	$sekolah_nama = $carisekolah['sekolah'];

	//include '../config/config.default.php';
	$qcariptk = mysqli_query($koneksi, 'SELECT Count(level) AS jumlahptk FROM pengawas WHERE level = "guru"');
	$cariptk = mysqli_fetch_array($qcariptk);
	$jumlahptk = $cariptk['jumlahptk'];

	//include '../config/config.default.php';
	$qcarimapel = mysqli_query($koneksi, 'SELECT count(mata_pelajaran_id) AS jumlah FROM mata_pelajaran');
	$carimapel = mysqli_fetch_assoc($qcarimapel);
	$jumlmapel = $carimapel['jumlah'];
	//include '../config/config.default.php';
	//$qcariekstra = mysql_query('SELECT Count(a.id_kelas_ekskul) AS jumlah FROM tabel_kelas_ekskul AS a INNER JOIN tabel_kelas AS b ON a.rombongan_belajar_id = b.rombongan_belajar_id WHERE b.semester_id = \'' . $semester_id . '\'' . "\r\n");
	//$cariekstraq = mysql_fetch_array($qcariekstra);
	//$jumlekstra = $cariekstraq['jumlah'];
	$qcarijurusan = mysqli_query($koneksi, 'SELECT count(jurusan_id) AS jumlah FROM pk');
	$carijurusan = mysqli_fetch_assoc($qcarijurusan);
	$jumljurusan = $carijurusan['jumlah'];

	//include '../config/config.default.php';
	$qcarisiswa = mysqli_query($koneksi, "SELECT Count(peserta_didik_id) AS jumlahsiswa FROM siswa");
	//$qcarisiswa = mysql_query('SELECT Count(c.peserta_didik_id) AS jumlahsiswa FROM tabel_anggotakelas AS a INNER JOIN tabel_kelas AS b ON b.rombongan_belajar_id = a.rombongan_belajar_id AND b.semester_id = a.semester_id INNER JOIN tabel_siswa AS c ON c.peserta_didik_id = a.peserta_didik_id' . "\r\n" . 'WHERE a.semester_id = \'' . $semester_id . '\' AND b.jenis_rombel IN (\'1\',\'8\',\'9\')');
	$carisiswa = mysqli_fetch_assoc($qcarisiswa);
	$jumlah_siswa = $carisiswa['jumlahsiswa'];

	//include '../config/config.default.php';
	$qcarisemester = mysqli_query($koneksi, 'SELECT count(semester_id) AS jumlahsmt FROM semester');
	$carismt = mysqli_fetch_assoc($qcarisemester);
	$jumlah_smt = $carismt['jumlahsmt'];

	//include '../config/config.default.php';
	$qcarikelas = mysqli_query($koneksi, 'SELECT Count(rombongan_belajar_id) AS jumlahrombel FROM kelas WHERE semester_id = "' . $semester_id . '"');
	$carikelas = mysqli_fetch_assoc($qcarikelas);
	$jumrombel = $carikelas['jumlahrombel'];
	//include '../config/config.default.php';
	//$qcarianggota = mysqli_query($koneksi, 'SELECT Count(anggota_rombel_id) AS jumanggota FROM tabel_anggotakelas WHERE semester_id="' . //$semester_id . '"');
	//$carianggota = mysqli_fetch_assoc($qcarianggota);
	//$jumanggotarombel = $carianggota['jumanggota'];
	//include '../config/config.default.php';
	//$qcaripembelajaran = mysqli_query($koneksi, 'SELECT count(pembelajaran_id) AS jum_pebl FROM tabel_pembelajaran WHERE semester_id="' . //$semester_id . '"');
	//$caripembelajaran = mysqli_fetch_assoc($qcaripembelajaran);
	//$jum_pembelajaran = $caripembelajaran['jum_pebl'];

	echo '
	
	<div class="row">
		<div class="col-lg-12"> 
			<div class="alert alert-danger fade in m-b-15"><strong>Perhatian!</strong>Pastikan bahwa data di dapodik sudah benar, dan Selalu lakukan sinkron/pengambilan data dapodik pada halaman ini jika anda melakukan perubahan data pada dapodik. 
			<span class="close" data-dismiss="alert">×</span>
			</div>
			
					<div class="box box-solid" >
                        <div class="box-header with-border">
							<h3 class="box-title"> &nbsp;&nbsp; Ambil Data Referensi Dapodik</h3>
                            <div class="box-tools">
								<div class="btn-group pull-right" style="background-color:transparent;">
									<a href="#" target="_self" alt="Sinkron" class="btn btn-danger btn-sm" onclick="tampil(\'update_sekolah\')">
									<i class="glyphicon glyphicon-random"></i> &nbsp;Sinkron Semua Data</a>
	                                <a href="#" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                                    <span class="caret"></span>
	                                </a>
	                                <ul class="dropdown-menu" role="menu" style="z-index:1100">
	                                	<li>
	                                	<a href="#" data-toggle="modal" data-target="#salinPembelajaran">
	                                	Salin Pembelajaran</a>
	                                	</li>
	                                	<!-- <li><a href="?pg=rombel">Salin Pembelajaran</a></li> -->
	                                    <li><a href="?pg=data_ptk">daftar ptk</a></li>
	                                    <li><a href="#">Import Periodik Siswa</a></li>
	                                    <li><a href="#">Import Jadwal</a></li>
	                                    <li><a href="#">Import Periodik Siswa</a></li>
	                                    <li class="divider"></li>
	                                    <li><a href="#">Import Nilai</a></li>
	                                </ul>
	                            </div>
                            </div>
                        </div>
                        <div class="box-body">
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if ($sekolah_id) {
		echo 'bg-blue';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if ($sekolah_id) {
		echo '<h3 id="txtupdate_sekolah"><font size="2">' . $sekolah_nama . '</font></h3>';
	} else {
		echo '<h3 id="txtupdate_sekolah"><font size="2">Belum disinkron</font></h3>';
	}
	echo ' 
									
										<p>Data Sekolah
									</div>
									<div class="icon">
										<i class="fa fa-university"></i>
									</div>
									<a href="#" id="update_sekolah" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Sekolah</a>
									<div align="center" id="loadupdate_sekolah" ></div>
								</div>
							</div>
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumlah_smt) {
		echo 'bg-yellow';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (0 < $jumlah_smt) {
		echo '<h3 id="txtupdate_semester">' . $jumlah_smt . '</h3>';
	} else {
		echo '<h3 id="txtupdate_semester">0</h3>';
	}
	echo ' 
									
										<p>Data Semester
									</div>
									<div class="icon">
										<i class="fa fa-calendar"></i>
									</div>
									<a href="#" id="update_semester" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Semester</a>
									<div align="center" id="loadupdate_semester" ></div>
								</div>
							</div>
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (5 < $jumlmapel) {
		echo 'bg-green';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (5 < $jumlmapel) {
		echo '<h3 id="txtupdate_mapel">' . $jumlmapel . '</h3>';
	} else {
		echo '<h3 id="txtupdate_mapel">0</h3>';
	}
	echo ' 
									
										<p>Data Mapel
									</div>
									<div class="icon">
										<i class="fa fa-book"></i>
									</div>
									<a href="#" id="update_mapel" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data mapel</a>
									<div align="center" id="loadupdate_mapel" ></div>
								</div>
							</div>
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumrombel) {
		echo 'bg-red';
	} else {
		echo 'bg-red';
	}
	echo '">
					
									<div class="inner">';
	if (0 < $jumrombel) {
		echo '<h3 id="txtupdate_kelas">' . $jumrombel . '</h3>';
	} else {
		echo '<h3 id="txtupdate_kelas">0</h3>';
	}
	echo ' 
									
										<p>Data Kelas
									</div>
									<div class="icon">
										<i class="fa fa-home"></i>
									</div>
									<a href="#" id="update_kelas" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Rombel</a>
									<div align="center" id="loadupdate_kelas" ></div>
								</div>
							</div>
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumlahptk) {
		echo 'bg-purple';
	} else {
		echo 'bg-red';
	}
	echo '">
							
									<div class="inner">';
	if (0 < $jumlahptk) {
		echo '<h3 id="txtupdate_guru">' . $jumlahptk . '</h3>';
	} else {
		echo '<h3 id="txtupdate_guru">0</h3>';
	}
	echo ' 
									
										<p>Data Guru
									</div>
									<div class="icon">
										<i class="fa fa-users"></i>
									</div>
									<a href="#" id="update_guru" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Guru</a>
									<div align="center" id="loadupdate_guru" ></div>
								</div>
							</div>
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumlah_siswa) {
		echo 'bg-blue';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (0 < $jumlah_siswa) {
		echo '<h3 id="txtupdate_siswa">' . $jumlah_siswa . '</h3>';
	} else {
		echo '<h3 id="txtupdate_siswa">0</h3>';
	}
	echo ' 
									
										<p>Data Siswa
									</div>
									<div class="icon">
										<i class="fa fa-user"></i>
									</div>
									<a href="#" id="update_siswa" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Siswa</a>
									<div align="center" id="loadupdate_siswa" ></div>
								</div>
							</div>
							
							<!--
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumanggotarombel) {
		echo 'bg-yellow';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (0 < $jumanggotarombel) {
		echo '<h3 id="txtupdate_anggota_kelas">' . $jumanggotarombel . '</h3>';
	} else {
		echo '<h3 id="txtupdate_anggota_kelas">0</h3>';
	}
	echo ' 
									
										<p>Data Anggota Rombel
									</div>
									<div class="icon">
										<i class="fa fa-users"></i>
									</div>
									<a href="#" id="update_anggota_kelas" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Anggota Rombel</a>
									<div align="center" id="loadupdate_anggota_kelas" ></div>
								</div>
							</div>
							-->
							
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jumljurusan) {
		echo 'bg-orange';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (0 < $jumljurusan) {
		echo '<h3 id="txtupdate_peminatan">' . $jumljurusan . '</h3>';
	} else {
		echo '<h3 id="txtupdate_peminatan">0</h3>';
	}
	echo ' 
									
										<p>Data Jurusan
									</div>
									<div class="icon">
										<i class="fa fa-diamond"></i>
									</div>
									<a href="#" id="update_peminatan" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Jurusan</a>
									<div align="center" id="loadupdate_peminatan" ></div>
								</div>
							</div>
							
							<!--
							<div class="col-lg-3 col-lg-6">
								<div class="small-box ';
	if (0 < $jum_pembelajaran) {
		echo 'bg-green';
	} else {
		echo 'bg-red';
	}
	echo '">
								
									<div class="inner">';
	if (0 < $jum_pembelajaran) {
		echo '<h3 id="txtupdate_pembelajaran">' . $jum_pembelajaran . '</h3>';
	} else {
		echo '<h3 id="txtupdate_pembelajaran">0</h3>';
	}
	echo ' 
									
										<p>Data Pembelajaran
									</div>
									<div class="icon">
										<i class="fa fa-server"></i>
									</div>
									<a href="#" id="update_pembelajaran" onclick="showCD(this.id)" class="small-box-footer"><i class="fa fa-refresh"></i>&nbsp;Sinkron Data Pemebelajaran</a>
									<div align="center" id="loadupdate_pembelajaran" ></div>
								</div>
							</div>
							-->
							
						</div>
					</div> 
			</div>
	</div>';


	echo '
			<div class="modal fade in" id="salinPembelajaran" style="display: none; padding-right: 17px;">
				<div class="modal-dialog">
					<form action="" method="POST">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title">Salin Pembelajaran</h4>
						</div>
						<div class="modal-body">
						<label>Dari</label>
		                    /* <!-- <div class="form-group">
		                        <label for="rombel">Semester</label>
		                        <select class="form-control" name="semester" id="semester">
									<option> $semester_id </option>';
	//include 'koneksi1.php';	
	//$jpend = pg_query("SELECT * FROM ref.tingkat_pendidikan WHERE jenjang_pendidikan_id='".$jenjang."' ");
	//while($j = pg_fetch_array($jpend)) {
	//$tingkat = $j['tingkat_pendidikan_id'];
	//echo '
	//<option value='.$tingkat.'>Tingkat &nbsp;'.$tingkat.'<i class="fa fa-caret-square-o-down" aria-hidden="true"></i></option>';
	// }
	echo ' 	
								</select>
		                    </div> --> */
		                    <div class="form-group">
		                        <label for="rombel">Semester</label>
		                        <select class="form-control select2" name="semester" id="semester" style="width:100%">
									<option> Tahun Pelajaran </option>
									<option value=' . $semester_id2 . '>Tahun Pelajaran &nbsp;' . $semester_nama . '</option>
								</select>
							</div>
		                    <div class="form-group">
		                        <label for="kelas">Kelas</label>
		                        <select name="dr_kelas" id="dr_kelas" class="form-control select2" style="width:100%">
								<option> Pilih Kelas </option>';
	include 'koneksi1.php';
	$sql1 = pg_query("SELECT * FROM rombongan_belajar WHERE  semester_id='$semester_id2' AND soft_delete=0 AND jenis_rombel=1 order by nama ");
	while ($line = pg_fetch_array($sql1)) {
		$idtingkat = $line['tingkat_pendidikan_id'];
		$idrombel = $line['rombongan_belajar_id'];
		$nm_rombel = $line['nama'];
		echo '
									<option value=' . $idrombel . '>Tingkat ' . $idtingkat . '&nbsp;' . $nm_rombel . '</option>';
	}
	echo '
								</select>
		                    </div>
		                    <hr />
		                    <div class="form-group">
			                    <div class="row">
			                    	<div class="col-md-6">
				                        <label>No. SK Tugas</label>
				                        <input class="form-control" name="no_sk" id="no_sk">
				                    </div>
				                    <div class="col-md-6">
				                        <label>Tanggal. SK Tugas</label>
				                        <div class="input-group date">
										    <input type="text" name="tgl_sk" id="tgl_sk" class="form-control datepicker" placeholder="Select Date">
										    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
				                    </div>
								</div>
		                    </div>
		                    <div class="form-group">
		                        <label for="kelas">Kelas</label>
		                        <select name="ke_kelas" id="ke_kelas" class="form-control select2" style="width:100%" required="true">
								<option> Pilih Kelas </option>';
	include 'koneksi1.php';
	$sql2 = pg_query("SELECT * FROM rombongan_belajar WHERE  semester_id='$semester_id' AND soft_delete=0 AND jenis_rombel=1 order by nama ");
	while ($line2 = pg_fetch_array($sql2)) {
		$idtingkat2 = $line2['tingkat_pendidikan_id'];
		$idrombel2 = $line2['rombongan_belajar_id'];
		$nm_rombel2 = $line2['nama'];
		echo '
									<option value=' . $idrombel2 . '>Tingkat ' . $idtingkat2 . '&nbsp;' . $nm_rombel2 . '</option>';
	}
	echo '
								</select>
		                    </div>       
						</div>
						<div class="modal-footer">
							<button type="submit" name="salin" class="btn btn-sm btn-inverse"><i class="fa fa-copy"></i>&nbsp; Salin</button>
		                    <button class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
						</div>
					</div>
					</form>
				</div>
			</div>';


	echo '
			

			<script src="$homeurl/dist/bootstrap/js/bootstrap.min.js"></script>
			<script src="$homeurl/plugins/jQuery/jquery-2.2.3.min.js"></script>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>  
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<!--
			<script type="text/javascript">
				$(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
					 $("#datatingkat").change(function(){ // Ketika user mengganti atau memilih data provinsi
						 // Sembunyikan dulu combobox kota nya
						var tingkat=$(this).val();
						console.log(tingkat);
						$.ajax({
							type: "POST", // Method pengiriman data bisa dengan GET atau POST
							url: "datarombel.php",// Isi dengan url/path file php yang dituju
							data: "tingkat="+tingkat, // data yang akan dikirim ke file yang dituju
							success: function(response){ // Ketika proses pengiriman berhasil
										
							$("#datakelas").html(response);
							}
						});
					});
				});
			</script> -->';
}

?>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>

<script type="text/javascript">
	function showCD(str) {
		if (str == "") {
			document.getElementById("txt" + str).innerHTML = "";
			return;
		}

		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari  

			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5  
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			mulaiload("load" + str);
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("txt" + str).innerHTML = xmlhttp.responseText;
				tutupload("load" + str);
				//location.replace("?pg=sinkron");
			}
		}

		xmlhttp.open("GET", "sinkron_dapodik/" + str + ".php", true);
		xmlhttp.send();
	}

	function tampil(str) {
		if (str == "") {
			document.getElementById("txt" + str).innerHTML = "";
			return;
		}
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari  
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5  
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {

			mulaiload("load" + str);
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("txt" + str).innerHTML = xmlhttp.responseText;
				tutupload("load" + str);
				if (str == "update_sekolah") {

					tampil("update_semester");
					return;
				} else if (str == "update_semester") {
					tampil("update_mapel");
					return;
				} else if (str == "update_mapel") {
					tampil("update_kelas");
					return;
				} else if (str == "update_kelas") {
					tampil("update_guru");
					return;
				} else if (str == "update_guru") {
					tampil("update_siswa");
					return;
				} else if (str == "update_siswa") {
					tampil("update_peminatan");
					return;
					/*}else if(str=="update_peminatan"){
					tampil("update_anggota_kelas");return;}else if(str=="update_anggota_kelas"){
					tampil("update_pembelajaran");return;*/
				}
				//location.replace("?pg=sinkron");    
			}
		}
		xmlhttp.open("GET", "sinkron_dapodik/" + str + ".php", true);
		xmlhttp.send();

	}
</script>


<script type="text/javascript">
	function jalankanload() {
		document.getElementById('loading').classList.add("overlay");
		document.getElementById('loading').innerHTML = "<img src='../dist/img/loader.gif' align='center' width='50px' alt='Loading...'' /><B>Sedang memproses data,Tunggu sesaat lagi.......</B><";
	}

	function matikanload() {
		document.getElementById('loading').classList.remove("overlay");
		document.getElementById('loading').innerHTML = "";
	}

	function mulaiload(data) {
		document.getElementById(data).classList.add("overlay");
		document.getElementById(data).innerHTML = "<img src='../dist/img/loader.gif' align='center' width='50px' alt='Loading...'' />";
	}

	function tutupload(data) {
		document.getElementById(data).classList.remove("overlay");
		document.getElementById(data).innerHTML = "";
	}
</script>