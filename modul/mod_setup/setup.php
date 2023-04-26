<script type="text/javascript">
	$(function () {
		$("#tmb_perkiraan").click(function () {
			$("#perkiraan").slideDown();
			$("#col").show();
			$("#loading").show();
		});
		$("#dob").click(function () {
			$("#perkiraan").slideUp();
			$("#loading").hide();
		});
		$("#batal").click(function () {
			$("#perkiraan").slideUp();
			$("#loading").hide();
		});
	});
</script>
<?php
$aksi="../modul/mod_setup/aksi_setup.php";
switch($_GET[act]){
  // Tampil barang
  default:
  if($_SESSION['leveluser']=='admin'){

	?><!-- DATA TABLE SCRIPTS -->
<script src="assets/js/dataTables/datatables.min.js"></script>
<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
<script>
	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0, 1, 4]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [4]
				}
			],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Master Akun</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Master</a></li>
			<li class="breadcrumb-item active"><a href="#">Akun</a></li>
			
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tabel Akun</h5>
					<button type="button"
						style="margin-right: 10px; position: absolute; right: 12px; top: 13px; box-shadow: 0 2px 6px #ffc473; background: #ffa426; color: white; font-size: 13px"
						class="btn btn-warning" onclick="window.location.href='rekeningtambah';"><i
							class="fa fa-plus mr-1"></i>
						Tambah Akun</button>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Id Rekening</th>
									<th scope="col">Nama Rekening</th>
									<th scope="col">Jumlah</th>
									<th scope="col" style="width: 15%;">Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php		
							$tampil=mysql_query("SELECT * FROM rekening,jns_rek WHERE rekening.jenis=kd_jns ORDER BY rekening.kd_rek");
							$total_debit=mysql_fetch_array(mysql_query("SELECT sum(jumlah) as total FROM rekening WHERE jenis='1'")) or die (mysql_error());
							$total_kredit=mysql_fetch_array(mysql_query("SELECT sum(jumlah) as total FROM rekening WHERE jenis='2'"))or die (mysql_error());
							$no=1;
							$subtot_pel=0;
							while($r = mysql_fetch_array($tampil)){
							$pel = $no;
							$total_pel = $subtot_pel + pel;
									echo"<tr class='odd gradeX'>
											<td>$no</td>
											<td>$r[kd_rek]</td>
										<td>$r[nama_rekening]</td>";
											echo"<td>Rp.&nbsp".number_format($r[jumlah],2,',','.');echo"</td>
										<td>
										<button class='btn btn-info btn-sm mr-1'><a style='color: white;' href=rekeningedit.$r[kd_rek]><i class='fa fa-edit'></i></a></button>
										<button class='btn btn-danger btn-sm'><a style='color: white'; Onclick=\"deleteAkun('$aksi', '$r[id_dokter]', '$r[nm_dokter]')\"><i class='bi bi-trash-fill'></i></a></button>
								</tr>";
								$no++;
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<script>
	function deleteAkun(aksi, id, nama) {
		swal({
				title: 'Apakah anda yakin?',
				text: `Apakah anda yakin akan menghapus data ${nama} dari daftar Akun ?`,
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					window.location.href = `${aksi}?op=delete&kode=${id}`;
				}
			});
	}
</script>
<!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Master Akun</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Kode Rekening</label>
					<input type="text" value="" id="id_rekening" size="3" class="form-control">
					<div id='pesan'></div>
					<label>Nama Rekening</label>
					<input type="text" value="" id="nm_rekening" class="form-control">
					<label>Jenis</label>
					<select id='jenis' class='form-control'>";
						<option value='1'>Debit</option>
						<option value='2'>Kredit</option>
						<option value='' selected>Pilih</option>
					</select>
					<label>Jumlah</label>
					<input type="text" value="" id="jumlah" class="form-control">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id='simpan' class='btn btn-default'>Simpan</button>
			</div>
		</div>
	</div>
