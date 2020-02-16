					<div class='modal fade' id='tambahjadwal' style='display: none;'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='modal-header bg-maroon'>
									<button class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
									<h4 class='modal-title'><i class="fas fa-business-time fa-fw"></i> Tambah Jadwal Ujian</h4>
								</div>
								<div class='modal-body'>
									<form id="formtambahujian" method='post'>
										<div class='form-group'>
											<label>Nama Bank Soal</label>
											<select name='idmapel' class='form-control' required='true'>
												<?php
												if ($pengawas['level'] == 'admin') {
													$namamapelx = mysqli_query($koneksi, "SELECT * FROM mapel where status='1' order by nama ASC");
												} else {
													$namamapelx = mysqli_query($koneksi, "SELECT * FROM mapel where status='1' and idguru='$id_pengawas' order by nama ASC");
												}
												while ($namamapel = mysqli_fetch_array($namamapelx)) {
													$dataArray = unserialize($namamapel['kelas']);
													echo "<option value='$namamapel[id_mapel]'>$namamapel[nama] - $namamapel[level] - ";
													foreach ($dataArray as $key => $value) {
														echo "$value ";
													}
													echo "</option>";
												}
												?>
											</select>
										</div>
										<div class='form-group'>
											<label>Nama Jenis Ujian</label>
											<select name='kode_ujian' class='form-control' required='true'>
												<option value=''>Pilih Jenis Ujian </option>
												<?php
												$namaujianx = mysqli_query($koneksi, "SELECT * FROM jenis where status='aktif' order by nama ASC");
												while ($ujian = mysqli_fetch_array($namaujianx)) {
													echo "<option value='$ujian[id_jenis]'>$ujian[id_jenis] - $ujian[nama] </option>";
												}
												?>
											</select>
										</div>
										<div class='form-group'>
											<div class='row'>
												<div class='col-md-6'>
													<label>Tanggal Mulai Ujian</label>
													<input type='text' name='tgl_ujian' class='tgl form-control' autocomplete='off' required='true' />
												</div>
												<div class='col-md-6'>
													<label>Tanggal Waktu Expired</label>
													<input type='text' name='tgl_selesai' class='tgl form-control' autocomplete='off' required='true' />
												</div>
											</div>
										</div>
										<div class='form-group'>
											<label>Sesi</label>
											<select name='sesi' class='form-control' required='true'>
												<?php
												$sesix = mysqli_query($koneksi, "SELECT * from sesi");
												while ($sesi = mysqli_fetch_array($sesix)) {
													echo "<option value='$sesi[kode_sesi]'>$sesi[kode_sesi]</option>";
												}
												?>
											</select>
										</div>
										<div class='form-group'>
											<div class='row'>
												<div class='col-md-4'>
													<label>Lama ujian (menit)</label>
													<input type='number' name='lama_ujian' class='form-control' required='true' />
												</div>
												<div class='col-md-4'>
													<label>KKM</label>
													<input type='number' name='kkm' class='form-control' />
												</div>
												<div class='col-md-4'>
													<label>Jumlah Remidi</label>
													<input type='number' name='ulang' class='form-control' />
												</div>
											</div>
										</div>
										<div class='form-group'>
											<label></label><br>
											<label>
												<input type='checkbox' class='icheckbox_square-green' name='acak' value='1' $acak /> Acak Soal
											</label>

											<label>
												<input type='checkbox' class='icheckbox_square-green' name='token' value='1' $token /> Token Soal
											</label>

											<label>
												<input type='checkbox' class='icheckbox_square-green' name='hasil' value='1' $hasil /> Hasil Tampil
											</label>
										</div>
										<div class='modal-footer'>
											<button name='tambahjadwal' class='btn btn-sm btn-flat btn-success'><i class='fa fa-check'></i> Simpan Semua</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<div class='box box-solid'>
								<div class='box-header with-border '>
									<h3 class='box-title'><i class="fas fa-business-time fa-fw"></i> Jadwal Ujian</h3>
									<div class='box-tools pull-right '>
										<?php if ($setting['server'] == 'pusat') : ?>
											<?php if ($pengawas['level'] == 'admin') : ?>
												<a id='btnhapusjadwal' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-trash'></i> <span class='hidden-xs'>Kosongkan</span></a>
											<?php endif ?>
											<button class='btn btn-sm btn-flat btn-success' data-toggle='modal' data-backdrop='static' data-target='#tambahjadwal'><i class='glyphicon glyphicon-plus'></i> <span class='hidden-xs'>Tambah Jadwal</span></button>
										<?php endif ?>
									</div>
								</div><!-- /.box-header -->
								<div class='box-body' id="bodyreset">
									<div class=''>
										<div id='tablereset' class='table-responsive'>
											<table class='table table-bordered table-striped '>
												<thead>
													<tr>
														<th width='5px'><input type='checkbox' id='ceksemua'></th>
														<th width='5px'>#</th>
														<th>Mata Pelajaran</th>
														<th>Level/Jur/Kelas</th>
														<th>Durasi</th>
														<th>Tgl Waktu Ujian</th>
														<th>Sesi</th>
														<th>Acak/Token/Hasil</th>
														<th>Status</th>
														<th width='90px'></th>
													</tr>
												</thead>
												<tbody>
													<?php

													if ($pengawas['level'] == 'admin') {
														$mapelQ = mysqli_query($koneksi, "SELECT * FROM ujian ORDER BY tgl_ujian ASC, waktu_ujian ASC");
													} else {
														$mapelQ = mysqli_query($koneksi, "SELECT * FROM ujian where id_guru='$id_pengawas' ORDER BY tgl_ujian ASC, waktu_ujian ASC");
													}
													?>
													<?php while ($mapel = mysqli_fetch_array($mapelQ)) : ?>
														<?php
															$tgl = explode(" ", $mapel['tgl_ujian']);
															$tgl = $tgl[0];
															$no++;
															?>

														<tr>
															<td><input type='checkbox' name='cekpilih[]' class='cekpilih' id='cekpilih-<?= $no ?>' value="<?= $mapel['id_ujian'] ?>"></td>
															<td><?= $no ?></td>
															<td>
																<?php
																	if ($mapel['id_pk'] == '0') {
																		$jur = 'Semua';
																	} else {
																		$jur = $mapel['id_pk'];
																	}
																	?>
																<b><small class='label bg-red'><?= $mapel['kode_ujian'] ?></small> <small class='label bg-purple'><?= $mapel['nama'] ?></small></b>
															</td>
															<td>
																<small class='label label-primary'><?= $mapel['level'] ?></small>
																<small class='label label-primary'><?= $jur ?></small>
																<?php
																	$dataArray = unserialize($mapel['kelas']);
																	foreach ($dataArray as $key => $value) {
																		echo "<small class='label label-success'>$value </small>&nbsp;";
																	}
																	?>
															</td>
															<td><small class='label label-warning'>
																	<?= $mapel['tampil_pg'] ?> Soal / <?= $mapel['lama_ujian'] ?> m / <?= $mapel['opsi'] ?> opsi</small>
															</td>
															<td>
																<small class='label bg-purple'><i class='fa fa-clock-o'></i> <?= $mapel['tgl_ujian'] ?></small><small class='label bg-purple'><?= $mapel['tgl_selesai'] ?></small>
															</td>
															<td style="text-align:center">
																<small class='label bg-green'><?= $mapel['sesi'] ?></small>
															</td>
															<td>
																<?php
																	if ($mapel['acak'] == 1) {
																		echo "<label class='label label-success'>Ya</label> ";
																	} elseif ($mapel['acak'] == 0) {
																		echo "<label class='label label-danger'>Tidak</label> ";
																	}
																	if ($mapel['token'] == 1) {
																		echo "<label class='label label-success'>Ya</label> ";
																	} elseif ($mapel['token'] == 0) {
																		echo "<label class='label label-danger'>Tidak</label> ";
																	}
																	if ($mapel['hasil'] == 1) {
																		echo "<label class='label label-success'>Ya</label> ";
																	} elseif ($mapel['hasil'] == 0) {
																		echo "<label class='label label-danger'>Tidak</label> ";
																	}
																	?>
															</td>
															<td style="text-align:center">
																<?php
																	if ($mapel['status'] == 1) {
																		echo "<label class='label label-success'>Aktif</label>";
																	} elseif ($mapel['status'] == 0) {
																		echo "<label class='label label-danger'>Tidak Aktif</label>";
																	}
																	?>
															</td>
															<td style="text-align:center">
																<div class='btn-grou'>
																	<a class='btn btn-warning btn-flat btn-xs' data-id="<?= $mapel['id_ujian'] ?>" data-toggle='modal' data-backdrop="static" data-target="#edit<?= $mapel['id_ujian'] ?>"><i class='fa fa-edit'></i></a>
																</div>
															</td>
														</tr>
														<?php

															if ($setting['server'] == 'pusat') {
																$dis = '';
															} else {
																$dis = 'disabled';
															}
															?>

														<div class='modal fade' id="edit<?= $mapel['id_ujian'] ?>" style='display: none;'>
															<div class='modal-dialog'>
																<div class='modal-content'>
																	<div class='modal-header bg-olive'>
																		<button class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
																		<h4 class='modal-title'><i class="fas fa-business-time fa-fw"></i> Edit Jadwal Ujian</h4>
																	</div>
																	<div class='modal-body'>
																		<form id="formeditujian<?= $mapel['id_ujian'] ?>" method='post'>

																			<div class='form-group'>
																				<label>Nama Ujian</label>
																				<input type='text' name='namamapel' value="<?= $mapel['nama'] ?>" class='form-control' readonly />
																			</div>
																			<div class='form-group'>
																				<label>Nama Jenis Ujian</label>
																				<select name='kode_ujian' class='form-control' required='true' <?= $dis ?>>
																					<option value=''>Pilih Jenis Ujian </option>
																					<?php
																						$namaujianx = mysqli_query($koneksi, "SELECT * FROM jenis where status='aktif' order by nama ASC");
																						while ($ujian = mysqli_fetch_array($namaujianx)) {
																							($ujian['id_jenis'] == $mapel['kode_ujian']) ? $s = 'selected' : $s = '';
																							echo "<option value='$ujian[id_jenis]' $s>$ujian[id_jenis] - $ujian[nama] </option>";
																						}
																						?>
																				</select>
																			</div>
																			<div class='form-group'>
																				<div class='row'>
																					<div class='col-md-6'>
																						<label>Tanggal Ujian</label>
																						<input name='tgl_ujian' value="<?= $mapel['tgl_ujian'] ?>" autocomplete='off' class='tgl form-control' required='true' />
																					</div>
																					<div class='col-md-6'>
																						<label>Tanggal Selesai</label>
																						<input name='tgl_selesai' value="<?= $mapel['tgl_selesai'] ?>" autocomplete='off' class='tgl form-control' required='true' />
																					</div>
																				</div>
																			</div>
																			<div class='form-group'>
																				<div class='row'>
																					<div class='col-md-6'>
																						<label>Lama Ujian</label>
																						<input type='number' name='lama_ujian' value="<?= $mapel['lama_ujian'] ?>" class='form-control' required='true' />
																					</div>
																					<div class='col-md-6'>
																						<label>Sesi</label>
																						<input type='number' name='sesi' value="<?= $mapel['sesi'] ?>" class='form-control' required='true' />
																					</div>
																				</div>
																			</div>
																			<div class='form-group'>
																				<label>Status</label>
																				<select name='status' class='form-control'>
																					<option value='1'>Aktif</option>
																					<option value='0'>Tidak Aktif</option>
																				</select>
																			</div>
																			<input type='hidden' id='idm' name='idu' value="<?= $mapel['id_ujian'] ?>" />
																			<div class='modal-footer'>
																				<div class='box-tools pull-right '>
																					<button type='submit' name='update' class='btn btn-sm btn-flat btn-success'><i class='fa fa-check'></i> Update</button>
																					<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
																				</div>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
														<script>
															$("#formeditujian<?= $mapel['id_ujian'] ?>").submit(function(e) {
																e.preventDefault();
																$.ajax({
																	type: 'POST',
																	url: 'jadwal/edit_jadwal.php',
																	data: $(this).serialize(),
																	success: function(data) {
																		location.reload();
																		// if (data == "OK") {
																		//     toastr.success("jadwal berhasil diperbarui");
																		// }else{
																		//     toastr.error("jadwal gagal tersimpan");
																		// }
																		// $(".modal-backdrop").hide();
																		// $('#bodyreset').load(location.href + ' #bodyreset');
																	}
																});
																return false;
															});
														</script>
													<?php endwhile ?>
												</tbody>
											</table>
										</div>
									</div><!-- /.box-body -->
								</div><!-- /.box -->
							</div>
							<?php
							if ($ac == 'kosongkan') {
								mysqli_query($koneksi, "TRUNCATE ujian");
								jump('?pg=jadwal');
							}
							?>
						</div>
					</div>

					<script>
						$('#formtambahujian').submit(function(e) {
							e.preventDefault();
							$.ajax({
								type: 'POST',
								url: 'jadwal/tambah_jadwal.php',
								data: $(this).serialize(),
								success: function(data) {
									console.log(data);
									if (data == "OK") {
										toastr.success("jadwal berhasil dibuat");
									} else {
										toastr.error("jadwal gagal tersimpan");
									}
									$('#tambahjadwal').modal('hide');
									$('#tablereset').load(location.href + ' #tablereset');
								}
							});
							return false;
						});
					</script>