</div> -->
<?php

} else{
			echo"<div id='peretas'><center><img src='../img/icons/32x32_0400/exclamation.png'><ss><p>$_SESSION[namalengkap]&nbspdilarang mengakses halaman ini.
			<br>TINDAKAN MERTAS SISTEM </p></ss></center></div>";
		}
	break;
	case "tambahrekening":
	?>
<div class="pagetitle" style="position: relative;">
	<h1>Master Akun</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Master</a></li>
			<li class="breadcrumb-item"><a href="rekening">Akun</a></li>
			<li class="breadcrumb-item active"><a href="#">Tambah Akun</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-7">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tambah Akun</h5>
				</div>
				<div id="pesan"></div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label>Id Rekening</label>
                                <input type="text" name="id_rekening" id="id_rekening" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="form-group">
                                <label>Nama Rekening</label>
                                <input type="text" name="nm_rekening" id="nm_rekening" class="form-control">
                            </div>
                        </div>
						<div class="col-12 col-md-4 col-lg-4">
							<div class="form-group">
								<label>Jenis</label>
								<select id='jenis' class='form-control form-select'>";
									<option value='' selected>Pilih</option>
									<option value='1'>Debit</option>
									<option value='2'>Kredit</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-8 col-lg-8">
							<div class="form-group">
								<label>Jumlah</label>
								<input type="text" name="jumlah" id="jumlah" class="form-control">
							</div>
						</div>
                    </div>
				</div>
				<div class="modal-footer bg-whitesmoke p-3" style="border: 1px solid #ebeef4; gap: 10px">
                    <button type="button" class="btn btn-secondary" onClick="self.history.back()">Batal</button>
                    <button type="button" id="simpan" class="btn btn-warning">Simpan</button>
                </div>
			</div>
		</div>
	</div>
</section>

<?php
	break;
	case "editrekening":
	  if($_SESSION['leveluser']=='admin'){
	$edit=mysql_query("SELECT * FROM rekening WHERE kd_rek='$_GET[id]'");
    $r=mysql_fetch_array($edit);
   ?>
<div class="pagetitle" style="position: relative;">
	<h1>Master Akun</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Master</a></li>
			<li class="breadcrumb-item"><a href="rekening">Akun</a></li>
			<li class="breadcrumb-item active"><a href="#">Edit Akun</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-7">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Edit Akun</h5>
				</div>
				<div id="pesan"></div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label>Id Rekening</label>
                                <input type="text" name="id_rekening" value="<?php echo"$r[kd_rek]";?>" id="id_rekening" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="form-group">
                                <label>Nama Rekening</label>
                                <input type="text" name="nm_rekening" value="<?php echo"$r[nama_rekening]";?>" id="nm_rekening" class="form-control">
                            </div>
                        </div>
						<div class="col-12 col-md-4 col-lg-4">
							<div class="form-group">
								<label>Jenis</label>
								<?php
								echo"
									<select id='jenis' class='form-control form-select'>";
								if($r[jenis]==1){					
										echo"<option value='1' selected>Debit</option>
											<option value='2'>Kredit</option>";
								}else {	echo"<option value='1' >Debit</option>
											<option value='2' selected>Kredit</option>";
										}echo"</select>";
								?>
							</div>
						</div>
						<div class="col-12 col-md-8 col-lg-8">
							<div class="form-group">
								<label>Jumlah</label>
								<input type="text" value="<?php echo"$r[jumlah]";?>" name="jumlah" id="jumlah" class="form-control">
							</div>
						</div>
                    </div>
				</div>
				<div class="modal-footer bg-whitesmoke p-3" style="border: 1px solid #ebeef4; gap: 10px">
                    <button type="button" class="btn btn-secondary" onClick="self.history.back()">Batal</button>
                    <button type="button" id="update" class="btn btn-warning">Simpan</button>
                </div>
			</div>
		</div>
	</div>
</section>
<?php
		  } else{
			echo"<div id='peretas'><center><img src='../img/icons/32x32_0400/exclamation.png'><ss><p>$_SESSION[namalengkap]&nbspdilarang mengakses halaman ini.
			<br>TINDAKAN MERTAS SISTEM </p></ss></center></div>";
		}
 
}
?>